<<<<<<< HEAD
<?php
session_start();
require_once('../core/Connection.class.php');
require_once('../core/Base.class.php');
$base = Base::getInstance();
$connection = Connection::getInstance();
$avatar = $line['avatar'];
$avatar = '';
if (isset($_SESSION['CSpace_userID'])){
  $userID = $base->getUserID();
  $query = "SELECT * FROM users WHERE userID='$userID'";
  $results = $connection->commit($query);
  $line = mysqli_fetch_array($results, MYSQL_ASSOC);
  $avatar = $line['avatar'];
}

?>

<nav class="navbar navbar-default navbar-fixed-top" style="background-color:#7eb3dd;border-bottom:4px black groove">
=======
<nav class="navbar navbar-default navbar-fixed-top" style="background-color:#7eb3dd;border-bottom:3px black groove">
>>>>>>> origin/master
  <div class="container-fluid">

    <!-- Brand and toggle get grouped for better mobile display -->


    <div class="navbar-header" style="border-right:1px white solid; height:55px; margin-top:2px; margin-bottom:2px; margin-right: 5px;">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" style="padding: 2px 10px;" href="index.php"><img alt="Coagmento" src="assets/img/clogo.png"></a>

    </div>


    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a class="btn" href="createProject.php" style="color:white; background-color:#3399BE;padding-top:5px;padding-bottom:5px;margin-top:10px;margin-left:5px;padding-left:8px;padding-right:8px">Create Project</a></li>


        <?php
        $curr_title = 'Default';
        $project_results = $base->getAllProjects();
        while($row = mysqli_fetch_assoc($project_results)) {
          if ($row["projectID"] == $base->getProjectID()){
            $curr_title = $row['title'];
          }
        }

        ?>
        <li class="dropdown">
<<<<<<< HEAD
          <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"
                                            style="color:white; background-color:#3399BE;padding-top:5px;padding-bottom:5px;margin-top:10px;margin-left:5px;padding-left:8px;padding-right:8px">
                                            <?php echo $curr_title; ?>
                                            <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">

            <?php

            $project_results = $base->getAllProjects();
            while($row = mysqli_fetch_assoc($project_results)) {
              printf("<li><a href=\"%s\">%s</a></li>", "selectProject.php?value=" . $row['projectID'], $row["title"]);
            }

            ?>

            <!-- <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li class="divider"></li>
            <li><a href="#">One more separated link</a></li> -->
=======


            <?php
            require_once("../core/Base.class.php");
            $base = Base::getInstance();
            $project_results = $base->getAllProjects();
            $items = "";
            $selected = "Select a Project";
            while($row = mysqli_fetch_assoc($project_results)) {
              if($row["projectID"] == $base->getSelectedProject()){
                $selected = $row["title"];
              }
              $items .=  sprintf("<li><a href='%s'>%s</a></li>", "selectProject.php?value=" . $row['projectID'], $row["title"]);
            }
            ?>
            <a href="#" class="btn dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="color:white; background-color:#0000CB;padding-top:5px;padding-bottom:5px;margin-top:10px;margin-left:5px;padding-left:8px;padding-right:8px">

            <?php echo $selected; ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
            <?php echo $items; ?>

>>>>>>> origin/master
          </ul>
        </li>

        <li>

          <a href="etherpad.php" title="Project etherpad"><span class="glyphicon glyphicon-edit"  style="font-size:25px; color:white"></span></a>
          <!-- <a href="etherpad.php" style="padding-top: 0;padding-bottom: 0;"><img alt="Edit" height="50"  src="../assets/img/edit_trans.png"></a> -->
          </li>
        <li>
          <a href="files.php" title="Project files"><span class="glyphicon glyphicon-folder-open"  style="font-size:25px; color:white"></span></a>
          <!-- <a href="files.php" style="padding-top: 0;padding-bottom: 0;"><img height="50" alt="Files" src="../assets/img/files_trans.png"></a> -->
          </li>
        <li>
          <a href="printreport.php" title="Print project reports"><span class="glyphicon glyphicon-print" style="font-size:25px; color:white"></span></a>
          <!-- <a href="printreport.php" style="padding-top: 0;padding-bottom: 0;"><img alt="Print" height="50"  src="../assets/img/print_trans.png" /></a> -->
          </li>
        <li>
          <a href="currentCollaborators.php" title="View project collaborators"><i class="fa fa-group" style="font-size:25px; color:white"></i></a>
          <!-- <a href="currentCollaborators.php" style="padding-top: 0;padding-bottom: 0;"><img height="50"  alt="Collaborators" src="../assets/img/updates_trans.png"></a> -->
          </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown callout">

          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false">


            <i  id="user_default" class="fa fa-user" style="font-size:25px">

          </i>
          <?php
          echo "<img src=\"../assets/img/$avatar\" onerror=\"this.style.display='none';\" onload=\"document.getElementById('user_default').style.display='none';\" style=\"width:36px;height:36px;border-radius:18px; -webkit-border-radius:18px; -moz-border-radius:18px;\" />";
          ?>

            <!-- <i class="fa fa-user" style="font-size:25px"></i>  -->
            <span class="caret"></span></a>

          <!-- <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false" style="padding-top: 0;padding-bottom: 0;"><img height="50" alt="Pic" style="border-radius:100%" src="../assets/img/profile_trans.png" /> <span class="caret"></span></a> -->
          <ul class="dropdown-menu" role="menu">
            <li><a href="profile.php">Profile</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="workspace-logout.php?redirect=index.php">Logout</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-option-horizontal" id="logIcon" style="font-size:20px"></span> <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="help.php">Help</a></li>
            <li class="divider"></li>
            <li><a href="http://coagmento.org/download.php">Get Toolbar</a></li>
            <li><a href="showRecommendations.php">Recommendations</a></li>
            <li><a href="interProject.php">Inter-Project</a></li>
          </ul>
        </li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
  <?php if(isset($PAGE)): ?>
  <div class="feed-links clear">
    <ul>
      <li><a class="<?php if($PAGE == 'ALL') echo 'current ' ?>" href="?page=ALL">All</a></li>
      <li><a class="<?php if($PAGE == 'PAGE_VISITS') echo 'current ' ?>" href="?page=PAGE_VISITS">Page Visits</a></li>
      <li><a class="<?php if($PAGE == 'BOOKMARKS') echo 'current ' ?>" href="?page=BOOKMARKS">Bookmarks</a></li>
      <li><a class="<?php if($PAGE == 'SNIPPETS') echo 'current ' ?>" href="?page=SNIPPETS">Snippets</a></li>
      <li><a class="<?php if($PAGE == 'SEARCHES') echo 'current ' ?>" href="?page=SEARCHES">Search History</a></li>
    </ul>
  </div> <!-- /.feed-links -->
  <?php endif; ?>
</nav>
<<<<<<< HEAD

<!--TIMELINE-->

<!-- <div class="container">
	<h2 class="title text-center" >Today</h2>
	<ul class="timeline">
		<li class="year">2015</li>
		<li class="event">
			<h3 class="heading">Yahoo Live Updates!</h3>
			<span class="month"><i class="fa fa-calendar"></i> &nbsp; May 2015</span>

			<p>&nbsp;</p>
			<p>Added By : <a href="m" target="_blank">m</a></p>
      <p>Date : <a href="#" target="_blank">5/12/2015</a></p>
			<p>Time : <a href="#" target="_blank">12:34pm</a></p>
			<div class="text-center">
				<img class="img-responsive img-thumbnail" src="assets/img/thumbnail1.png" style="width:200px">
			</div>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
		<li class="event">
			<h3 class="heading">Yahoo News!</h3>
			<span class="month"><i class="fa fa-calendar"></i> &nbsp; May 2015 </span>
			<p>&nbsp;</p>
      <div class="text-center">
				<img class="img-responsive img-thumbnail" src="assets/img/thumbnail1.png" style="width:200px">
			</div>
      <p>Added By : <a href="m" target="_blank">m</a></p>
      <p>Date : <a href="#" target="_blank">5/13/2015</a></p>
			<p>Time : <a href="#" target="_blank">1:34am</a></p>

			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
		</li>
	</ul>
</div> -->


<!-- <div id="header_container">
  <header class="page_header">
    <hgroup class='left-side'>
      <a href="index.php"><img src="assets/img/clogo.png" alt="Coagmento Logo" /></a>
    </hgroup>
    <div class='right-side links'>
      <a href="help.php">Help</a><br/>
      <a href="settings.php">Settings</a><br/>
      <a href="workspace-logout.php?redirect=index.php">Logout</a><br/>
    </div>
    <nav class='clear'>
      <ul>
        <li><a class="" href="index.php">&laquo; Back to Workspace</a></li>
      </ul>
    </nav>
  </header>
</div> -->
=======
>>>>>>> origin/master
