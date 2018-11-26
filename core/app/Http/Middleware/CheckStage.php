<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ParticipantController;
use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\Stage;

class CheckStage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    //dd($request);

        $stage_page = $request->path();
        $stage_id = Session::get('stage_id');
        //dd($request->path());
        //$stage = Stage::where('page', $stage_page)->where('id', $stage_id)->get();
        //if (count($stage) > 0 or $stage_page=='/stages' or $stage_page=='/stages/next' or $stage_page=='stages' or $stage_page=='stages/next') {
            //return $next($request);
        //}else{
            //return redirect('stages');
        //}
        return ParticipantController::start($stage_id);
    }
}
