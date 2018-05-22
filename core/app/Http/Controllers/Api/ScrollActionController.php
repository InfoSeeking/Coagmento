<?php

namespace App\Http\Controllers\Api;

use App\Models\ScrollAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;

class ScrollActionController extends Controller
{
    public function store(Request $req){
        $scrollaction = new ScrollAction;
        $scrollaction->user_id = $req->user_id;
        $scrollaction->project_id = $req->project_id;
        $scrollaction->stage_id = $req->stage_id;
        $scrollaction->screen_x = $req->screenX;
        $scrollaction->screen_y = $req->screenY;
        $scrollaction->scroll_x = $req->scrollX;
        $scrollaction->scroll_y = $req->scrollY;
        $scrollaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $scrollaction->created_at_local_ms = $req->created_at_local_ms;
        $scrollaction->save();
    }

    public function storeMany(Request $req){
        $mouse_actions = $req['scrolls'];
        $user_id = Auth::user()->id;
        $project_id = 0;
        $stage_id = 0;
        foreach($mouse_actions as $time=>$o){
//            TODO: Data corrections
            foreach($o as $index=>$obj){
                $mouseaction = new ScrollAction;
                $mouseaction->user_id = $user_id;
                $mouseaction->project_id = $project_id;
                $mouseaction->stage_id = $stage_id;
                $mouseaction->screen_x = $obj['screenX'];
                $mouseaction->screen_y = $obj['screenY'];
                $mouseaction->scroll_x = $obj['scrollX'];
                $mouseaction->scroll_y = $obj['scrollY'];
                $mouseaction->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
                $mouseaction->created_at_local_ms = $time;
                $mouseaction->save();
            }


        }
//        Keystroke::insert($data_array);
//        $keystroke->save();
    }
}
