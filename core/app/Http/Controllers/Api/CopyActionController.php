<?php

namespace App\Http\Controllers\Api;

use App\Models\CopyAction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

class CopyActionController extends Controller
{
    public function store(Request $req){
        $copyaction = new CopyAction;
        $copyaction->user_id = $req->user_id;
        $copyaction->project_id = $req->project_id;
        $copyaction->stage_id = $req->stage_id;
        $copyaction->url = $req->url;
        $copyaction->title = $req->title;
        $copyaction->snippet = $req->snippet;
        $copyaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $copyaction->created_at_local_ms = $req->created_at_local_ms;
        $copyaction->save();
    }
}
