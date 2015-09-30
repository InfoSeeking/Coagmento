<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use App\Utilities\ApiResponse;

class BookmarkController extends Controller
{
    public function __construct(BookmarkService $bookmarkService) {
        $this->bookmarkService = $bookmarkService;
    }
    /**
     * @api{post} /v1/bookmarks
     * @apiDescription Creates a new bookmark.
     * @apiGroup Bookmark
     * @apiName CreateBookmark
     */
    public function create(Request $req) {
        $bookmarkStatus = $this->bookmarkService->create($req->all());
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    /**
     * @api{get} /v1/bookmarks
     * @apiDescription Gets a list of bookmarks the user has some access to.
     * @apiGroup Bookmark
     * @apiName GetBookmarks
     */
    public function index(Request $req) {   
        return ApiResponse::fromStatus($this->bookmarkService->getMultiple($req->all()));
    }

    /**
     * @api{get} /v1/bookmarks/:id
     * @apiDescription Gets a single bookmark.
     * @apiGroup Bookmark
     * @apiName GetBookmark
     */
    public function get($id) {
        return ApiResponse::fromStatus($this->bookmarkService->get($id));
    }

    /**
     * @api{delete} /v1/bookmarks/:id
     * @apiDescription Deletes a single bookmark if the user has write permission.
     * @apiGroup Bookmark
     * @apiName DeleteBookmark
     */
    public function delete($id) {
        $status = $this->bookmarkService->delete($id);
        return ApiResponse::fromStatus($status);
    }

    /**
     * @api{put} /v1/bookmarks/:id
     * @apiDescription Updates a bookmark if the user has write permission.
     * @apiGroup Bookmark
     * @apiName UpdateBookmark
     */
    public function update(Request $req, $id) {
        $args = array_merge($req->all(), ['id' => $id]);
        $bookmarkStatus = $this->bookmarkService->update($args);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    /**
     * @api{put} /v1/bookmarks/:id/move
     * @apiDescription Moves the bookmark to another project if the user 
     * has write permission on both projects.
     * @apiGroup Bookmark
     * @apiName MoveBookmark
     */
    public function move(Request $req, $id) {
        $args = array_merge($req->all(), ['id' => $id]);
        $bookmarkStatus = $this->bookmarkService->move($args);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    private $bookmarkService;
}
