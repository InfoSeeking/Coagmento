<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Stage;
use App\Models\Widget;
use App\Models\Questionnaire;
use App\Models\Task;
use App\Models\Attribute;
use App\Models\TaskAttributeAssignment;
use Auth;
use App\Utilities\Status;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\StageProgressService;

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
