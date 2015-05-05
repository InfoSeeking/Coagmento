<?php
session_start();
require_once("../core/Base.class.php");
require_once("controllers/Api_Bookmark.class.php");
require_once("controllers/Api_Snippet.class.php");
require_once("controllers/Api_Page.class.php");
require_once("controllers/Api_Query.class.php");

$API_CLASSES = array("Bookmark", "Snippet", "Page", "Query");

function finish($status="success", $data=array()){
  $resp = array(
    "status" => $status,
    "data" => $data
  );
  echo json_encode($resp);
  exit();
}

function err($msg=""){
  finish("error", $msg);
}

function req($param){
  if(!isset($_REQUEST[$param])){
    finish("error", "Missing required parameter " . $param);
  }
  return $_REQUEST[$param];
}

function opt($param, $default=null){
  if(!isset($_REQUEST[$param])){
    return $default;
  }
  return $_REQUEST[$param];
}

$base = new Base();
if(!$base->isUserActive()){
  err("Not logged in");
}

$entity = req("entity");
$function = req("function");

$class_found = false;
$method_found = false;

foreach($API_CLASSES as $class){
  echo $class;
  if(strtolower($class) == strtolower($entity)){
    $class_found = $class;
    break;
  }
}

if($class_found){
  $class_name = "Api_" . $class_found;
  foreach($class_name::$METHODS as $method){
    if(strtolower($method) == strtolower($function)){
      $method_found = $method;
      break;
    }
  }

  if($method_found){
    $class_name::$method();
  } else {
    err($function . " is not a valid function");
  }
} else {
  err($entity . " is not a valid entity");
}
