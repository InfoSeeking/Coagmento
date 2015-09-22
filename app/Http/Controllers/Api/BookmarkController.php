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

    function create(Request $req) {
        $bookmarkStatus = ProjectService::addBookmark($req);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    /**
     * Return a list of all bookmarks saved by the user.
     *
     * @return Response
     */
    public function index()
    {   
        return ApiResponse::fromResult(BookmarkService::getForUser());
    }

    /**
     * Delete the specified bookmark.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $status = BookmarkService::delete($id);
        return ApiResponse::fromStatus($status);
    }

}
