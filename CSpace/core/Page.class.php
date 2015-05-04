<?php

require_once('Connection.class.php');
require_once('Base.class.php');

class Page extends Base {

  public function __construct(){
    parent::__construct();
  }


  public static function retrieveFromUser($userID, $projectID=FALSE){

  }

  public static function retrieveFromProject($projectID, $start=0, $limit=200){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT pages.*, users.username FROM pages, users WHERE pages.projectID=%d AND pages.userID=users.userID ORDER BY pages.timestamp DESC LIMIT %d, %d", $projectID, $start, $limit);
    $pages = array();
    $results = $cxn->commit($query);
    while($record = mysql_fetch_assoc($results)){
      array_push($pages, $record);
    }
    return $pages;
  }

  public static function delete($pageID){
    $cxn = Connection::getInstance();
    $q = sprintf("DELETE FROM pages WHERE pageID=%d", $pageID);
    $cxn->commit($q);
  }
}
?>
