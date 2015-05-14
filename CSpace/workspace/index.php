<?php
session_start();
require_once("../core/Base.class.php");
require_once("../core/Bookmark.class.php");
require_once("../core/Page.class.php");
require_once("../core/Snippet.class.php");
require_once("../core/Query.class.php");
require_once("../core/Tags.class.php");
require_once("assets/php/util.php");
require_once("../core/Connection.class.php");
require_once("../core/Questionnaires.class.php");
$base = new Base();
if(!$base->isUserActive()){
  header("Location: ../workspace-login.php?redirect=workspace/index.php");
}

//simple routing
$PAGE = "ALL";
$valid_pages = array("ALL", "BOOKMARKS", "SNIPPETS", "SEARCHES", "ANNOTATIONS", "PAGE_VISITS", "CONTRIBUTIONS", "SOURCES");
if(isset($_GET["page"])){
  $PAGE = $_GET["page"];
}

if(!in_array($PAGE, $valid_pages)){
  exit("Invalid page " . $PAGE);
}

$cxn = Connection::getInstance();
$results = mysqli_fetch_assoc($cxn->commit("SELECT username FROM users WHERE userID=" . $base->getUserID()));
$username = $results["username"];
$userID = $base->getUserID();


$firstLogin = false;
$r = $cxn->commit("SELECT * FROM actions WHERE action='login' AND userID='$userID'");
if(mysqli_num_rows($r)<=1){
  $firstLogin = true;
}

$feed_data = array(); //sorted by date
$tag_data = array(); //only for bookmarks page
$current_tag = "";
$projectID = $base->getSelectedProject();
$sorting = isset($_GET["sorting"]) ? $_GET["sorting"] : "timestamp";
$sorting_order = isset($_GET["sorting_order"]) ? $_GET["sorting_order"] : "DESC";
$sorting_query = $sorting . " " . $sorting_order;
$only_mine = isset($_GET["only_mine"]) ? $_GET["only_mine"] : false;
$hide_pages = isset($_GET["hide_pages"]) ? $_GET["hide_pages"] : true;

if($only_mine){
  $only_mine=$username;
}

switch($PAGE){
  case "ALL":
    $bookmarks = extend_data(Bookmark::retrieveFromProject($projectID), "bookmark");
    if(!$hide_pages){
      $pages = extend_data(Page::retrieveFromProject($projectID), "page");
    }
    $snippets = extend_data(Snippet::retrieveFromProject($projectID), "snippet");
    $searches = extend_data(Query::retrieveFromProject($projectID), "search");
    if(!$hide_pages){
      $feed_data = timestamp_merge($feed_data, $pages);
    }
    $feed_data = timestamp_merge($feed_data, $bookmarks);
    $feed_data = timestamp_merge($feed_data, $snippets);
    $feed_data = timestamp_merge($feed_data, $searches);
  break;
  case "BOOKMARKS":
    $raw_bookmarks = array();
    if(!empty($_GET["bookmark_tag_filter"])){
      $current_tag = $_GET["bookmark_tag_filter"];
      $raw_bookmarks = Bookmark::retrieveFromProjectAndTag($projectID, $_GET["bookmark_tag_filter"], $sorting_query);
    } else {
      $raw_bookmarks = Bookmark::retrieveFromProject($projectID, $sorting_query);
    }
    $snippets = extend_data(Snippet::retrieveFromProject($projectID), "snippet");
    $urlToSnippets = array();//maps urls to snippets
    foreach($snippets as $snippet){
      $s = $snippet["data"];
      $url = $s["url"];
      if(!isset($urlToSnippets[$url])){
        $urlToSnippets[$url] = array();
      }
      array_push($urlToSnippets[$url], $snippet);
    }
    foreach($raw_bookmarks as $key => $val){
      $bookmark = $raw_bookmarks[$key];
      $url = $bookmark["url"];
      if(isset($urlToSnippets[$url])){
        $raw_bookmarks[$key]["snippets"] = $urlToSnippets[$url];
      }
    }
    $bookmarks = extend_data($raw_bookmarks, "bookmark");
    $tag_data = Tags::retrieveFromProject($projectID);
    $feed_data = $bookmarks;
  break;
  case "PAGE_VISITS":
    $pages = extend_data(Page::retrieveFromProject($projectID), "page");
    $feed_data = $pages;
  break;
  case "SNIPPETS":
    $snippets = extend_data(Snippet::retrieveFromProject($projectID, $sorting_query), "snippet");
    $feed_data = $snippets;
  break;
  case "SEARCHES":
    $searches = extend_data(Query::retrieveFromProject($projectID, $sorting_query), "search");
    $feed_data = $searches;
    break;
  case "SOURCES":
    $bookmarks = extend_data(Bookmark::retrieveWithTagsFromProject($projectID), "bookmark");
    $snippets = extend_data(Snippet::retrieveFromProject($projectID), "snippet");
    $feed_data = buildSources($bookmarks, $snippets);
  break;
}

require_once("views/layout.php");
