<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use App\Services\ProjectService;
use App\Utilities\ApiResponse;

class BookmarkController extends Controller
{

    public function create(Request $req) {
        $bookmarkStatus = BookmarkService::create($req);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    public function index(Request $req) {   
        return ApiResponse::fromStatus(BookmarkService::getMultiple($req));
    }

    public function get($id) {
        return ApiResponse::fromStatus(BookmarkService::get($id));
    }

    public function delete($id) {
        $status = BookmarkService::delete($id);
        return ApiResponse::fromStatus($status);
    }

    public function update(Request $req, $id) {
        $bookmarkStatus = BookmarkService::update($req, $id);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    public function move(Request $req, $id) {
        $bookmarkStatus = BookmarkService::move($req, $id);
        return ApiResponse::fromStatus($bookmarkStatus);
    }
}
