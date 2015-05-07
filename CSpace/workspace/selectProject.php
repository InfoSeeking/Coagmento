<?php
session_start();
require_once("../core/Base.class.php");
$base = Base::getInstance();
if(!isset($_GET["value"])){
  die("No value passed");
}
$base->selectProject(intval($_GET["value"]));
//redirect
header( 'Location: index.php' ) ;
