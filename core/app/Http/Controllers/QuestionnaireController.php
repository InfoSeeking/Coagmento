<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Stage;
use App\Models\Task;
use App\Models\StageProgress;
use App\Models\QuestionnairePosttask;
use App\Models\QuestionnairePretask;
/*<<<<<<< HEAD*/
use App\Value;
/*=======*/
use App\QuestionnaireQuerySegment;
/*>>>>>>> 522fe00c574f57c9d7fd957f4db379933c7a6191*/
use Illuminate\Http\Request;
use Auth;
use App\Utilities\Status;
use App\Utilities\StatusCodes;

use App\Http\Requests;
use App\Services\StageProgressService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class QuestionnaireController extends Controller
{

    public function __construct(StageProgressService $stageProgressService) {
        $this->stageProgressService = $stageProgressService;
        $this->user = Auth::user();
    }

    public function getPretask(Request $req){
        //Redirect admins; I think this can actually be put elsewhere.
        $user= Auth::user();
        if($user->active && $user->is_admin){
            Auth::login($user, $req->has('remember'));
            return redirect('/admin');
        }

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

    public function postQuerySegmentQuestionnaire(Request $req){
        $questionnaire = new QuestionnaireQuerySegment($req->all());
        $questionnaire->save();
        return response()->json(['success'=>true]);
    }

    public function postPretask(Request $req){

        $user = Auth::user();
        $this->validate($req, [
            'search_difficulty' => 'required',
            'information_understanding' => 'required',
            'decide_usefulness' => 'required',
            'information_integration' => 'required',
            'topic_prev_knowledge' => 'required',
            'information_sufficient' => 'required',
            'goal_specific' => 'required',
            'task_pre_difficulty' => 'required',
            'narrow_information' => 'required',
            'task_newinformation' => 'required',
            'task_unspecified' => 'required',
            'task_detail' => 'required',
            'task_knowspecific' => 'required',
            'task_specificitems' => 'required',
            'task_factors' => 'required',
            'queries_start' => 'required',
            'know_usefulinfo' => 'required',
            'useful_notobtain' => 'required',
            'task_interest' => 'required',
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
            'difficulty_search' => 'required',
            'difficulty_understand' => 'required',
            'difficulty_usefulinformation' => 'required',
            'difficulty_integrate' => 'required',
            'difficulty_enoughinformation' => 'required',






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
    public function manageQuestionnaires()
    {
        $questionnaires = Questionnaire::all();
        return view('admin.manage_questionnaires', compact('questionnaires'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create_questionnaire');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    public function store(Request $request){

        $arr = $request->input("questions");
        $user=Auth::User();
        $questionnaire = $user->questionnaires()->create([
            'title' => $request->input('title'),
        ]);
        $createQuestion = null;
        foreach ($arr as $key=>$question){
            //$tempValues = $question['values'];
            $tempValues=null;
            if(array_key_exists("values", $question)) {
                $tempValues = $question['values'];
                $question['values'] = null;
            }
            $createQuestion = $questionnaire->questions()->create($question);
            if(array_key_exists("required", $question)){
                $createQuestion->required = true;
                $createQuestion->save();
            }
            if(array_key_exists("inline", $question)){
                $createQuestion->inline = true;
                $createQuestion->save();
            }
            if ($tempValues != null){
                foreach ($tempValues as $k=>$value){
                    $val = $createQuestion->values()->create($value);
                    if(array_key_exists("selected", $value)){
                        $val->selected = true;
                        $val->save();
                    }
                }
            }
        }
        return Question::where('questionnaire_id',$questionnaire->id)->get();
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
