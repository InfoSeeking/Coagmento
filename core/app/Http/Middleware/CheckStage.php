<?php

namespace App\Http\Middleware;

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

        $stage_page = $request->path();
        $stage_id = Session::get('stage_id');
//        echo $stage_page;
//        echo "BLAH".$stage_id."BLEH";
        $stage = Stage::where('page', $stage_page)->where('id', $stage_id)->get();
        if (count($stage) > 0 or $stage_page=='stages') {
            return $next($request);
        }else{
            return redirect('stages');
        }


//        $value = Session::get('key');
//        if ($request->stage <= 200) {
//            return redirect('stages');
//        }

//        return $next($request);
    }
}
