<?php
require_once("../core/Bookmark.class.php");
class Api_Bookmark{
  public static $METHODS = array("delete", "update");
  public static function delete(){
    $id = req("bookmarkID");
    Bookmark::delete($id);
    finish("success");
  }
  public static function update(){
    $id = req("bookmarkID");
    $notes = opt("notes", "");
    $tags = opt("tags", array());
    Bookmark::update($id, $notes, $tags);
    finish("success");
  }
}
