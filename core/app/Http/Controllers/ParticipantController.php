<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ParticipantController extends Controller
{

    public function inactive(){
        return view('participant.inactive');
    }

    public function start($id){
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

        return view('participant.study', compact('stage', 'widgets', 'tasks', 'questionnaires', 'attributes', 'assignments', 'nextStage', 'prevStage'));
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