<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stage;
use App\Models\Widget;
use App\Models\Questionnaire;
use App\Models\Task;
use App\Models\Attribute;
use App\Models\TaskAttributeAssignment;
//use Auth;
use App\Utilities\Status;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\StageProgress;
use App\Services\StageProgressService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ParticipantController extends Controller
{

    public function __construct(StageProgressService $stageProgressService) {
        $this->stageProgressService = $stageProgressService;
        $this->user = Auth::user();
        /*$this->middleware('admin',
            ['only'=> null ]
        );*/
    }

    public function inactive(){
        return view('participant.inactive');
    }

    public static function start($id){
        //dd($id);
        $stage = Stage::where('id', $id)->first();

        $widgets = Widget::where('stage_id', $id)->get();
        $questionnaires = Questionnaire::all();
        $tasks = Task::all();
        $attributes = Attribute::all();
        $assignments = TaskAttributeAssignment::all();
        $nextStage = null;
         if($stage->weight + 1 != count(Stage::all())){
             $nextStage = Stage::where('weight', $stage->weight+1)->first();
         }

        $prevStage = null;
         if($stage->weight - 1 != -1) {
             $prevStage = Stage::where('weight', 0)->first();
         }

         # saving stage progress (quick fix)
         # the intended way to save progress was through the StageProgressController, but that is unfinished
         $stageProgress = new StageProgress;
         $stageProgress->user_id = Auth::user()->id;
         $stageProgress->project_id = 1;
         $stageProgress->stage_id = $id;
         $stageProgress->created_at_local = 0;
         $stageProgress->created_at_local_ms = 0;
         $stageProgress->save();

        return view('participant.study', compact('id', 'stage', 'widgets', 'tasks', 'questionnaires', 'attributes', 'assignments', 'nextStage', 'prevStage'));
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
