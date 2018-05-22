<?php

namespace App\Http\Controllers\Api;

use App\Models\Click;
use App\Models\Snippet;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;

class ClickController extends Controller
{
    public function store(Request $req){
        $click = new Click;
        $click->user_id = $req->user_id;
        $click->project_id = $req->project_id;
        $click->stage_id = $req->stage_id;
        $click->url = $req->url;
        $click->title = $req->title;
        $click->client_x = $req->clientX;
        $click->client_y = $req->clientY;
        $click->page_x = $req->pageX;
        $click->page_y = $req->pageY;
        $click->screen_x = $req->screenX;
        $click->screen_y = $req->screenY;
        $click->scroll_x = $req->scrollX;
        $click->scroll_y = $req->scrollY;
        $click->type = $req->type;
        $click->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $click->created_at_local_ms = $req->created_at_local_ms;
        $click->save();
    }


    public function storeMany(Request $req){
        $copies = $req['clicks'];
        $user_id = Auth::user()->id;
        $project_id = 0;
        $stage_id = 0;
//        snippet,title,url
        foreach($copies as $time=>$obj){
//            TODO: Data corrections
            $mouseaction = new Click;
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
            $mouseaction->type = $obj['type'];
            $mouseaction->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
            $mouseaction->created_at_local_ms = $time;
            $mouseaction->save();
        }
//        Keystroke::insert($data_array);
//        $keystroke->save();
    }
}
