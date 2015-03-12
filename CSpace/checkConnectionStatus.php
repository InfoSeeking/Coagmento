<?php
//	session_name('XULSession'); // Set session name
	session_start();
        if (isset($_SESSION['CSpace_userID']))
            $connected = 1;
	else
            $connected = -1;

        echo "$connected";

?>
