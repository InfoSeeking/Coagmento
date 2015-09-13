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
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
        $user = Auth::user();
        return ApiResponse::fromResult(BookmarkService::getAllForUser($user));
    }

    public function helper() {
        return view('ajaxTest');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $bookmarkAndStatus = BookmarkService::insert($user, $request);
        return ApiResponse::fromStatus($bookmarkAndStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function get($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $status = BookmarkService::delete($user, $id);
        return ApiResponse::fromStatus($status);
    }

}
