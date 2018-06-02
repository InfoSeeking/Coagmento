<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Task;
use App\Models\StageProgress;
use App\Models\QuestionnairePosttask;
use App\Models\QuestionnairePretask;
use Illuminate\Http\Request;
use Auth;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

use App\Http\Requests;
use App\Services\StageProgressService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class QuestionnaireController extends Controller
{

    public function __construct(StageProgressService $stageProgressService) {
        $this->stageProgressService = $stageProgressService;
        $this->user = Auth::user();
    }

    public function getPretask(Request $req){
        $currentStage = $this->getCurrentStageId();
        $taskID = -1;
        if($currentStage <= 5){
            $taskID = 1;
        }else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('questionnaire_pretask',['task'=>$task]);
    }

    public function postPretask(Request $req){

        $user = Auth::user();
        $this->validate($req, [
            'search_difficulty' => 'required',
            'information_understanding' => 'required',
            'decide_usefulness' => 'required',
            'information_integration' => 'required',
            'information_sufficient' => 'required',
        ]);
        $req->merge(['user_id' => $user->id]);
        $req->merge(['stage_id' => Session::get('stage_id')]);
        $pretask = new QuestionnairePretask($req->all());
        $pretask->save();
        return app()->make('App\Http\Controllers\StageProgressController')->callAction('moveToNextStage',['request'=>$req]);
    }

    public function postPosttask(Request $req){
        $user = Auth::user();
        $this->validate($req, [
            'satisfaction' => 'required',
            'system_helpfulness' => 'required',
            'goal_success' => 'required',
            'mental_demand' => 'required',
            'physical_demand' => 'required',
            'temporal_demand' => 'required',
            'effort' => 'required',
            'frustration' => 'required',
//            'difficulty' => 'required',
//            'task_success' => 'required',
//            'enough_time' => 'required',
        ]);
        $req->merge(['user_id' => $user->id]);
        $req->merge(['stage_id' => Session::get('stage_id')]);
        $posttask = new QuestionnairePosttask($req->all());
        $posttask->save();
        return app()->make('App\Http\Controllers\StageProgressController')->callAction('moveToNextStage',['request'=>$req]);
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
        if($currentStage <= 15){
            $taskID = 1;
        }else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('task_description',['task'=>$task]);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
