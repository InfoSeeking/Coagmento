<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;

class SidebarController extends Controller
{
    public function getHome() {
    	$user = Auth::user();
    	$bookmarks = BookmarkService::getAllForUser($user);
        return view('sidebar', ['bookmarks' => $bookmarks]);
    }
}
