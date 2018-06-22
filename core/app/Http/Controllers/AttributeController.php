<?php

namespace App\Http\Controllers;

use App\Models\TaskAttributeAssignment;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Attribute;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    public function _construct(){
        $this->middleware('admin');
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
   /* public function create()
    {
        //
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'type' => 'required',
        ]);
        if($request->input('type')==='select'){
            $this->validate($request,[
                'option_name' => 'required',
            ]);
        }
        $array = $request->all();
        $attribute = Attribute::create($array);
        $attribute->option_name=serialize($request->input('option_name'));
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
        $attribute = Attribute::where('id', $id)->first();
        $assignments = TaskAttributeAssignment::where('attribute_id', $id)->first();
        return view('admin.edit_attribute', compact('attribute', 'assignments'));
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
        /*$attribute = Attribute::where('id', $id)->first();
        $attribute->value = $request->input('value');
        $attribute->save();*/
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        $array = $request->all();
        $attribute = Attribute::find($id);
        if($attribute->name !== $request->input('name')){
            $attribute->name = $request->input('name');
        }
        if($attribute->type === "select") {
            /*$this->validate($request->Attribute, [
                'option_name' => 'required',
            ]);*/
            $attribute->option_name = unserialize(serialize($request->input('option_name')));
        }
        $attribute->save();
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
        Attribute::destroy($id);
        TaskAttributeAssignment::where('attribute_id', $id)->delete();
        return back();
        /*return view('/admin/task_settings', compact('attributes', 'tasks') );*/
    }
}
