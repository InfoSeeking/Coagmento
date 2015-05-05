<?php
/*
Simpler login page for workspace. Allows redirect.
Maybe we can use this as a replacement for the sidebar login as well.
*/
	session_start();
  require_once('core/Connection.class.php');
	require_once("core/Base.class.php");
	require_once("core/Util.class.php");
	$base = Base::getInstance();
  $feedback = "";

  function onLogin(){
    global $feedback;
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    $error = false;
    $connection = Connection::getInstance();
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $results = $connection->commit($query);
    if(mysql_num_rows($results) == 0){
      $feedback = "Incorrect username/password, please try again";
      return;
    }
    $row = mysql_fetch_array($results, MYSQL_ASSOC);
    $userID = $row['userID'];
    $base = Base::getInstance();
    $base->setUserName($username);
    $base->setUserID($userID);
    //$base->setProjectID(0); - IDK how to set to current project
    $base->setStageID(-1);
		$base->setAllowCommunication(1);
		$base->setAllowBrowsing(1);
    Util::getInstance()->saveAction('login',0,$base);
    if(isset($_GET['redirect'])){
      header("Location: ".  $_GET['redirect']);
    }
  }

  if(isset($_POST['action']) && $_POST['action'] == 'login'){
    onLogin();
  }
?>
<html>
  <head>
    <style type="text/css">
			*{
				margin: 0;
				padding: 0;
			}
      .feedback{
        background:#EEE;
        padding: 5px 10px;
      }
      #container{
        width: 325px;
        margin: 10px auto;
      }
      .row label{
        display: inline-block;
        width: 100px;
      }
      .row, .feedback{
        margin-bottom: 10px;
			}
			h3{
				font-family: "Arial";
				font-size: 16px;
				font-weight: normal;
			}
			h3 span{
				position: relative;
				top: -12px;
				left: 10px;
			}
			#header_container{
			  background: #7eb3dd;
			  border-bottom: 2px #1B77E0 solid;
			}
			.page_header{
			  width: 325px;
			  margin: 0px auto;
				color: #FFF;
			  position: relative;
			}
    </style>
		<!-- <link type="text/css" rel="stylesheet" href="study_styles/pure-release-0.5.0/buttons.css" /> -->
  </head>
  <body>

		<div id="header_container">
			<header class="page_header">
				<h3><img src="workspace/assets/img/clogo.png" alt="Coagmento Logo" /><span>Log in</span></h3>
			</header>
		</div>
    <div id="container">
      <?php if($feedback != ""): ?>
        <p class="feedback"><?php echo $feedback; ?></p>
      <?php endif; ?>
      <form action="#" method="post">
        <div class="row">
          <label for="username">Username</label><input type="text" id="username" name="username" />
        </div>
        <div class="row">
          <label for="password">Password</label><input type="password" id="password" name="password" />
        </div>
        <input type="hidden" name="action" value="login" />
        <div class="row">
          <input type="submit" class="pure-button pure-button-primary" value="Log in" />
        </div>
      </form>
    </div>
  </body>
<html>
