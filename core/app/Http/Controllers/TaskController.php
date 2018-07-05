<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Stage;
use App\Models\Task;
use App\Models\StageProgress;
use App\Models\TaskAttributeAssignment;
use Illuminate\Http\Request;
use Auth;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

use App\Http\Requests;
use App\Services\StageProgressService;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{

    public function __construct(StageProgressService $stageProgressService) {
        $this->stageProgressService = $stageProgressService;
        $this->user = Auth::user();
        $this->middleware('admin',
            ['only'=>['createTask','destroy', 'addTask', 'update', 'editTask']]
        );
    }

    public function getCurrentStageId() {
        $stageProgress = StageProgress::all()->where('user_id', $this->user->id)->last();
        if (is_null($stageProgress)) {
            $first_stage_id = Stage::all()->first()['id'];
//            $first_stage_id = Status::fromResult(Stage::all()->first())->getResult()->id;
            StageProgress::create([
                'user_id' => $this->user->id,
                'stage_id' => $first_stage_id,
                'created_at_local' => Carbon::createFromTimestamp(1523835589)
            ])->save();

            return $first_stage_id;
//            return Status::fromResult(Stage::all()->first());
//                StageProgress::fromError('No stage progress found.', StatusCodes::NOT_FOUND);
        }else{
            $stage = Stage::all()->where('id', Status::fromResult($stageProgress)->getResult()->stage_id)->first();
            return $stage['id'];
        }

    }

    public function getTaskDescription(){
        $currentStage = $this->getCurrentStageId();
        $taskID = -1;
        if($currentStage <= 15){
            $taskID = 1;
        }else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('task_description',['task'=>$task]);
    }


    public function getTask(){
        $currentStage = $this->getCurrentStageId();
        $taskID = -1;

        if($currentStage <= 3) {
            $taskID = 0;
        }
        else if($currentStage <= 15){
            $taskID = 1;
        }else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('task',['task'=>$task]);
    }


    public function directToStage(){
        $stage = $this->stageProgressService->getCurrentStage();
        $stage->getResult();

//        dd($stage->getResult());
//        dd($stage->getResult()->page);
        return redirect($stage->getResult()->page);
    }

    public function moveToNextStage(){
        $this->stageProgressService->moveToNextStage();


        return redirect('/stages');
    }

    /**
     *
     * Creates a task that belongs to an admin user.
     *
     * @param Request $request
     * @param $id
     * @return Task
     */
    public function createTask(Request $request){
        $this->validate($request, [
            'description' => 'required',
        ]);
        $task=Task::create([
            'description' => $request->input('description'),
        ]);
        $task->save();
        //$attributes=Attribute::all();
        $values = $request->input('option_values'); //withorwithout brackets?
        foreach($request->input('attribute_ids') as $attribute_id) {//^^
            $task->attributes()->attach($attribute_id, ['value'=>$values[$attribute_id]]);
            /*$task->attributes()->create([
                'attribute_id' => $attribute_id,
                'task_id' => $task->id,
                'value' => $values[$attribute_id],
            ])->save();*/
        }

        return $task;
    }

    public function editTask($id){
        $task = Task::all()->find($id);
        $attributes = Attribute::all();
        $assignments = TaskAttributeAssignment::where('task_id', $task->id);
        return view('admin.edit_task', compact('task', 'attributes', 'assignments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $task = Task::all()->find($id);
        if($task->description != $request->input('description')){
            $task->description = $request->input('description');
        }
        $task->save();

        $values = ($request->input('option_values'));
        foreach($request->input('attribute_ids') as $attribute_id) {
            $task->attributes()->save(Attribute::findOrFail($attribute_id), ['value' => $values[$attribute_id]]);//sync([$attribute_id => ['value'=>$values[$attribute_id]]]);
        }

        $attributes = Attribute::all();
        $assignments = TaskAttributeAssignment::where('task_id', $task->id)->get();

        return view('admin.edit_task', compact('task', 'attributes', 'assignments'));
    }

    public function addTask(Request $request){
        $task = $this->createTask($request);
        $task->save();
        $tasks= Task::all();
        $attributes = Attribute::all();
        $assignments = TaskAttributeAssignment::where('task_id', $task->id)->get();
        return view('admin.manage_tasks', compact('tasks', 'attributes', 'assignments'));
    }

    public function destroy($id){
        Task::destroy($id);
        TaskAttributeAssignment::where('task_id', $id)->delete();
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }




}
