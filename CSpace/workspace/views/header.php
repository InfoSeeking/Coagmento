<nav class="navbar navbar-default navbar-fixed" style="background-color:#7eb3dd">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->

    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>

      <a class="navbar-brand" href="index.php">
        <img alt="Coagmento" src="assets/img/clogo.png" height="100%" width="">
      </a>

    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="createProject.php">Create Project</a></li>


        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">My Project <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li class="divider"></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>

        <li>

          <a href="etherpad.php" ><span class="glyphicon glyphicon-edit" id="logIcon" style="font-size:20px"></span></a>
          <!-- <a href="etherpad.php" style="padding-top: 0;padding-bottom: 0;"><img alt="Edit" height="50"  src="../assets/img/edit_trans.png"></a> -->
          </li>
        <li>
          <a href="files.php" ><span class="glyphicon glyphicon-folder-open" id="logIcon" style="font-size:20px"></span></a>
          <!-- <a href="files.php" style="padding-top: 0;padding-bottom: 0;"><img height="50" alt="Files" src="../assets/img/files_trans.png"></a> -->
          </li>
        <li>
          <a href="printreport.php" ><span class="glyphicon glyphicon-print" id="logIcon" style="font-size:20px"></span></a>
          <!-- <a href="printreport.php" style="padding-top: 0;padding-bottom: 0;"><img alt="Print" height="50"  src="../assets/img/print_trans.png" /></a> -->
          </li>
        <li>
          <a href="currentCollaborators.php" ><span class="glyphicon glyphicon-sort" id="logIcon" style="font-size:20px"></span></a>
          <!-- <a href="currentCollaborators.php" style="padding-top: 0;padding-bottom: 0;"><img height="50"  alt="Collaborators" src="../assets/img/updates_trans.png"></a> -->
          </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown callout">

          <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user" id="logIcon" style="font-size:20px"></span> <span class="caret"></span></a>

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
            <li><a href="#">Contact Us</a></li>
            <li class="divider"></li>
            <li><a href="http://coagmento.org/download.php">Get Toolbar</a></li>
            <li><a href="#">Recommendation</a></li>
            <li><a href="interProject.php">Inter-Project</a></li>
          </ul>
        </li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>



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
