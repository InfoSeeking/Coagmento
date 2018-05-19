<?php

namespace App\Http\Controllers\Api;

use App\Models\ScrollAction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

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
}
