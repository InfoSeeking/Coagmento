<?php
require_once("../core/Page.class.php");
class Api_Page{
  public static $METHODS = array("delete");
  public static function delete(){
    $id = req("pageID");
    Page::delete($id);
    finish("success");
  }
  public static function get($start=0, $count=200){
    
  }
}
