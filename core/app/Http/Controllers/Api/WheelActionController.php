<?php

namespace App\Http\Controllers\Api;

use App\Models\WheelAction;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WheelActionController extends Controller
{
  public function store(Request $req){
      $wheelaction = new WheelAction;
      $wheelaction->user_id = $req->user_id;
      $wheelaction->project_id = $req->project_id;
      $wheelaction->stage_id = $req->stage_id;
      $wheelaction->delta_mode = $req->deltaMode;
      $wheelaction->delta_x = $req->deltaX;
      $wheelaction->delta_y = $req->deltaY;
      $wheelaction->delta_z = $req->deltaZ;
      $wheelaction->screen_x = $req->screenX;
      $wheelaction->screen_y = $req->screenY;
      $wheelaction->scroll_x = $req->scrollX;
      $wheelaction->scroll_y = $req->scrollY;
      $wheelaction->layer_x = $req->layerX;
      $wheelaction->layer_y = $req->layerY;
      $wheelaction->movement_x = $req->movementX;
      $wheelaction->movement_y = $req->movementY;
      $wheelaction->offset_x = $req->offsetX;
      $wheelaction->offset_y = $req->offsetY;
      $wheelaction->screen_x = $req->screenX;
      $wheelaction->screen_y = $req->screenY;
      $wheelaction->scroll_x = $req->scrollX;
      $wheelaction->scroll_y = $req->scrollY;
      $wheelaction->created_at_local = Carbon::createFromTimestamp($req->created_at_local)->format('Y-m-d H:i:s');
      $wheelaction->created_at_local_ms = $req->created_at_local_ms;
      $wheelaction->save();
  }

  public function storeMany(Request $req){
      $wheel_actions = $req['wheels'];
      $user_id = Auth::user()->id;
      $project_id = 1;
      $stage_id = 1;
      if(Session::has('project_id')){
          $project_id = Session::get('project_id');
      }
      if(Session::has('stage_id')){
          $stage_id = Session::get('stage_id');
      }
      foreach($wheel_actions as $time=>$o){
//            TODO: Data corrections
          foreach($o as $index=>$obj){
              $wheelaction = new WheelAction;
              $wheelaction->user_id = $user_id;
              $wheelaction->project_id = $project_id;
              $wheelaction->stage_id = $stage_id;
              $wheelaction->delta_mode = $obj['deltaMode'];
              $wheelaction->delta_x = $obj['deltaX'];
              $wheelaction->delta_y = $obj['deltaY'];
              $wheelaction->delta_z = $obj['deltaZ'];
              $wheelaction->client_x = $obj['clientX'];
              $wheelaction->client_y = $obj['clientY'];
              $wheelaction->page_x = $obj['pageX'];
              $wheelaction->page_y = $obj['pageY'];
              $wheelaction->screen_x = $obj['screenX'];
              $wheelaction->screen_y = $obj['screenY'];
              $wheelaction->scroll_x = $obj['scrollX'];
              $wheelaction->scroll_y = $obj['scrollY'];
              $wheelaction->layer_x = $obj['layerX'];
              $wheelaction->layer_y = $obj['layerY'];
              $wheelaction->movement_x = $obj['movementX'];
              $wheelaction->movement_y = $obj['movementY'];
              $wheelaction->offset_x = $obj['offsetX'];
              $wheelaction->offset_y = $obj['offsetY'];
              $wheelaction->created_at_local = Carbon::createFromTimestamp($time)->format('Y-m-d H:i:s');
              $wheelaction->created_at_local_ms = $time;
              $wheelaction->save();
          }
      }
   }
}
