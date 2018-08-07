<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Stage;
use App\Models\Task;
use App\Models\StageProgress;
use App\Models\QuestionnairePosttask;
use App\Models\QuestionnairePretask;
use App\Models\Value;
use App\Models\QuestionnaireQuerySegment;
use App\Models\QuestionnaireHelpAndBarrier;
use Illuminate\Http\Request;
use Auth;
use App\Utilities\Status;
use App\Utilities\StatusCodes;
use App\Http\Requests;
use App\Services\StageProgressService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
//use Illuminate\Validation\Validator;
use Validator;

class QuestionnaireController extends Controller
{

    public function __construct(StageProgressService $stageProgressService) {
        $this->stageProgressService = $stageProgressService;
        $this->user = Auth::user();
        $this->middleware('admin',
            ['only'=>['create','preview','store','destroy', 'addTask', 'update', 'manageQuestionnaires']]
        );
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
        if($currentStage < 5){
            $taskID = 0;
        }else if($currentStage < 17){
            $taskID = 1;
        }
        
        else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('questionnaire_pretask',['task'=>$task]);
    }

    public function postQuerySegmentQuestionnaire(Request $req){
//        dd($req->all());
        $questionnaire = new QuestionnaireHelpAndBarrier;
        $stage = $this->stageProgressService->getCurrentStage();
        $stage->getResult();
        $stage_id = $stage->getResult()->id;
        $questionnaire->user_id = $req->input('user_id');
        $questionnaire->prompt_number = $req->input('query_id');
        $questionnaire->stage_id = $stage_id;
//        $questionnaire->query_segment_id = $req->input('query_segment_id');

        $rules = array(
            'user_id'=>'required',
            'prompt_number'=>'required',
            'barriers'=>'required',
            'barriers_order'=>'required',
            'help'=>'required',
            'help_order'=>'required',
        );


//        $questionnaire->useful = $req->input('useful');
        $questionnaire->barriers = serialize($req->input('barriers'));
        $questionnaire->barriers_order = serialize($req->input('barriers_order'));
        $questionnaire->help = serialize($req->input('help'));
        $questionnaire->help_order = serialize($req->input('help_order'));

        $inputs = array(
            'user_id'=>$questionnaire->user_id,
            'prompt_number'=>$questionnaire->prompt_number,
            'barriers'=>$questionnaire->barriers,
            'barriers_order'=>$questionnaire->barriers_order,
            'help'=>$questionnaire->help,
            'help_order'=>$questionnaire->help_order,
        );
        $validator = Validator::make($inputs,$rules);
        if($validator->fails()){
            return response()->json(['success'=>false]);
        }else if($questionnaire->help=='N;' or $questionnaire->barriers=='N;'){
            return response()->json(['success'=>false]);
        }

        $questionnaire->save();
        return response()->json(['success'=>true]);
    }

    public function postPretask(Request $req){

        $user = Auth::user();
        $this->validate($req, [
            'help' => 'required',
            'task_familiarity' => 'required',
            'task_effort' => 'required',
            'goal_specific' => 'required',
            'task_pre_difficulty' => 'required',
            'narrow_information' => 'required',
            'task_newinformation' => 'required',
            'task_unspecified' => 'required',
            'task_detail' => 'required',
            'task_knowspecific' => 'required',
            'queries_start' => 'required',
            'know_usefulinfo' => 'required',
            'useful_notobtain' => 'required',
        ]);


        $pretask = new QuestionnairePretask;
        $pretask->user_id = $user->id;
        $pretask->stage_id = Session::get('stage_id');
        $pretask->help = serialize($req->input("help"));
        $pretask->task_familiarity = $req->input("task_familiarity");
        $pretask->task_effort = $req->input("task_effort");
        $pretask->goal_specific = $req->input("goal_specific");
        $pretask->task_pre_difficulty = $req->input("task_pre_difficulty");
        $pretask->narrow_information = $req->input("narrow_information");
        $pretask->task_newinformation = $req->input("task_newinformation");
        $pretask->task_unspecified = $req->input("task_unspecified");
        $pretask->task_detail = $req->input("task_detail");
        $pretask->task_knowspecific = $req->input("task_knowspecific");
        $pretask->queries_start = $req->input("queries_start");
        $pretask->know_usefulinfo = $req->input("know_usefulinfo");
        $pretask->useful_notobtain = $req->input("useful_notobtain");

        $pretask->save();
        return app()->make('App\Http\Controllers\StageProgressController')->callAction('moveToNextStage',['request'=>$req]);
    }

    public function postPosttask(Request $req){
        $user = Auth::user();
        $this->validate($req, [
            'future_help'=>'required',
            'task_difficult'=>'required',
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
        $posttask = new QuestionnairePosttask;
        $posttask->user_id = $user->id;
        $posttask->stage_id = Session::get('stage_id');
        $posttask->future_help = serialize($req->input("future_help"));
        $posttask->task_difficult = $req->input("task_difficult");
        $posttask->satisfaction = $req->input("satisfaction");
        $posttask->system_helpfulness = $req->input("system_helpfulness");
        $posttask->goal_success = $req->input("goal_success");
        $posttask->mental_demand = $req->input("mental_demand");
        $posttask->physical_demand = $req->input("physical_demand");
        $posttask->temporal_demand = $req->input("temporal_demand");
        $posttask->effort = $req->input("effort");
        $posttask->frustration = $req->input("frustration");
//        $req->merge(['user_id' => $user->id]);
//        $req->merge(['stage_id' => Session::get('stage_id')]);

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
        if($currentStage < 5){
            $taskID = 0;
        }else if($currentStage < 17){
            $taskID = 1;
        }
        else{
            $taskID = 2;
        }
        $task = Task::all()->where('id',$taskID)->first();
        return view('task_description',['task'=>$task]);
    }


    public function getTask(){
        $currentStage = $this->getCurrentStageId();
        $taskID = -1;
        if($currentStage < 5){
            $taskID = 0;
        }else if($currentStage < 17){
            $taskID = 1;
        }
        else{
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

    public function preview($id){
        $questionnaire=Questionnaire::findOrFail($id);
        $questions =  $questionnaire->data;
        return view('admin.preview_questionnaire', compact('questionnaire', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $questionnaire = $request;
        return view('admin.create_questionnaire', compact('questionnaire'));
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
            'data' => json_encode($arr),
        ]);
        $createQuestion = null;
        foreach ($arr as $key=>$question){
            //$questionValues = $question['values'];
            $questionValues=null;
            if(array_key_exists("values", $question)) {
                $questionValues = $question['values'];
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
            if ($questionValues != null){
                foreach ($questionValues as $k=>$value){
                    $answerValue= $createQuestion->values()->create($value);
                    if(array_key_exists("selected", $value)){
                        $answerValue->selected = true;
                        $answerValue->save();
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
        $questionnaire = Questionnaire::findOrFail($id);

        $questions =  $questionnaire->data;

        return view('admin.edit_questionnaire', compact('questionnaire', 'questions'));
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
        $arr = $request->input("questions");
        $questionnaire = Questionnaire::findOrFail($id);
        $questionnaire->title = $request->input('title');
        $questionnaire->data=json_encode($arr);
        $questionnaire->save();

        $oldQuestions = Question::where('questionnaire_id', $id)->get();
        foreach($oldQuestions as $question){
            if(Value::where('question_id', $question->id)->count()>0) {
                Value::where('question_id', $question->id)->delete();
            }
        }
        Question::where('questionnaire_id', $id)->delete();

        $createQuestion = null;
        foreach ($arr as $key=>$question){
            //$questionValues = $question['values'];
            $questionValues=null;
            if(array_key_exists("values", $question)) {
                $questionValues = $question['values'];
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
            if ($questionValues != null){
                foreach ($questionValues as $k=>$value){
                    $answerValue= $createQuestion->values()->create($value);
                    if(array_key_exists("selected", $value)){
                        $answerValue->selected = true;
                        $answerValue->save();
                    }
                }
            }
        }
        return Question::where('questionnaire_id',$questionnaire->id)->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $arr = Question::where('questionnaire_id', $id)->get();
        foreach($arr as $question){
            if(Value::where('question_id', $question->id)->count()>0) {
                Value::where('question_id', $question->id)->delete();
            }
        }
        Question::where('questionnaire_id', $id)->delete();
        Questionnaire::destroy($id);


        $questionnaires = Questionnaire::all();
        return back();

    }
}
