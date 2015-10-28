<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\BookmarkService;

class SidebarController extends Controller
{
	public function __construct(BookmarkService $bookmarkService) {
		$this->bookmarkService = $bookmarkService;
	}
	public function getProjectSelection() {
		return "Select a project";
	}
    public function getFeed(Request $req, $projectId) {
    	$user = Auth::user();
    	$bookmarks = $this->bookmarkService->getMultiple([
    		'project_id' => $projectId
    		]);
        return view('sidebar', ['bookmarks' => $bookmarks]);
    }
}
