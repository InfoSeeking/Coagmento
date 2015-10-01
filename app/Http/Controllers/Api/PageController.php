<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Utilities\ApiResponse;

class PageController extends Controller
{
    public function __construct(PageService $pageService) {
        $this->pageService = $pageService;
    }
    /**
     * @api{post} /v1/pages Create
     * @apiDescription Creates a new page.
     * @apiPermission write
     * @apiGroup Page
     * @apiName CreatePage
     * @apiParam {Integer} project_id
     * @apiParam {String} url
     * @apiParam {String} title The contents of title in the page.
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $pageStatus = $this->pageService->create($req->all());
        return ApiResponse::fromStatus($pageStatus);
    }

    /**
     * @api{get} /v1/pages Get Multiple
     * @apiDescription Gets a list of pages.
     * If the project_id is specified, returns all pages in a project (not just owned by user).
     * If project_id is omitted, then returns all user owned pages.
     * @apiPermission read
     * @apiGroup Page
     * @apiName GetPages
     * @apiParam {Integer} [project_id]
     * @apiVersion 1.0.0
     */
    public function index(Request $req) {   
        return ApiResponse::fromStatus($this->pageService->getMultiple($req->all()));
    }

    /**
     * @api{get} /v1/pages/:id Get
     * @apiDescription Gets a single page.
     * @apiPermission read
     * @apiGroup Page
     * @apiName GetPage
     * @apiVersion 1.0.0
     */
    public function get($id) {
        return ApiResponse::fromStatus($this->pageService->get($id));
    }

    /**
     * @api{delete} /v1/pages/:id Delete
     * @apiDescription Deletes a single page.
     * @apiPermission write
     * @apiGroup Page
     * @apiName DeletePage
     * @apiVersion 1.0.0
     */
    public function delete($id) {
        $status = $this->pageService->delete($id);
        return ApiResponse::fromStatus($status);
    }

    private $pageService;
}
