<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Action;
use App\Models\Page;
use App\Models\Query;
use App\Services\PageService;
use App\Utilities\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
     * @apiParam {String} [title] The contents of title in the page.
     * @apiParam {String} [if_query=both] Used to determine the behavior when the url
     * represents a search engine query page (e.g. https://www.google.com/search?q=test)
     * This should be set to one of the following: 'page_only', 'query_only', or 'both'
     * @apiVersion 1.0.0
     */
    public function create(Request $req) {
        $pageStatus = $this->pageService->create($req->all());
        return ApiResponse::fromStatus($pageStatus);
    }


    private function findNextPromptNumber($userID,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();
        $query = "SELECT IFNULL(MAX(prompt_number),0) as maxPromptNumber FROM pages WHERE user_id='$userID' AND `date_local`='$date'";
//        $line = DB::select($query)->first();
        $result = DB::select($query);
        if(count($result)==0){
            return 1;
        }
        $line = json_decode(json_encode($result[0]),true);
        $promptNumber = $line['maxPromptNumber']+1;
        return $promptNumber;
    }
    private function findNextQuerySegmentLabel($userID,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();
        $query = "SELECT IFNULL(MAX(query_segment_label),0) as maxQuerySegmentID FROM querysegment_labels_users WHERE user_id='$userID' AND `date_local`='$date'";
//        $line = DB::select($query)->first();
        $result = DB::select($query);
        if(count($result)==0){
            return 1;
        }
        $line = json_decode(json_encode($result[0]),true);
        $querySegmentID = $line['maxQuerySegmentID']+1;
        return $querySegmentID;
    }

    private function markQuerySegmentLabel($userID,$projectID,$querySegmentID,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();

        $query = "INSERT INTO querysegment_labels_users (`user_id`,`project_id`,`query_segment_label`,`deleted`,`date_local`) VALUES ('$userID','$userID',$querySegmentID,0,'$date')";
        $line = DB::insert($query);

        return DB::getPdo()->lastInsertId();
    }

    private function sameMostRecentURLByTab($url,$tabID,$userID,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();

        $query = "SELECT * FROM pages WHERE user_id=$userID AND tab_id=$tabID AND `date_local`='$date' ORDER BY id DESC LIMIT 1";
//        $pageline = DB::select($query)->first();
        $result = DB::select($query);
        if(count($result)==0){
            return false;
        }
        $pageline = json_decode(json_encode($result[0]),true);

        return ($url== $pageline['url']);
    }

    private function sameMostRecentURL($url,$userID,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();
        $query = "SELECT * FROM pages WHERE user_id=$userID AND `date_local`='$date' ORDER BY id DESC LIMIT 1";
        //        TODO: query language; fetch result
//        $pageline = DB::select($query)->first();
        $result = DB::select($query);
        if(count($result)==0){
            return false;
        }
        $pageline = json_decode(json_encode($result[0]),true);
        return ($url== $pageline['url']);
    }

    private function sameMostRecentActiveTab($userID,$tabID,$startTimestamp){
        // Check action history for user.  If most recent webNavigation.onCommitted or tab.onActivated (besides the most recent one) is
        $date = date('Y-m-d', $startTimestamp);
        $date = Carbon::parse($date)->toDateTimeString();
        $query = "SELECT * FROM actions WHERE user_id=$userID AND `action` IN ('tabs.onActivated','webNavigation.onCommitted') AND `date_local`='$date' ORDER BY id DESC";
        //        TODO: query language; fetch result
        $results = DB::select($query);
        if(count($results)<=1){
            return false;
        }else{
            $line = json_decode(json_encode($results[1]),true);
            $tabdetail = json_decode($line['action_json'],true);
            return $tabdetail['tabId']==$tabID;
        }
    }

    private function getPageByTabIdURL($userID,$tabID,$url,$startTimestamp){
        $date = date('Y-m-d', $startTimestamp);
        echo $date;
        $query = "SELECT * FROM pages WHERE user_id='$userID' AND `url`='$url' AND `date_local`='$date' AND `tab_id`=$tabID ORDER BY id DESC";
        $results = DB::select($query);
        echo "results";
        print_r($results);
        if(count($results)<=0) {
            return null;
        }else{
            $line = json_decode(json_encode($results[0]),true);
            return $line;
        }
    }


    private function extractQuery($referrer)
    {
        $ref = $referrer;
        $queryString = false;

        $se_stuff = array();
        $se_stuff[] = array("google.com", "q", "Google");
        $se_stuff[] = array("google.co.uk", "q", "Google");
        $se_stuff[] = array("ask.com", "q", "Ask.com");
        $se_stuff[] = array("ask.co.uk", "ask", "Ask.co.uk");
        $se_stuff[] = array("comcast.net", "?cat=Web&con=betaa&q", "Comcast");
        $se_stuff[] = array("yahoo", "p", "Yahoo");
        $se_stuff[] = array("yahoo.co.uk", "p", "Yahoo");
        $se_stuff[] = array("aol.com", "query", "AOL");
        $se_stuff[] = array("msn.com", "q", "MSN");
        $se_stuff[] = array("live.com", "q", "Live");
        $se_stuff[] = array("bing.com", "q", "Bing");
        $se_stuff[] = array("netscape.com", "query", "Netscape");
        $se_stuff[] = array("netzero.net", "query", "NetZero");
        $se_stuff[] = array("altavista.com", "q", "Altavista");
        $se_stuff[] = array("mywebsearch.com", "searchfor", "Mywebsearch");
        $se_stuff[] = array("alltheweb.com", "q", "Alltheweb");
        $se_stuff[] = array("cnn.com", "query", "CNN");
        $se_stuff[] = array("myspace.com", "q", "MySpace");
        $se_stuff[] = array("wikipedia.org", "search", "Wikipedia");
        $se_stuff[] = array("en.wikipedia.org", "search", "Wikipedia");
        $se_stuff[] = array("searchme.com", "q", "Searchme");
        $se_stuff[] = array("duckduckgo.com", "q", "Duckduckgo");

        for($i=0, $size = sizeof($se_stuff); $i < $size; $i++)
        {
            if (stristr($ref,$se_stuff[$i][0]) )
            {
                // Additional check for google to make sure the revised query strings get properly captured
                if(strcmp($se_stuff[$i][0],"google.com")==0 || strcmp($se_stuff[$i][0],"google.co.uk")==0)
                {
                    $symbol = $se_stuff[$i][1];
                    //Reformulations
                    if(stristr($ref,"#$symbol=")){
                        $temp1 = explode("#$symbol=", $ref, strlen($ref));
                        $temp2 = explode("&", $temp1[count($temp1)-1]);
                        $string = $temp2[0];
                        $queryString = urldecode($string);
                        break;
                    }
                    if(stristr($ref,"?$symbol=") && !stristr($ref,"&$symbol=")){
                        $temp1 = explode("?$symbol=", $ref, strlen($ref));
                        $temp2 = explode("&", $temp1[count($temp1)-1]);
                        $string = $temp2[0];
                        $queryString = urldecode($string);
                        break;
                    }
                    if(stristr($ref,"&$symbol=")){
                        $temp1 = explode("&$symbol=", $ref, strlen($ref));
                        $temp2 = explode("&", $temp1[count($temp1)-1]);
                        $string = $temp2[0];
                        $queryString = urldecode($string);
                        break;
                    }
                }


                // general guery string
                $symbol = $se_stuff[$i][1];
                if(stristr($ref,"?$symbol=") && !stristr($ref,"&$symbol=")){
                    $temp1 = explode("?$symbol=", $ref, strlen($ref));
                    $temp2 = explode("&", $temp1[count($temp1)-1]);
                    $string = $temp2[0];
                    $queryString = urldecode($string);
                    break;
                }
                if(stristr($ref,"&$symbol=")){
                    $temp1 = explode("&$symbol=", $ref, strlen($ref));
                    $temp2 = explode("&", $temp1[count($temp1)-1]);
                    $string = $temp2[0];
                    $queryString = urldecode($string);
                    break;
                }

                /*
                $symbol = $se_stuff[$i][1];
                $temp1 = explode("$symbol=", $ref, 2);
                $temp2 = explode("&", $temp1[1]);
                $string = $temp2[0];
                $queryString = urldecode($string);
                break;*/
            }
        }

        return $queryString;
    } // end extractQuery


    // createPageOrQueryOriginal() suddenly started causing connection failure.
    // The createPageOrQuery() below currently has chunks commented out.

    public function createPageOrQuery(Request $req){

        $api_key = env('GOOGLE_APIKEY');
        $cx = env('GOOGLE_CX');

        $save_files = false;

        $tabID = $req->input('tabId');
        $windowID= $req->input('windowId');
        $localTimestamp = $req->input('created_at_local');
        $localTimestamp_ms = $req->input('created_at_local_ms');
        $timestamp = Carbon::now();
        $date = Carbon::today()->toDateString();
        echo "DATE";
        echo $date;
                //  $date_local = $req->input('created_at_local');
        $url = $req->input('url');
        $title = $req->input('title');
    //        $url = mysql_escape_string($req->input('url'));
    //        $title = mysql_escape_string($req->input('title'));
        $active_tab = intval($req->input('active')=='true');
        $is_coagmento = intval(substr($req->input('url'), 0, strlen('http://coagmento.org')) === 'http://coagmento.org');

        $querySegmentID = 'NULL';

        $new_querySegmentID = null;
        $new_querySegment = false;
        $new_query = null;

        $details_string = $req->input('details');
    //        $details_string = mysql_escape_string($req->input('details'));
        $details = json_decode($req->input('details'),true);

        $projectID = $req->input('project_id');
        $userID = $req->input('user_id');
        $stageID = $req->input('stage_id');
        $action= $req->input('action');

        $querySegmentID_automatic = 0;

        $title = str_replace(" - Mozilla Firefox","",$title);
    //        $title = mysql_escape_string($title);

        $host = "";
        $parse = parse_url($url);
        if ($parse && isset($parse['host'])){
            $host = $parse['host'];
    //            $host = mysql_escape_string($host);
        }else{
            $host = '';
        }

        $temp_url = $url;
        $temp_url = str_replace("http://", "", $temp_url); // Remove 'http://' from the reference
        $temp_url = str_replace("https://", "", $temp_url); // Remove 'https://' from the reference
        $temp_url = str_replace("com/", "com.", $temp_url);
        $temp_url = str_replace("org/", "org.", $temp_url);
        $temp_url = str_replace("edu/", "edu.", $temp_url);
        $temp_url = str_replace("gov/", "gov.", $temp_url);
        $temp_url = str_replace("us/", "us.", $temp_url);
        $temp_url = str_replace("ca/", "ca.", $temp_url);
        $temp_url = str_replace("uk/", "uk.", $temp_url);
        $temp_url = str_replace("es/", "es.", $temp_url);
        $temp_url = str_replace("net/", "net.", $temp_url);
        $entry = explode(".", $temp_url);
        $i = 0;
        $isWebsite = 0;
        $site = NULL;

        while (isset($entry[$i]) && ($isWebsite == 0))
        {
            $entry[$i] = strtolower($entry[$i]);
            if (($entry[$i] == "com") || ($entry[$i] == "edu") || ($entry[$i] == "org") || ($entry[$i] == "gov") || ($entry[$i] == "info") || ($entry[$i] == "us") || ($entry[$i] == "ca") || ($entry[$i] == "es") || ($entry[$i] == "uk") || ($entry[$i] == "net"))
            {
                $isWebsite = 1;
                if(($entry[$i] == "uk") && strpos($url,'uk.yahoo.com') !== false){
                    $domain = $entry[$i+2];
                    $site = $entry[$i+1];
                }else if(($entry[$i] == "uk") && strpos($url,'uk.search.yahoo.com') !== false){
                    $domain = $entry[$i+3];
                    $site = $entry[$i+2];
                }else if(($entry[$i] == "uk") && strpos($url,'.co.uk') !== false){
                    $domain = $entry[$i];
                    $site = $entry[$i-2];
                }else{
                    $domain = $entry[$i];
                    $site = $entry[$i-1];
                }
            }
            $i++;
        }

        // Extract the query if there is any
        $queryString = $this->extractQuery($url);
    //        $queryString = mysql_escape_string($queryString);
        $is_query = intval(!(is_null($queryString)) and $queryString and $queryString != '');
        $is_prompt = $url == 'https://www.google.com/';
        $prompt_number = 0;
        if($is_prompt){
            $prompt_number = $this->findNextPromptNumber($userID,$localTimestamp);
        }
        $is_prompt = false;



        //When implementing full version verify membership
    //	if (!isset($_SESSION['CSpace_lastURL']) || $url!=$_SESSION['CSpace_lastURL'])
    //	{
    //		if($action=='tabs.onUpdated' and sameMostRecentURL($url,$tabID,$userID) and $details['tab']['active']==true){
        if(($action=='tabs.onUpdated' or $action=='webNavigation.onCommitted') and $this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp)) {
    //			If the page updated but the URL is the same, do nothing.
    //			If a normal tabUpdated action, just exit.  Only want to handle tabUpdated on clickthrough
    //			Other reasons for tabUpdated: 1) same as onCommitted (same URL) 2) frequent reloading (also same URL)
    //            echo "FIRST";

            $is_prompt = $url == 'https://www.google.com/';
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
    //            exit();
        }else if($action=='webNavigation.onCommitted' and $details['tab']['active']==false){
    //        	If web commit on an inactive tab, no need to record
    //            echo "SECOND";
            $is_prompt = false;
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
    //            exit();
        }else if(($action=='tabs.onActivated') and $this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp) and $this->sameMostRecentActiveTab($userID,$tabID,$localTimestamp)){
    //			If tab activated on a tab that 1) has the same URL and 2) was the same active tab as before anyway, then no need to record. (Happens on Ctrl+T)
    //
    //            echo "THIRD";

            $is_prompt = false;
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
    //		}else if(($action=='tabs.onActivated') and sameMostRecentURL($url,$tabID,$userID) and $details['tab']['active']==true){

    //            exit();
        }else if($action=='tabs.onUpdated'){
            $is_prompt = false;
    //            echo "FOURTH";
            if(array_key_exists ('active' , $details ) && $details['active'] == true){
              if($this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp) and $this->sameMostRecentActiveTab($userID,$tabID,$localTimestamp)){
                 exit();
              }
              else{
      // //        		TODO: commented out.  Assumed that this will always be coupled with webNavigation.onCommitted
      // //            			tabs.onUpdated, but a different URL from the last one. fetch the most recent querySegmentID
      //                  if($is_query){
      //                      $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
      //                      $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
      //
      //                  }else{
      //                      $query = "SELECT * FROM pages WHERE userID='$userID' AND tabID=$tabID ORDER BY pageID DESC LIMIT 1";
      //                      $connection = Connection::getInstance();
      //                      $results = $connection->commit($query);
      //                      $line = mysql_fetch_array($results,MYSQL_ASSOC);
      //                      $querySegmentID = $line['querySegmentID'];
      //                  }
              }
            }
          }
        else if($action=='webNavigation.onCommitted' and $details['tab']['active']==true){
    //            echo "FIFTH";
            $is_prompt = $url == 'https://www.google.com/';
            if(in_array($details['transitionType'],array('generated','form_submit','link','typed','keyword','keyword_generated'))){
    //				echo "FIFTH1.1";

                if(in_array('forward_back',$details['transitionQualifiers'])){

                    $line = $this->getPageByTabIdURL($userID,$tabID,$url,$localTimestamp);
                    print_r("line") ;
                    print_r($line) ;
                    if(is_null($line) or is_null($line['query_segment_id'])){
                      //echo("is_null");
                    }else{
                        //echo("not");
                        $querySegmentID = $line['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID)&& $querySegmentID!=0;
                    }
                        //echo "FIFTH1.1.1...$querySegmentID...";

                }
                else if($is_query){
                        //echo "FIFTH1.1.2";
                    $querySegmentID = $this->findNextQuerySegmentLabel($userID,$localTimestamp);
                    $new_querySegmentID = $querySegmentID;
                    $new_querySegment = true;
                    $new_query = $queryString;
                    $querySegmentID = $this->markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp);
                    $querySegmentID_automatic = 1;
                }else{
                        //echo "FIFTH1.1.3";
                    $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$tabID ORDER BY id DESC LIMIT 1";
                    //echo $query;
                    $result = DB::select($query);
    //                    dd("FIFTH1.1.3".$query);
    //                    dd($result);
    //                    TODO: Why 0 results sometimes?  Improper recording?
                    //echo $result;
                    if(count($result)>0){
                        $line = json_decode(json_encode($result[0]),true);
    //                    $line = DB::select($query)->first();
                        $querySegmentID = $line['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
                    }


                }
            }else{
    //				Some auto or form submit
                exit();
            }
    //		else if($action=='webNavigation.onCommitted' and $details['tab']['active']==true and $is_query and $details['transitionType']=='generated' and in_array('from_address_bar',$details['transitionQualifiers'])){
    ////			Omnibox entry. New query
    //			$querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
    //            $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
    //		}else if($action=='webNavigation.onCommitted' and $is_query and $details['transitionType']=='link' and count($details['transitionQualifiers'])==0 and $details['tab']['active']==true){
    ////			SERP box entry. New query
    //            $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
    //            $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
    //		}else if($action=='webNavigation.onCommitted' and $details['transitionType']=='link' and count($details['transitionQualifiers'])==0 and $details['tab']['active']==true){
    //			if($is_query){
    //                $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
    //                $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
    //			}else{
    //				$query = "SELECT * FROM pages WHERE userID='$userID' AND tabID=$tabID ORDER BY pageID DESC LIMIT 1";
    //				$connection = Connection::getInstance();
    //				$results = $connection->commit($query);
    //				$line = mysql_fetch_array($results,MYSQL_ASSOC);
    //				$querySegmentID = $line['querySegmentID'];
    //			}
        }else if($action=='tabs.onActivated'){

            $is_prompt = false;
    //			Not webNavigation, not tabUpdated, must be tabActivated action
            $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$tabID ORDER BY id DESC LIMIT 1";
            $result = DB::select($query);

            print_r($result);
    //            dd("tabs.onActivated".$query);
    //            dd($result);
    //            TODO: Why is result length 0 sometimes?  Bad input from previous request?
            $line = null;
            if(count($line)>0){
                $line = json_decode(json_encode($result[0]),true);
            }

    //            $line = DB::select($query)->first();
            if(!is_null($line) and isset($line['query_segment_id'])){
    //				Get previous querySegmentID assignment of tab
                $querySegmentID = $line['query_segment_id'];
                $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
            }else{


                //				Cases:
    //				1) New tab (ID=NULL)
    //				2) Query opened from page/query (new querySegmentID)
    //				3) URL opened from page/query (get opener tab's ID)
                if (strpos($url, 'chrome://newtab') !== false){
                    //Do nothing (bad code practice, I know)
                    $querySegmentID='NULL';
                    $querySegmentID_automatic = 0;

                }
                else if($is_query){
                    $querySegmentID = $this->findNextQuerySegmentLabel($userID,$localTimestamp);
                    $new_querySegmentID = $querySegmentID;
                    $new_querySegment = true;
                    $new_query = $queryString;
                    $querySegmentID = $this->markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp);
                    $querySegmentID_automatic = 1;
                }else{
    //					Get the creator tab
    //					Get that creator
                    $query = "SELECT action_json FROM actions WHERE user_id='$userID' AND `action`='tabs.onCreated' AND value='$tabID' ORDER BY `id` DESC LIMIT 1";
                    $results = DB::select($query);

                    $prevTabID=null;
                    if(count($results)==0){
                        $prevTabID = $tabID;
                        //echo "No creator tab!";

                    }else{
                        $line = json_decode(json_encode($results[0]),true);
                        $prevTabDetails = json_decode($line['action_json'],true);

                        if(array_key_exists('newTab',$prevTabDetails) and array_key_exists('openerTabId',$prevTabDetails['newTab'])){
                            $prevTabID = $prevTabDetails['newTab']['openerTabId'];
                        }else if(array_key_exists('currentTab',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['currentTab'][0]['id'];
                        }else if(array_key_exists('openerTabId',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['openerTabId'];
                        }else if(array_key_exists('tab',$prevTabDetails) and array_key_exists('openerTabId',$prevTabDetails['tab'])){
                            $prevTabID = $prevTabDetails['tab']['openerTabId'];
                        }
                        else if(array_key_exists('tabId',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['tabId'];
                        }
                    }


                    $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$prevTabID ORDER BY id DESC LIMIT 1";
                    $line = DB::select($query);
                    if(count($line)>0 and isset(json_decode(json_encode($line[0]),true)['query_segment_id'])){
                        $querySegmentID = json_decode(json_encode($line[0]),true)['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
                    }

                }

            }
        }


        if($querySegmentID==''){
            $querySegmentID='NULL';
            $querySegmentID_automatic = 0;
        }



        $page = new Page();
        $user_id = Auth::user()->id;
        $project_id = 1;
        $stage_id = 1;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
        $page->user_id = $user_id;
        $page->project_id = $project_id;
        $page->stage_id = $stage_id;
        $page->source = $site;
        $page->host = $host;
        $page->url = $url;
        $page->title = $title;
        $page->query = $queryString;
        $page->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
        $page->created_at_local_ms = $localTimestamp_ms;
        $page->trash = 0;
        $page->date_local = $date;
        $page->permanently_delete = 0;
        $page->is_query = $is_query;
        $page->active_tab = $active_tab;
        $page->tab_id = $tabID;
        $page->window_id = $windowID;
        $page->is_coagmento = $is_coagmento;
        $page->details = $details_string;
        $page->query_segment_id = $querySegmentID;
        $page->query_segment_id_automatic = $querySegmentID_automatic;
        if($prompt_number!=0){
            $page->prompt_number = $prompt_number;
        }
        $page->save();

        $action = new Action();
        $action->user_id = $user_id;
        $action->project_id = $project_id;
        $action->stage_id =  $stage_id;
        $action->action = "page";
        $action->value = $page->id;
        $action->json = null;
        $action->action_json = null;
        $action->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
        $action->created_at_local_ms = $localTimestamp_ms;
        $action->date_local = $date;//Carbon::now()->format('Y-m-d');
        $action->save();

        $pageID = $page->id;
        echo $page->date_local;

        // Finding the search engine used for each query
        $searchEngine=0;
        if (strpos($url,'www.google.com') !== false || strpos($url,'google.co.uk') !== false || strpos($url,'www.google.ca') !== false || strpos($url,'www.google.si') !== false)
        {
            $searchEngine='google';
        }
        else if (strpos($url,'search.yahoo.com') !== false || strpos($url,'uk.yahoo.com') !== false || strpos($url,'yahoo.co.uk') !== false)
        {
            $searchEngine='yahoo';
        }
        else if (strpos($url,'www.bing.com') !== false)
        {
            $searchEngine='bing';
        }
        else
        {
            $searchEngine='other';
        }

        if ($is_query)
        {

            if(strpos($url,'google.co.uk') !== false){
                $site = "google UK";
            }
            if(strpos($url,'google.ca') !== false){
                $site = "google CA";
            }
            if(strpos($url,'google.si') !== false){
                $site = "google SI";
            }

            // // Is there an existing SERP from today?
            $query = "SELECT * FROM queries WHERE user_id='$userID' AND query='$queryString' AND source='$site' AND `date_local`='$date'";
            $results = DB::select($query);
            $exists_serp = 0;
            if (count($results) > 0){
                $exists_serp = 1;
            }else{
                $exists_serp = 0;
            }

            $q = new Query();
            $q->user_id = $user_id;
            $q->project_id = $project_id;
            $q->stage_id =  $stage_id;
            $q->search_engine = $searchEngine;
            $q->query = $queryString;
            $q->source = $site;
            $q->host = $host;
            $q->url = $url;
            $q->title = $title;
            $q->date_local = $date;
            $q->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
            $q->created_at_local_ms = $localTimestamp_ms;
            $q->status = 1;
            $q->trash = 0;
            $q->permanently_delete = 0;
            $q->active_tab = $active_tab;
            $q->tab_id = $tabID;
            $q->window_id = $windowID;
            $q->is_coagmento = $is_coagmento;
            $q->details = $details_string;
            $q->query_segment_id = $querySegmentID;
            $q->query_segment_id_automatic = $querySegmentID_automatic;
            $q->save();

            $action = new Action();
            $action->user_id = $user_id;
            $action->project_id = $project_id;
            $action->stage_id =  $stage_id;
            $action->action = "query";
            $action->value = $q->id;
            $action->date_local = Carbon::now()->format('Y-m-d');
            $action->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
            $action->created_at_local_ms = $localTimestamp_ms;
            $action->date_local = $date;//Carbon::now()->format('Y-m-d');
            $action->save();

            /*-----Code to save Google SERP page results as json files-----*/
            // if($searchEngine=='google')
            // {
            //     if($exists_serp == 0){
            //         $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
            //         $data = '';
            //
            //         if(strpos($url,'google.si') !== false){
            //             $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.si&q=".$query_stringwithplus."'";
            //             $data=shell_exec($cmd);
            //         }
            //         else if(strpos($url,'google.ca') !== false){
            //             $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.ca&q=".$query_stringwithplus."'";
            //             $data=shell_exec($cmd);
            //         }
            //         else if(strpos($url,'google.co.uk') !== false){
            //             $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.co.uk&q=".$query_stringwithplus."'";
            //             $data=shell_exec($cmd);
            //         }else{
            //             $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&q=".$query_stringwithplus."'";
            //             $data=shell_exec($cmd);
            //         }
            //         if($save_files){
            //             $filename_content = "Google_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
            //             $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
            //             fwrite($fileHandle_content, $data);
            //             fclose($fileHandle_content);
            //         }
            //
            //     }
            // }

            /**************************************************/
            /*-----Code to save Yahoo SERP page results as json files-----*/
            /*-----Technically this works but in order to get search data, you have to pay money :(. So not activated at this point.-----*/
            /*
                else if($searchEngine=='yahoo')
                 {
                    $query_stringwithplus = urlencode($queryString);
                    $cc_key  = "dj0yJmk9bWlMcU5hUGVJclpEJmQ9WVdrOVRHbHlNVVJCTnpRbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD01ZA--";
                    $cc_secret = "a6ff4151bc8adbfaacad47d06d84a2340b9369b9";
                    $url = "http://yboss.yahooapis.com/ysearch/news,web";
                    $args = array();
                    $args["q"] = $query_stringwithplus;
                    $args["format"] = "json";

                    $consumer = new OAuthConsumer($cc_key, $cc_secret);
                    $request = OAuthRequest::from_consumer_and_token($consumer, NULL,"GET", $url, $args);
                    $request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);
                    $url = sprintf("%s?%s", $url, "q=".$query_stringwithplus);
                    $ch = curl_init();
                    $headers = array($request->to_header());
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $data = curl_exec($ch);
                    $filename_content = "Yahoo_SERP_user".$userID."_stage".$stageID."_page".$pageID.".json";
                    $fileHandle_content = fopen("/www/userstudy2014.coagmento.rutgers.edu/htdocs/contentPages/".$filename_content, 'w') or die("file could not be accessed/created");
                    fwrite($fileHandle_content, $data);
                    fclose($fileHandle_content);

                }
                */
            /**************************************************/

            /**************************************************/
            /*-----Code to save Bing SERP page results as json files-----*/
            // else if($searchEngine=='bing')
            // {
            //     $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
            //     $account_key = 'QEnKG3ytiksVuOX7nrnj+agA38LD1qSkSMDnNqMQbog';
            //     $temp_url = "https://api.datamarket.azure.com/Bing/Search/v1/Web?\$format=json&Query='".$query_stringwithplus."'";
            //     $ch = curl_init();
            //     curl_setopt($ch, CURLOPT_URL, $temp_url);
            //     curl_setopt($ch, CURLOPT_HEADER, false);
            //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //     curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
            //     curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
            //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //     curl_setopt($ch, CURLOPT_USERPWD, $account_key . ":" . $account_key);
            //     $json = curl_exec($ch);
            //     curl_close($ch);
            //     if($save_files){
            //         $filename_content = "Bing_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
            //         $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
            //         fwrite($fileHandle_content, $json);
            //         fclose($fileHandle_content);
            //     }
            //
            //
            // }
            /**************************************************/
            /**************************************************/
            /*-----Code to save other SERP page results as text files-----*/
            // else
            // {
            //     $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
            //     $request =  "curl -e http://coagmento.org " .$temp_url;
            //     $data=shell_exec($request);
            //     $filename_content = "Other_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
            //     if($save_files){
            //         $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
            //         fwrite($fileHandle_content, $data);
            //         fclose($fileHandle_content);
            //     }
            //
            // }
            // /**************************************************/

        }


    }

    // Original createPageOrQuery() suddenly started causing connection failure.
    // The createPageOrQuery() in use currently has chunks commented out.

    public function createPageOrQueryOriginal(Request $req){

        $api_key = env('GOOGLE_APIKEY');
        $cx = env('GOOGLE_CX');

        $save_files = false;

        $tabID = $req->input('tabId');
        $windowID= $req->input('windowId');
        $localTimestamp = $req->input('created_at_local');
        $localTimestamp_ms = $req->input('created_at_local_ms');
        $timestamp = Carbon::now();
        $date = Carbon::today();
                  $date_local = $req->input('created_at_local');
        $url = $req->input('url');
        $title = $req->input('title');
//        $url = mysql_escape_string($req->input('url'));
//        $title = mysql_escape_string($req->input('title'));
        $active_tab = intval($req->input('active')=='true');
        $is_coagmento = intval(substr($req->input('url'), 0, strlen('http://coagmento.org')) === 'http://coagmento.org');

        $querySegmentID = 'NULL';

        $new_querySegmentID = null;
        $new_querySegment = false;
        $new_query = null;

        $details_string = $req->input('details');
//        $details_string = mysql_escape_string($req->input('details'));
        $details = json_decode($req->input('details'),true);

        $projectID = $req->input('project_id');
        $userID = $req->input('user_id');
        $stageID = $req->input('stage_id');
        $action= $req->input('action');

        $querySegmentID_automatic = 0;

        $title = str_replace(" - Mozilla Firefox","",$title);
//        $title = mysql_escape_string($title);

        $host = "";
        $parse = parse_url($url);
        if ($parse && isset($parse['host'])){
            $host = $parse['host'];
//            $host = mysql_escape_string($host);
        }else{
            $host = '';
        }

        $temp_url = $url;
        $temp_url = str_replace("http://", "", $temp_url); // Remove 'http://' from the reference
        $temp_url = str_replace("https://", "", $temp_url); // Remove 'https://' from the reference
        $temp_url = str_replace("com/", "com.", $temp_url);
        $temp_url = str_replace("org/", "org.", $temp_url);
        $temp_url = str_replace("edu/", "edu.", $temp_url);
        $temp_url = str_replace("gov/", "gov.", $temp_url);
        $temp_url = str_replace("us/", "us.", $temp_url);
        $temp_url = str_replace("ca/", "ca.", $temp_url);
        $temp_url = str_replace("uk/", "uk.", $temp_url);
        $temp_url = str_replace("es/", "es.", $temp_url);
        $temp_url = str_replace("net/", "net.", $temp_url);
        $entry = explode(".", $temp_url);
        $i = 0;
        $isWebsite = 0;
        $site = NULL;

        while (isset($entry[$i]) && ($isWebsite == 0))
        {
            $entry[$i] = strtolower($entry[$i]);
            if (($entry[$i] == "com") || ($entry[$i] == "edu") || ($entry[$i] == "org") || ($entry[$i] == "gov") || ($entry[$i] == "info") || ($entry[$i] == "us") || ($entry[$i] == "ca") || ($entry[$i] == "es") || ($entry[$i] == "uk") || ($entry[$i] == "net"))
            {
                $isWebsite = 1;
                if(($entry[$i] == "uk") && strpos($url,'uk.yahoo.com') !== false){
                    $domain = $entry[$i+2];
                    $site = $entry[$i+1];
                }else if(($entry[$i] == "uk") && strpos($url,'uk.search.yahoo.com') !== false){
                    $domain = $entry[$i+3];
                    $site = $entry[$i+2];
                }else if(($entry[$i] == "uk") && strpos($url,'.co.uk') !== false){
                    $domain = $entry[$i];
                    $site = $entry[$i-2];
                }else{
                    $domain = $entry[$i];
                    $site = $entry[$i-1];
                }
            }
            $i++;
        }

        // Extract the query if there is any
        $queryString = $this->extractQuery($url);
//        $queryString = mysql_escape_string($queryString);
        $is_query = intval(!(is_null($queryString)) and $queryString and $queryString != '');
        $is_prompt = $url == 'https://www.google.com/';
        $prompt_number = 0;
        if($is_prompt){
            $prompt_number = $this->findNextPromptNumber($userID,$localTimestamp);
        }
        $is_prompt = false;



        //When implementing full version verify membership
//	if (!isset($_SESSION['CSpace_lastURL']) || $url!=$_SESSION['CSpace_lastURL'])
//	{
//		if($action=='tabs.onUpdated' and sameMostRecentURL($url,$tabID,$userID) and $details['tab']['active']==true){
        if(($action=='tabs.onUpdated' or $action=='webNavigation.onCommitted') and $this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp)) {
//			If the page updated but the URL is the same, do nothing.
//			If a normal tabUpdated action, just exit.  Only want to handle tabUpdated on clickthrough
//			Other reasons for tabUpdated: 1) same as onCommitted (same URL) 2) frequent reloading (also same URL)
//            echo "FIRST";

            $is_prompt = $url == 'https://www.google.com/';
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
//            exit();
        }else if($action=='webNavigation.onCommitted' and $details['tab']['active']==false){
//        	If web commit on an inactive tab, no need to record
//            echo "SECOND";
            $is_prompt = false;
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
//            exit();
        }else if(($action=='tabs.onActivated') and $this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp) and $this->sameMostRecentActiveTab($userID,$tabID,$localTimestamp)){
//			If tab activated on a tab that 1) has the same URL and 2) was the same active tab as before anyway, then no need to record. (Happens on Ctrl+T)
//
//            echo "THIRD";

            $is_prompt = false;
            return response()->json(['pqsuccess'=>true,'new_querysegment'=>false,'new_querysegmentid'=>null,'new_query'=>'','new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);
//		}else if(($action=='tabs.onActivated') and sameMostRecentURL($url,$tabID,$userID) and $details['tab']['active']==true){

//            exit();
        }else if($action=='tabs.onUpdated'){
            $is_prompt = false;
//            echo "FOURTH";
            if($details['active']==false){
                echo "not active!";
               exit();
            }
            else
             if($this->sameMostRecentURLByTab($url,$tabID,$userID,$localTimestamp) and $this->sameMostRecentActiveTab($userID,$tabID,$localTimestamp)){
                exit();
            }else{
//        		TODO: commented out.  Assumed that this will always be coupled with webNavigation.onCommitted
                //			tabs.onUpdated, but a different URL from the last one. fetch the most recent querySegmentID
                if($is_query){
                    $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
                    $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);

                }else{
                    $query = "SELECT * FROM pages WHERE userID='$userID' AND tabID=$tabID ORDER BY pageID DESC LIMIT 1";
                    $connection = Connection::getInstance();
                    $results = $connection->commit($query);
                    $line = mysql_fetch_array($results,MYSQL_ASSOC);
                    $querySegmentID = $line['querySegmentID'];
                }
            }

        }
        else if($action=='webNavigation.onCommitted' and $details['tab']['active']==true){
//            echo "FIFTH";
            $is_prompt = $url == 'https://www.google.com/';
            if(in_array($details['transitionType'],array('generated','form_submit','link','typed','keyword','keyword_generated'))){
//				echo "FIFTH1.1";

                if(in_array('forward_back',$details['transitionQualifiers'])){

                    $line = $this->getPageByTabIdURL($userID,$tabID,$url,$localTimestamp);
                    if(is_null($line) or is_null($line['query_segment_id'])){
                        $querySegmentID = 'NULL';
                        $querySegmentID_automatic = 0;
                    }else{
                        $querySegmentID = $line['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID)&& $querySegmentID!=0;
                    }
//                    echo "FIFTH1.1.1...$querySegmentID...";

                }
                else if($is_query){
//                    echo "FIFTH1.1.2";
                    $querySegmentID = $this->findNextQuerySegmentLabel($userID,$localTimestamp);
                    $new_querySegmentID = $querySegmentID;
                    $new_querySegment = true;
                    $new_query = $queryString;
                    $querySegmentID = $this->markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp);
                    $querySegmentID_automatic = 1;
                }else{
//                    echo "FIFTH1.1.3";
                    $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$tabID ORDER BY id DESC LIMIT 1";
                    $result = DB::select($query);
//                    dd("FIFTH1.1.3".$query);
//                    dd($result);
//                    TODO: Why 0 results sometimes?  Improper recording?
                    if(count($result)>0){
                        $line = json_decode(json_encode($result[0]),true);
//                    $line = DB::select($query)->first();
                        $querySegmentID = $line['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
                    }


                }
            }else{
//				Some auto or form submit
                exit();
            }
//		else if($action=='webNavigation.onCommitted' and $details['tab']['active']==true and $is_query and $details['transitionType']=='generated' and in_array('from_address_bar',$details['transitionQualifiers'])){
////			Omnibox entry. New query
//			$querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
//            $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
//		}else if($action=='webNavigation.onCommitted' and $is_query and $details['transitionType']=='link' and count($details['transitionQualifiers'])==0 and $details['tab']['active']==true){
////			SERP box entry. New query
//            $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
//            $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
//		}else if($action=='webNavigation.onCommitted' and $details['transitionType']=='link' and count($details['transitionQualifiers'])==0 and $details['tab']['active']==true){
//			if($is_query){
//                $querySegmentID = findNextQuerySegmentLabel($userID,$localTimestamp/1000);
//                $querySegmentID = markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp/1000);
//			}else{
//				$query = "SELECT * FROM pages WHERE userID='$userID' AND tabID=$tabID ORDER BY pageID DESC LIMIT 1";
//				$connection = Connection::getInstance();
//				$results = $connection->commit($query);
//				$line = mysql_fetch_array($results,MYSQL_ASSOC);
//				$querySegmentID = $line['querySegmentID'];
//			}
        }else if($action=='tabs.onActivated'){

            $is_prompt = false;
//			Not webNavigation, not tabUpdated, must be tabActivated action
            $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$tabID ORDER BY id DESC LIMIT 1";
            $result = DB::select($query);
//            dd("tabs.onActivated".$query);
//            dd($result);
//            TODO: Why is result length 0 sometimes?  Bad input from previous request?
            $line = null;
            if(count($line)>0){
                $line = json_decode(json_encode($result[0]),true);
            }

//            $line = DB::select($query)->first();
            if(!is_null($line) and isset($line['query_segment_id'])){
//				Get previous querySegmentID assignment of tab
                $querySegmentID = $line['query_segment_id'];
                $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
            }else{


                //				Cases:
//				1) New tab (ID=NULL)
//				2) Query opened from page/query (new querySegmentID)
//				3) URL opened from page/query (get opener tab's ID)
                if (strpos($url, 'chrome://newtab') !== false){
                    //Do nothing (bad code practice, I know)
                    $querySegmentID='NULL';
                    $querySegmentID_automatic = 0;

                }
                else if($is_query){
                    $querySegmentID = $this->findNextQuerySegmentLabel($userID,$localTimestamp);
                    $new_querySegmentID = $querySegmentID;
                    $new_querySegment = true;
                    $new_query = $queryString;
                    $querySegmentID = $this->markQuerySegmentLabel($userID,$projectID,$querySegmentID,$localTimestamp);
                    $querySegmentID_automatic = 1;
                }else{
//					Get the creator tab
//					Get that creator
                    $query = "SELECT action_json FROM actions WHERE user_id='$userID' AND `action`='tabs.onCreated' AND value='$tabID' ORDER BY `id` DESC LIMIT 1";
                    $results = DB::select($query);

                    $prevTabID=null;
                    if(count($results)==0){
                        $prevTabID = $tabID;
                        //echo "No creator tab!";

                    }else{
                        $line = json_decode(json_encode($results[0]),true);
                        $prevTabDetails = json_decode($line['action_json'],true);

                        if(array_key_exists('newTab',$prevTabDetails) and array_key_exists('openerTabId',$prevTabDetails['newTab'])){
                            $prevTabID = $prevTabDetails['newTab']['openerTabId'];
                        }else if(array_key_exists('currentTab',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['currentTab'][0]['id'];
                        }else if(array_key_exists('openerTabId',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['openerTabId'];
                        }else if(array_key_exists('tab',$prevTabDetails) and array_key_exists('openerTabId',$prevTabDetails['tab'])){
                            $prevTabID = $prevTabDetails['tab']['openerTabId'];
                        }
                        else if(array_key_exists('tabId',$prevTabDetails)){
                            $prevTabID = $prevTabDetails['tabId'];
                        }
                    }


                    $query = "SELECT * FROM pages WHERE user_id='$userID' AND tab_id=$prevTabID ORDER BY id DESC LIMIT 1";
                    $line = DB::select($query);
                    if(count($line)>0 and isset(json_decode(json_encode($line[0]),true)['query_segment_id'])){
                        $querySegmentID = json_decode(json_encode($line[0]),true)['query_segment_id'];
                        $querySegmentID_automatic = !is_null($querySegmentID) && $querySegmentID!=0;
                    }

                }

            }
        }


        if($querySegmentID==''){
            $querySegmentID='NULL';
            $querySegmentID_automatic = 0;
        }



        $page = new Page();
        $user_id = Auth::user()->id;
        $project_id = 1;
        $stage_id = 1;
        if(Session::has('project_id')){
            $project_id = Session::get('project_id');
        }
        if(Session::has('stage_id')){
            $stage_id = Session::get('stage_id');
        }
        $page->user_id = $user_id;
        $page->project_id = $project_id;
        $page->stage_id = $stage_id;
        $page->source = $site;
        $page->host = $host;
        $page->url = $url;
        $page->title = $title;
        $page->query = $queryString;
        $page->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
        $page->created_at_local_ms = $localTimestamp_ms;
        $page->trash = 0;
        $page->date_local = $date;
        $page->permanently_delete = 0;
        $page->is_query = $is_query;
        $page->active_tab = $active_tab;
        $page->tab_id = $tabID;
        $page->window_id = $windowID;
        $page->is_coagmento = $is_coagmento;
        $page->details = $details_string;
        $page->query_segment_id = $querySegmentID;
        $page->query_segment_id_automatic = $querySegmentID_automatic;
        if($prompt_number!=0){
            $page->prompt_number = $prompt_number;
        }
        $page->save();

        $action = new Action();
        $action->user_id = $user_id;
        $action->project_id = $project_id;
        $action->stage_id =  $stage_id;
        $action->action = "page";
        $action->value = $page->id;
        $action->json = null;
        $action->action_json = null;
        $action->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
        $action->created_at_local_ms = $localTimestamp_ms;
        $action->date_local = $date;//Carbon::now()->format('Y-m-d');
        $action->save();

        $pageID = $page->id;


        // Finding the search engine used for each query
        $searchEngine=0;
        if (strpos($url,'www.google.com') !== false || strpos($url,'google.co.uk') !== false || strpos($url,'www.google.ca') !== false || strpos($url,'www.google.si') !== false)
        {
            $searchEngine='google';
        }
        else if (strpos($url,'search.yahoo.com') !== false || strpos($url,'uk.yahoo.com') !== false || strpos($url,'yahoo.co.uk') !== false)
        {
            $searchEngine='yahoo';
        }
        else if (strpos($url,'www.bing.com') !== false)
        {
            $searchEngine='bing';
        }
        else
        {
            $searchEngine='other';
        }


        if ($is_query)
        {

            if(strpos($url,'google.co.uk') !== false){
                $site = "google UK";
            }
            if(strpos($url,'google.ca') !== false){
                $site = "google CA";
            }
            if(strpos($url,'google.si') !== false){
                $site = "google SI";
            }

            // Is there an existing SERP from today?
            $query = "SELECT * FROM queries WHERE user_id='$userID' AND query='$queryString' AND source='$site' AND `date_local`='$date'";
            $results = DB::select($query);
            $exists_serp = 0;
            if (count($results) > 0){
                $exists_serp = 1;
            }else{
                $exists_serp = 0;
            }

            $q = new Query();
            $q->user_id = $user_id;
            $q->project_id = $project_id;
            $q->stage_id =  $stage_id;
            $q->search_engine = $searchEngine;
            $q->query = $queryString;
            $q->source = $site;
            $q->host = $host;
            $q->url = $url;
            $q->title = $title;
            $q->date_local = $date;
            $q->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
            $q->created_at_local_ms = $localTimestamp_ms;
            $q->status = 1;
            $q->trash = 0;
            $q->permanently_delete = 0;
            $q->active_tab = $active_tab;
            $q->tab_id = $tabID;
            $q->window_id = $windowID;
            $q->is_coagmento = $is_coagmento;
            $q->details = $details_string;
            $q->query_segment_id = $querySegmentID;
            $q->query_segment_id_automatic = $querySegmentID_automatic;
            $q->save();

            $action = new Action();
            $action->user_id = $user_id;
            $action->project_id = $project_id;
            $action->stage_id =  $stage_id;
            $action->action = "query";
            $action->value = $q->id;
            $action->date_local = Carbon::now()->format('Y-m-d');
            $action->created_at_local = Carbon::createFromTimestamp($localTimestamp)->format('Y-m-d H:i:s');;
            $action->created_at_local_ms = $localTimestamp_ms;
            $action->date_local = $date;//Carbon::now()->format('Y-m-d');
            $action->save();

            /*-----Code to save Google SERP page results as json files-----*/
            if($searchEngine=='google')
            {
                if($exists_serp == 0){
                    $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
                    $data = '';

                    if(strpos($url,'google.si') !== false){
                        $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.si&q=".$query_stringwithplus."'";
                        $data=shell_exec($cmd);
                    }
                    else if(strpos($url,'google.ca') !== false){
                        $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.ca&q=".$query_stringwithplus."'";
                        $data=shell_exec($cmd);
                    }
                    else if(strpos($url,'google.co.uk') !== false){
                        $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&googlehost=google.co.uk&q=".$query_stringwithplus."'";
                        $data=shell_exec($cmd);
                    }else{
                        $cmd = "curl -e http://coagmento.org " . "'https://www.googleapis.com/customsearch/v1?key=$api_key&cx=$cx&q=".$query_stringwithplus."'";
                        $data=shell_exec($cmd);
                    }
                    if($save_files){
                        $filename_content = "Google_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
                        $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
                        fwrite($fileHandle_content, $data);
                        fclose($fileHandle_content);
                    }

                }
            }

            /**************************************************/
            /*-----Code to save Yahoo SERP page results as json files-----*/
            /*-----Technically this works but in order to get search data, you have to pay money :(. So not activated at this point.-----*/
            /*
                else if($searchEngine=='yahoo')
                 {
                    $query_stringwithplus = urlencode($queryString);
                    $cc_key  = "dj0yJmk9bWlMcU5hUGVJclpEJmQ9WVdrOVRHbHlNVVJCTnpRbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD01ZA--";
                    $cc_secret = "a6ff4151bc8adbfaacad47d06d84a2340b9369b9";
                    $url = "http://yboss.yahooapis.com/ysearch/news,web";
                    $args = array();
                    $args["q"] = $query_stringwithplus;
                    $args["format"] = "json";

                    $consumer = new OAuthConsumer($cc_key, $cc_secret);
                    $request = OAuthRequest::from_consumer_and_token($consumer, NULL,"GET", $url, $args);
                    $request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, NULL);
                    $url = sprintf("%s?%s", $url, "q=".$query_stringwithplus);
                    $ch = curl_init();
                    $headers = array($request->to_header());
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $data = curl_exec($ch);
                    $filename_content = "Yahoo_SERP_user".$userID."_stage".$stageID."_page".$pageID.".json";
                    $fileHandle_content = fopen("/www/userstudy2014.coagmento.rutgers.edu/htdocs/contentPages/".$filename_content, 'w') or die("file could not be accessed/created");
                    fwrite($fileHandle_content, $data);
                    fclose($fileHandle_content);

                }
                */
            /**************************************************/

            /**************************************************/
            /*-----Code to save Bing SERP page results as json files-----*/
            else if($searchEngine=='bing')
            {
                $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
                $account_key = 'QEnKG3ytiksVuOX7nrnj+agA38LD1qSkSMDnNqMQbog';
                $temp_url = "https://api.datamarket.azure.com/Bing/Search/v1/Web?\$format=json&Query='".$query_stringwithplus."'";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $temp_url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_USERPWD, $account_key . ":" . $account_key);
                $json = curl_exec($ch);
                curl_close($ch);
                if($save_files){
                    $filename_content = "Bing_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
                    $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
                    fwrite($fileHandle_content, $json);
                    fclose($fileHandle_content);
                }


            }
            /**************************************************/
            /**************************************************/
            /*-----Code to save other SERP page results as text files-----*/
            else
            {
                $query_stringwithplus = urlencode($queryString); //need to encode to get query string words separated by + sign
                $request =  "curl -e http://coagmento.org " .$temp_url;
                $data=shell_exec($request);
                $filename_content = "Other_SERP_user".$userID."_stage".$stageID."_page".$pageID."_query".$q->id.".json";
                if($save_files){
                    $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
                    fwrite($fileHandle_content, $data);
                    fclose($fileHandle_content);
                }

            }
            /**************************************************/

        }
        /* ----ADDED on 05/27/2014 to get content pages saved as HTML in: htdocs/contentPages/----*/
        else
        {

            $ch = curl_init();
            $timeout = 5;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
            if($save_files){
                $filename_content = "CONTENT_user".$userID."_stage".$stageID."_page".$pageID.".html";
                $fileHandle_content = fopen("/www/coagmento.org/htdocs/tbis/webPages/".$filename_content, 'w') or die("file could not be accessed/created");
                fwrite($fileHandle_content, $data);
                fclose($fileHandle_content);
            }

        }



        //return response()->json(['pqsuccess'=>true,'new_querysegment'=>$new_querySegment,'new_querysegmentid'=>$new_querySegmentID,'new_query'=>$new_query,'new_prompt'=>$is_prompt,'prompt_number'=>$prompt_number]);

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
