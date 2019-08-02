<?php

namespace App\Http\Controllers\Api;

use App\Models\MouseAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MouseActionController extends Controller
{
    public function store(Request $req){
        $mouseaction = new MouseAction;
        $mouseaction->user_id = $req->user_id;
        $mouseaction->project_id = $req->project_id;
        $mouseaction->stage_id = $req->stage_id;
        $mouseaction->client_x = $req->clientX;
        $mouseaction->client_y = $req->clientY;
        $mouseaction->page_x = $req->pageX;
        $mouseaction->page_y = $req->pageY;
        $mouseaction->screen_x = $req->screenX;
        $mouseaction->screen_y = $req->screenY;
        $mouseaction->scroll_x = $req->scrollX;
        $mouseaction->scroll_y = $req->scrollY;
        $mouseaction->type = $req->type;
        $mouseaction->layer_x = $req->layerX;
        $mouseaction->layer_y = $req->layerY;
        $mouseaction->movement_x = $req->movementX;
        $mouseaction->movement_y = $req->movementY;
        $mouseaction->offset_x = $req->offsetX;
        $mouseaction->offset_y = $req->offsetY;
        $mouseaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $mouseaction->created_at_local_ms = $req->created_at_local_ms;
        $mouseaction->save();
    }


    public function storeMany(Request $req){
        $mouse_actions = $req->mouse_actions;
        $user_id = Auth::user()->id;
        $project_id = 1;
        $stage_id = 0;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
        foreach($mouse_actions as $time=>$o){
//            TODO: Data corrections
            $obj = $o[0];
            $mouseaction = new MouseAction;
            $mouseaction->user_id = $user_id;
            $mouseaction->project_id = $project_id;
            $mouseaction->stage_id = $stage_id;
            $mouseaction->client_x = $obj['clientX'];
            $mouseaction->client_y = $obj['clientY'];
            $mouseaction->page_x = $obj['pageX'];
            $mouseaction->page_y = $obj['pageY'];
            $mouseaction->screen_x = $obj['screenX'];
            $mouseaction->screen_y = $obj['screenY'];
            $mouseaction->scroll_x = $obj['scrollX'];
            $mouseaction->scroll_y = $obj['scrollY'];
            $mouseaction->layer_x = $obj['layerX'];
            $mouseaction->layer_y = $obj['layerY'];
            $mouseaction->movement_x = $obj['movementX'];
            $mouseaction->movement_y = $obj['movementY'];
            $mouseaction->offset_x = $obj['offsetX'];
            $mouseaction->offset_y = $obj['offsetY'];
            $mouseaction->type = $obj['type'];
            $mouseaction->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
            $mouseaction->created_at_local_ms = $time;
            $mouseaction->save();
        }
//        Keystroke::insert($data_array);
//        $keystroke->save();
    }
}
