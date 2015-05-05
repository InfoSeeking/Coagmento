<?php
require_once('Connection.class.php');
require_once('Base.class.php');
require_once('Action.class.php');
require_once('Util.class.php');

class Tags extends Base{
  public function __construct(){
    parent::__construct();
  }

  public function retrieveFromProject($projectID){
    $cxn = Connection::getInstance();
    $q = "select distinct bookmark_tags.tagID as tagID, bookmark_tags.name as name FROM bookmark_tags INNER JOIN (SELECT tagID,taID FROM tag_assignments INNER JOIN bookmarks on tag_assignments.bookmarkID=bookmarks.bookmarkID AND bookmarks.url NOT LIKE '%coagmento.org/spring2015%' AND bookmarks.url NOT LIKE '%about:blank%' AND bookmarks.url NOT LIKE '%about:home%' AND bookmarks.url NOT LIKE '%about:newtab%' AND bookmarks.url NOT LIKE '%about:addons%') a ON bookmark_tags.tagID = a.tagID WHERE bookmark_tags.projectID='$projectID' ORDER BY bookmark_tags.name";


    $arr_results = array();
    $results = $cxn->commit($q);
    while($row = mysql_fetch_assoc($results)){
      array_push($arr_results, array(
        "tagID" => $row["tagID"],
        "name" => $row["name"]
      ));
    }
    return $arr_results;
  }

  public function retrieveFromBookmark($bookmarkID){
    $cxn = Connection::getInstance();
    $q = sprintf("select T.* FROM bookmark_tags T, tag_assignments TA WHERE TA.bookmarkID=%d AND TA.tagID=T.tagID", $bookmarkID);
    $arr_results = array();
    $results = $cxn->commit($q);
    while($row = mysql_fetch_assoc($results)){
      array_push($arr_results, array(
        "tagID" => $row["tagID"],
        "name" => $row["name"]
      ));
    }
    return $arr_results;
  }

  public function updateTagForProject($projectID, $tagID, $new_name){
    // will be used in CSpace
    $cxn = Connection::getInstance();
  }

  /*
   * @param array $tags An array of strings
   */
  public function assignTagsToBookmark($bookmarkID, $tags){
    if(count($tags) == 0){
      Util::getInstance()->saveAction("ALL TAGS DELETED",$bookmarkID,Base::getInstance());
      return;
    }
    /* By using IGNORE if user is assigning a previously created tag,
     * it will not insert it again
    */
    $q = "INSERT IGNORE INTO bookmark_tags (`projectID`, `name`) VALUES ";
    $arr = array();
    $cxn = Connection::getInstance();
    foreach($tags as $name){
      $ins = sprintf("(%d, '%s')", $this->projectID, $cxn->esc(trim($name)));
      array_push($arr, $ins);
    }
    $q .= implode(",", $arr);
    $cxn->commit($q);

    //now insert into tag_assignments
    $q = "INSERT INTO tag_assignments (`userID`, `bookmarkID`, `tagID`) VALUES ";
    $arr = array();

    $tags_str = array();
    $tag_assignments_str = array();

    foreach($tags as $name){
      $ins = sprintf("(%d, %d, (SELECT tagID FROM bookmark_tags WHERE projectID=%d AND name='%s'))", $this->userID, $bookmarkID, $this->projectID, $cxn->esc($name));
      array_push($arr, $ins);
      array_push($tags_str,$name);
      $r = $cxn->commit("SELECT tagID FROM bookmark_tags WHERE projectID='".$this->projectID."' AND name='".$cxn->esc($name)."'");
      $line = mysql_fetch_array($r,MYSQL_ASSOC);
      array_push($tag_assignments_str,$line['tagID']);
    }
    $q .= implode(",", $arr);
    $cxn->commit($q);



    Util::getInstance()->saveAction("Assining tags TAGS: ".addslashes(implode(",",$tags_str))." TAG_ASSIGNMENTS: ".addslashes(implode(",",$tag_assignments_str))."",$bookmarkID,Base::getInstance());

  }

  public function deleteForBookmark($bookmarkID){
    $cxn = Connection::getInstance();
    //delete all tag_associations
    $query = sprintf("DELETE FROM tag_assignments WHERE bookmarkID=%d", $bookmarkID);
    $cxn->commit($query);
  }
}
