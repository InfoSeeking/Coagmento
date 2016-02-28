<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\QueryService;
use App\Utilities\Status;
use App\Utilities\ApiResponse;

class QueryController extends Controller
{
    public function __construct(QueryService $queryService) {
        $this->queryService = $queryService;
    }

    /**
     * @api{get} /v1/queries GetMultiple
     * @apiDescription Gets many queries.
     * If the project_id is specified, returns all queries in a project (not just owned by user).
     * If project_id is omitted, then returns all user owned queries.
     * @apiPermission read
     * @apiGroup Query
     * @apiName GetQueries
     * @apiParam {Integer} [project_id] Filters by project if included.
     * @apiVersion 1.0.0
     */
    public function index(Request $req) {
        $queryStatus = $this->queryService->getMultiple($req->all());
        return ApiResponse::fromStatus($queryStatus);
    }

    /**
     * @api{post} /v1/queries Create
     * @apiDescription Creates a new query.
     * @apiPermission write
     * @apiGroup Query
     * @apiName CreateQuery
     * @apiParam {Integer} project_id
     * @apiParam {String} text The search engine query text.
     * @apiParam {String} search_engine The name of the search engine (e.g. google, bing).
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $queryStatus = $this->queryService->create($req->all());
        return ApiResponse::fromStatus($queryStatus);
    }

    /**
     * @api{delete} /v1/queries/:id Delete
     * @apiDescription Deletes a single query.
     * @apiPermission write
     * @apiGroup Query
     * @apiName DeleteQuery
     * @apiVersion 1.0.0
     */
    public function delete($id) {
        $queryStatus = $this->queryService->delete($id);
        return ApiResponse::fromStatus($queryStatus);
    }
}
