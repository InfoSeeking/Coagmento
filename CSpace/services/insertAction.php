<?php
        function insertAction($action, $value)
        {
            require_once('../core/Base.class.php');
            require_once("../core/Connection.class.php");
            require_once("../core/Util.class.php");
            $base = Base::getInstance();
            $connection = Connection::getInstance();

            date_default_timezone_set('America/New_York');
            $timestamp = time();
            $datetime = getdate();
            $date = date('Y-m-d', $datetime[0]);
            $time = date('H:i:s', $datetime[0]);
            $projectID = $_SESSION['CSpace_projectID'];
            $userID = $_SESSION['CSpace_userID'];
            $ip=$base->getIP();
            Util::getInstance()->saveAction("$action","$value",$base);
	}
?>
