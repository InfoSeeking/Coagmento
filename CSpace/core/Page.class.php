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
    $query = "SELECT pages.*, users.username FROM pages, users WHERE pages.projectID=".$projectID." AND pages.userID=users.userID AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' AND NOT url LIKE '%google%' AND NOT url LIKE '%yahoo%' AND NOT url LIKE '%bing%'  ORDER BY pages.timestamp DESC LIMIT ".$start.", ".$limit;
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
