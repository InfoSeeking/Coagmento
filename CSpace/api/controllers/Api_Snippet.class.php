<?php
require_once("../core/Snippet.class.php");
class Api_Snippet{
  public static $METHODS = array("delete");
  public static function delete(){
    $id = req("snippetID");
    Snippet::delete($id);
    finish("success");
  }
}
