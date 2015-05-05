<?php
session_start();
require_once("../core/Base.class.php");
$base = Base::getInstance();
echo $base->getUserID();
$base->selectProject($_GET["value"]);
//redirect
header( 'Location: index.php' ) ;
