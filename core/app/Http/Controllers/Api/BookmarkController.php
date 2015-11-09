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
     * @api{post} /v1/bookmarks Create
     * @apiDescription Creates a new bookmark.
     * @apiPermission write
     * @apiGroup Bookmark
     * @apiName CreateBookmark
     * @apiParam {Integer} project_id
     * @apiParam {String} url
     * @apiParam {String} [notes] Related user written notes about this bookmark.
     * @apiParam {String} title The contents of title in the page.
     * @apiParam {String[]} [tags] A list of initial tags.
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $bookmarkStatus = $this->bookmarkService->create($req->all());
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    /**
     * @api{get} /v1/bookmarks Get Multiple
     * @apiDescription Gets a list of bookmarks.
     * If the project_id is specified, returns all bookmarks in a project (not just owned by user).
     * If project_id is omitted, then returns all user owned bookmarks.
     * @apiPermission read
     * @apiGroup Bookmark
     * @apiName GetBookmarks
     * @apiParam {Integer} [project_id]
     * @apiVersion 1.0.0
     */
    public function index(Request $req) {
        return ApiResponse::fromStatus($this->bookmarkService->getMultiple($req->all()));
    }

    /**
     * @api{get} /v1/bookmarks/:id Get
     * @apiDescription Gets a single bookmark.
     * @apiPermission read
     * @apiGroup Bookmark
     * @apiName GetBookmark
     * @apiVersion 1.0.0
     */
    public function get($id) {
        return ApiResponse::fromStatus($this->bookmarkService->get($id));
    }

    /**
     * @api{delete} /v1/bookmarks/:id Delete
     * @apiDescription Deletes a single bookmark.
     * @apiPermission write
     * @apiGroup Bookmark
     * @apiName DeleteBookmark
     * @apiVersion 1.0.0
     */
    public function delete($id) {
        $status = $this->bookmarkService->delete($id);
        return ApiResponse::fromStatus($status);
    }

    /**
     * @api{put} /v1/bookmarks/:id Update
     * @apiDescription Updates a bookmark.
     * @apiPermission write
     * @apiParam {String} [url]
     * @apiParam {String} [title] The contents of title in the page.
     * @apiParam {String[]} [tags] A list of tags.
     * @apiGroup Bookmark
     * @apiName UpdateBookmark
     * @apiVersion 1.0.0
     */
    public function update(Request $req, $id) {
        $args = array_merge($req->all(), ['id' => $id]);
        $bookmarkStatus = $this->bookmarkService->update($args);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    /**
     * @api{put} /v1/bookmarks/:id/move Move to Project
     * @apiDescription Moves the bookmark to another project.
     * Note: the user must have write permission on both 'from' and 'to' projects.
     * @apiPermission write
     * @apiParam {Integer} project_id The destination project.
     * @apiName MoveBookmark
     * @apiGroup Bookmark
     * @apiVersion 1.0.0
     */
    public function move(Request $req, $id) {
        $args = array_merge($req->all(), ['id' => $id]);
        $bookmarkStatus = $this->bookmarkService->move($args);
        return ApiResponse::fromStatus($bookmarkStatus);
    }

    private $bookmarkService;
}
