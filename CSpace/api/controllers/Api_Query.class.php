<?php
require_once("../core/Query.class.php");
class Api_Query{
  public static $METHODS = array("delete");
  public static function delete(){
    $id = req("queryID");
    Query::delete($id);
    finish("success");
  }
}
