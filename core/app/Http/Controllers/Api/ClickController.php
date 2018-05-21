<?php

namespace App\Http\Controllers\Api;

use App\Models\Click;
use App\Models\Snippet;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

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
}