<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Task;
use App\Models\Widget;
use Illuminate\Http\Request;
use App\Models\Attribute;
use App\Models\TaskAttributeAssignment;
use App\Http\Response;
use App\Http\Controllers\StageProgressService;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use Auth;

class StageController extends Controller
{
    public function __construct() {

        $this->user = Auth::user();
        $this->middleware('admin',
            ['except'=>[]]
        );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stages = Stage::orderBy('weight')->get();
        return view('admin.manage_stages')->with('stages', $stages);
    }

    public function stageOrder(Request $request){
        //dd();

        $array = $request->weights;

        //$weight = 1;
        foreach($array as $weight=>$stage_id){
            $stage = Stage::find($stage_id);
            $stage->weight = ($weight);
            $weight++;
            $stage->save();
        }
        return response()->json($array);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questionnaires = Questionnaire::all();
        $tasks = Task::all();
        return view('admin.create_stage', compact('questionnaires', 'tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validation: Confirm Validation Here
        //TODO: two toggle inputs
        $toggle = $request->input('toggle_extension') === 'on';
        $this->validate($request, [
            'title' => 'required',
            'value' => 'required',
            'widget' => 'required',
        ]);
        $toggle = $request->input('toggle_extension') === 'on';

        $stage=Stage::create([
            'title' => $request->input('title'),
            'page' => 'temp',
            'toggle_extension' => $toggle,
        ]);
        $widgets = $request->input('widget');
        $values = $request->input('value');
        $counter = 0;
        $order = array();
        if($widgets !== null){
            foreach($widgets as $widget){
                $id = null;
                if($widget === 'questionnaire' || $widget === 'template') {

                    if ($widget === 'questionnaire'){
                        $id = Questionnaire::where('title', $values[$counter]);
                    }
                    else{
                        $id = Task::where('description', $values[$counter]);
                    }

                }
                $new = $stage->widgets()->create([
                    'type' => $widget,
                    'value' => $values[$counter],
                    'other_id' => $id,
                    'weight' => $counter,
                ]);
                $counter++;
                $order[] = $new->id;
            }
        }
        $stage->order = serialize($order);
        $stage->save();
        //
        return back();
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
        $stage = Stage::findOrFail($id);
        $widgets = Widget::where('stage_id', $id)->get();
        $questionnaires = Questionnaire::all();
        $tasks = Task::all();
        $attributes = Attribute::all();
        $assignments = TaskAttributeAssignment::all();
        return view('admin.edit_stage', compact('stage', 'widgets','tasks', 'questionnaires', 'attributes', 'assignments'));
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
        $this->validate($request, [
            'title' => 'required',
            'widget' => 'required',
        ]);
        //add validation here
        $stage=Stage::findOrFail($id);
        $stage->title = $request->input('title');
        $toggle = $request->input('toggle_extension') === 'on';
        $stage->toggle_extension = $toggle;
        $widgets = $request->input('widget');
        //dd($request->all());
        $values = $request->input('value');
        $widgetIDs = $request->input('id');
        $counter = 0;
        $order = array();
        $stage->widgets()->delete();
        foreach($widgets as $widget){
            $id = null;
            if($widget === 'questionnaire' || $widget === 'template') {

                if ($widget === 'questionnaire'){
                    $id = Questionnaire::where('title', $values[$counter])->first()->id;
                }
                else{
                    $id = Task::where('description', $values[$counter])->first()->id;
                }
            }

            $current = $stage->widgets()->create([
                'type' => $widget,
                'value' => $values[$counter],
                'other_id' => $id,
                'weight' => $counter,
            ])->id;

            $counter++;
            $order[] = $current;
        }
        $stage->order = serialize($order);
        $stage->save();
        //
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Widget::where('stage_id', $id)->delete();
        Stage::destroy($id);
        return back();
    }

    public function preview($id){
        $stage = Stage::findOrFail($id);
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
            $prevStage = Stage::where('weight', $stage->weight - 1)->first();
        }

        return view('admin.preview_stage', compact('stage', 'widgets', 'tasks', 'questionnaires', 'attributes', 'assignments', 'nextStage', 'prevStage'));
    }
}
