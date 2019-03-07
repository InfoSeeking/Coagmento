<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\StageProgress;
use App\Services\ProjectService;
use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Http\StageProgressService;
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
    dd("directtostage function hit in stageprogresscontroller");
        $stage = $this->stageProgressService->getCurrentStage();
        $stage->getResult();
        $stage_id = $stage->getResult()->id;
        Session::put('stage_id',$stage_id);

        return redirect($stage->getResult()->page);
    }
    public function getCurrentProject(){
        dd("get current project funcion in stageprogresscontroller.php");
        $stage = $this->stageProgressService->getCurrentStage();
        $stage->getResult();
        $stage_id = $stage->getResult()->id;

        Session::put('stage_id',$stage_id);
        Session::put('project_id',$project_id);

        return response()->json([
            'project_id'=>$project_id
        ]);
    }

    public function moveToNextStage(Request $req){
        dd("movetonextstage function in stageprogresscontroller.php");
        $this->stageProgressService->moveToNextStage($req);
        return redirect('/stages');
    }

    public function getCurrentStageUser(Request $req){
        dd("HELP in getCurrentStageUser");
        $currentStage = $this->stageProgressService->getCurrentStage($req)->getResult();
        $currentStageProgress = $this->stageProgressService->getCurrentStageProgress($req)->getResult();
        return response()->json([
            'stage_id'=>$currentStage->id,
            'timed'=>$currentStage->timed,
            'time_limit'=>$currentStage->time_limit,
            'time_start'=>$currentStageProgress->created_at,
        ]);
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
