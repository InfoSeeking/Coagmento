<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
use App\Models\Task;
use App\Models\Widget;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Stage;
use Auth;

class StageController extends Controller
{
    public function __construct()
    {
        $this->user = Auth::user();
        $this->middleware('admin');
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
        $stage=Stage::create([
            'title' => $request->input('title'),
            'page' => 'temp',
        ]);
        $widgets = $request->input('widget');
        $values = $request->input('value');
        $counter = 0;
        $order = array();
        foreach($widgets as $widget){
            if($widget === 'confirm'){
            $new = $stage->widgets()->create([
                'type' => $widget,
                'value' => $values[$counter],
            ]);
            $counter++;}
            else{
                $new = $stage->widgets()->create([
                    'type' => $widget,
                ]);
            }
            $order[] = $new->id;
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
        return view('admin.edit_stage', compact('stage', 'widgets'));
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
        Widget::where('stage_id', $id)->delete();
        Stage::destroy($id);
        return back();
    }

    public function createWidget(Request $request){
        Widget::create([
            'type' => $request->input('type'),
        ]);
        return back();
    }

    public function preview($id){
        $stage = Stage::findOrFail($id);
        $widgets = Widget::where('stage_id', $id)->get();
        $questionnaires = Questionnaire::all();
        $tasks = Task::all();

        return view('admin.preview_stage', compact('stage', 'widgets', 'tasks', 'questionnaires'));
    }
}
