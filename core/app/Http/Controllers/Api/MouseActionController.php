<?php

namespace App\Http\Controllers\Api;

use App\Models\MouseAction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

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
        $mouseaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $mouseaction->created_at_local_ms = $req->created_at_local_ms;
        $mouseaction->save();
    }
}
