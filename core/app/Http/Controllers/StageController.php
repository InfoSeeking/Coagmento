<?php

namespace App\Http\Controllers;

use App\Models\Questionnaire;
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
        $stages = Stage::all();
        return view('admin.manage_stages')->with('stages', $stages);
    }

    public function stageOrder(Request $request){
        $array = Input::get('data');
        foreach($array as $order_id=>$stage_id){
            $stage = Stage::find($stage_id);
            $stage->weight = ($order_id);$stage->save();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questionnaires = Questionnaire::all();
        return view('admin.create_stage', compact('questionnaires'));
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
        foreach($widgets as $widget){
            $new = $stage->widgets()->create([
                'type' => $widget,
            ]);
            if($new->type === 'questionnaire' || $new->type === 'text'){//THIS WILL CHANGE LATER!
                $new->value = $values[$counter];
                $counter++;
            }
        }
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
}
