<?php

require_once('Connection.class.php');
require_once('Base.class.php');

class Query extends Base {

  public function __construct(){
    parent::__construct();
  }


  public static function retrieveFromUser($userID, $projectID=FALSE){

  }

  public static function retrieveFromProject($projectID, $sorting="timestamp DESC"){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT queries.*, users.username FROM queries, users WHERE queries.projectID=%d AND queries.userID=users.userID ORDER BY %s", $projectID, $cxn->esc($sorting));
    $queries = array();
    $results = $cxn->commit($query);
    while($record = mysqli_fetch_assoc($results)){
      array_push($queries, $record);
    }
    return $queries;
  }

  public static function delete($queryID){
    $cxn=Connection::getInstance();
    $q = sprintf("DELETE FROM queries WHERE queryID=%d", $queryID);
    $cxn->commit($q);
  }
}
?>
