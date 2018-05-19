<?php

namespace App\Http\Controllers\Api;

use App\Models\Keystroke;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

class KeystrokeController extends Controller
{
    public function store(Request $req){
        $keystroke = new Keystroke;
        $keystroke->user_id = $req->user_id;
        $keystroke->project_id = $req->project_id;
        $keystroke->stage_id = $req->stage_id;
        $keystroke->key_code = $req->key_code;
        $keystroke->modifiers = $req->modifiers;
        $keystroke->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
        $keystroke->created_at_local_ms = $req->created_at_local_ms;
        $keystroke->save();
    }
}
