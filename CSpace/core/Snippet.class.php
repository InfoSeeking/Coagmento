<?php

require_once('Connection.class.php');
require_once('Base.class.php');

class Snippet extends Base {

  public function __construct(){
    parent::__construct();
  }


  public static function retrieveFromUser($userID, $projectID=FALSE){

  }

  public static function retrieveFromProject($projectID, $sorting="timestamp DESC"){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT snippets.*, users.username FROM snippets, users WHERE snippets.projectID=%d AND snippets.userID=users.userID ORDER BY %s", $projectID, $cxn->esc($sorting));
    $snippets = array();
    $results = $cxn->commit($query);
    while($record = mysql_fetch_assoc($results)){
      array_push($snippets, $record);
    }
    return $snippets;
  }

  public static function delete($snippetID){
    $cxn=Connection::getInstance();
    $query = sprintf("DELETE FROM snippets WHERE snippetID=%d", $snippetID);
    $cxn->commit($query);
  }
}
?>
