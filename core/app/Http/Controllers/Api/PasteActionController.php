<?php

namespace App\Http\Controllers\Api;

use App\Models\PasteAction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

class PasteActionController extends Controller
{
    public function store(Request $req){
        $pasteaction = new PasteAction;
        $pasteaction->user_id = $req->user_id;
        $pasteaction->project_id = $req->project_id;
        $pasteaction->stage_id = $req->stage_id;
        $pasteaction->url = $req->url;
        $pasteaction->title = $req->title;
        $pasteaction->snippet = $req->snippet;
        $pasteaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $pasteaction->created_at_local_ms = $req->created_at_local_ms;
        $pasteaction->save();
    }
}
