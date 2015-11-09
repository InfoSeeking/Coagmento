<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\SnippetService;
use App\Utilities\ApiResponse;

class SnippetController extends Controller
{
    public function __construct(SnippetService $snippetService) {
        $this->snippetService = $snippetService;
    }

    /**
     * @api{post} /v1/bookmarks Create
     * @apiDescription Creates a new snippet.
     * @apiPermission write
     * @apiGroup Snippet
     * @apiName CreateSnippet
     * @apiParam {Integer} project_id
     * @apiParam {String} url
     * @apiParam {String} [title] The web page title.
     * @apiParam {String} text The snippet contents.
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $snippetStatus = $this->snippetService->create($req->all());
        return ApiResponse::fromStatus($snippetStatus);
    }

    /**
     * @api{delete} /v1/bookmarks/:id Delete
     * @apiDescription Deletes a snippet
     * @apiPermission write
     * @apiGroup Snippet
     * @apiName DeleteSnippet
     * @apiVersion 1.0.0
     */
    public function delete(Request $req, $snippet_id) {
        $snippetStatus = $this->snippetService->delete($snippet_id);
        return ApiResponse::fromStatus($snippetStatus);
    }

    /**
     * @api{get} /v1/bookmarks/:id Get
     * @apiDescription Returns a single snippet
     * @apiPermission read
     * @apiGroup Snippet
     * @apiName GetSnippet
     * @apiVersion 1.0.0
     */
    public function get(Request $req, $snippet_id) {
        $snippetStatus = $this->snippetService->get($snippet_id);
        return ApiResponse::fromStatus($snippetStatus);
    }

    /**
     * @api{put} /v1/bookmarks/:id Update
     * @apiPermission write
     * @apiGroup Snippet
     * @apiName UpdateSnippet
     * @apiParam {String} [url]
     * @apiParam {String} [text]
     * @apiVersion 1.0.0
     */
    public function update(Request $req, $snippet_id) {
        $args = array_merge($req->all, ['id' => $snippet_id]);
        $snippetStatus = $this->snippetService->update($args);
        return ApiResponse::fromStatus($snippetStatus);
    }

    /**
     * @api{get} /v1/bookmarks Get Multiple
     * @apiDescription Returns multiple snippets
     * @apiPermission read
     * @apiGroup Snippet
     * @apiName GetSnippets
     * @apiParam {integer} [project_id]
     * @apiVersion 1.0.0
     */
    public function index(Request $req) {
        $snippetStatus = $this->snippetService->getMultiple($req->all());
        return ApiResponse::fromStatus($snippetStatus);
    }

    private $snippetService;
}
