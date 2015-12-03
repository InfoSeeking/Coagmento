<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\DocService;
use App\Utilities\Status;
use App\Utilities\ApiResponse;

class DocController extends Controller
{
    public function __construct(DocService $docService) {
        $this->docService = $docService;
    }

    /**
     * @api{post} /v1/docs Create
     * @apiDescription Creates a new document.
     * @apiPermission write
     * @apiGroup Document
     * @apiName CreateDocument
     * @apiParam {Integer} project_id
     * @apiParam {String} title
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $docStatus = $this->docService->create($req->all());
        return ApiResponse::fromStatus($docStatus);
    }

    /* @api{get} /v1/docs Get Multiple
     * @apiDescription Gets a list of docs.
     * If the project_id is specified, returns all docs in a project (not just owned by user).
     * If project_id is omitted, then returns all user owned docs.
     * @apiPermission read
     * @apiGroup Doc
     * @apiName GetDocs
     * @apiParam {Integer} [project_id]
     * @apiVersion 1.0.0
     */
    public function getMultiple(Request $req) {
        return ApiResponse::fromStatus($this->docService->getMultiple($req->all()));
    }
}
