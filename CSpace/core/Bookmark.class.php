<?php
/*
Taken from CoagmentoCollaboratory
TODO replace PDO with mysql in this file, or replace mysql with PDO/mysqli everywere else...
*/
require_once('Connection.class.php');
require_once('Base.class.php');
require_once('Tags.class.php');

/**
* Bookmark creating/deleting/updating
*/
class Bookmark extends Base {

  /**
  * Returns an array of all of the bookmarks belonging to the specified user.
  * @param int $userID
  * @param int $projectID if set, it will only retrieve bookmarks from that specified project
  * @return array Returns an array of Bookmark objects
  */
  public static function retrieveFromUser($userID, $projectID=FALSE){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT * FROM bookmarks WHERE userID=%d", $userID);
    if($projectID){
      $query .= sprintf(" AND projectID=%d", $projectID);
    }
    $query .= " ORDER BY timestamp DESC";
    $bookmarks = array();
    $results = $cxn->commit($query);
    while($record = mysql_fetch_assoc($results)){
      array_push($bookmarks, $record);
    }
    return $bookmarks;
  }

  public static function retrieveWithTagsFromProject($projectID, $sorting="timestamp DESC"){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT U.username, B.*, GROUP_CONCAT(tags.name SEPARATOR ',') as tagList FROM users U, bookmarks B LEFT JOIN (tag_assignments, tags) ON (B.bookmarkID = tag_assignments.bookmarkID AND tag_assignments.tagID = tags.tagID) WHERE B.projectID=%d AND B.userID=U.userID GROUP BY B.bookmarkID ORDER BY %s", $projectID, $cxn->esc($sorting));
    $bookmarks = array();
    $results = $cxn->commit($query);
    while($record = mysql_fetch_assoc($results)){
      array_push($bookmarks, $record);
    }
    return $bookmarks;
  }

  public static function retrieveFromProjectAndTag($projectID, $tagName, $sorting="timestamp DESC"){
    $cxn=Connection::getInstance();
    $query = sprintf("SELECT U.username, B.* FROM users U, bookmarks B, tag_assignments TA, tags T WHERE B.projectID=%d AND B.userID=U.userID AND T.name='%s' AND T.tagID = TA.tagID AND TA.bookmarkID=B.bookmarkID ORDER BY %s", $projectID, $cxn->esc($tagName), $cxn->esc($sorting));
    $bookmarks = array();
    $results = $cxn->commit($query);
    while($record = mysql_fetch_assoc($results)){
      array_push($bookmarks, $record);
    }
    return $bookmarks;
  }

  /**
  * Deletes bookmark from database.
  * @param int $bookmarkID
  */
  public static function delete($bookmarkID){
    $cxn = Connection::getInstance();
    $query = sprintf("DELETE FROM bookmarks WHERE bookmarkID=%d", $bookmarkID);
    $cxn->commit($query);
    Tags::deleteForBookmark($bookmarkID);
  }


  public static function update($bookmarkID, $notes="", $tags=array(), $useful_info = "", $author_qualifications = ""){
    $cxn = Connection::getInstance();
    $query = sprintf("UPDATE bookmarks SET note='%s', useful_info='%s', author_qualifications='%s' WHERE bookmarkID=%d", $cxn->esc($notes), $cxn->esc($useful_info), $cxn->esc($author_qualifications), $bookmarkID);
    // echo $query;
    $cxn->commit($query);
    $t = new Tags();
    $t->deleteForBookmark($bookmarkID);
    $t->assignTagsToBookmark($bookmarkID, $tags);
  }
}
?>
