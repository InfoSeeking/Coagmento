<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\StageProgress;
use App\Services\ProjectService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Services\StageProgressService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class StageProgressController extends Controller
{

    public function __construct(StageProgressService $stageProgressService,ProjectService $projectService) {
        $this->stageProgressService = $stageProgressService;
        $this->projectService = $projectService;
    }

    public function directToStage(){
        $stage = $this->stageProgressService->getCurrentStage();
        $stage->getResult();
        $stage_id = $stage->getResult()->id;
        Session::put('stage_id',$stage_id);

        if($stage_id < 15){
            Session::put('project_id',$this->projectService->getMyFirstProject()->id);
        }else if($stage_id <= 19){
            Session::put('project_id',$this->projectService->getMySecondProject()->id);
        }else{
            Session::put('project_id',$this->projectService->getMyThirdProject()->id);
        }

//        dd($stage->getResult());
//        dd($stage->getResult()->page);
        return redirect($stage->getResult()->page);
    }

    public function moveToNextStage(Request $req){
        $this->stageProgressService->moveToNextStage($req);
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
