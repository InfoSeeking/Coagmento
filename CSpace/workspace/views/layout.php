<?php
function gen_url($param){
  global $PAGE, $sorting, $sorting_order, $current_tag, $only_mine, $hide_pages;
  $defaults = array(
  "page" => $PAGE,
  "bookmark_tag_filter" => $current_tag,
  "sorting" => $sorting,
  "sorting_order" => $sorting_order,
  "only_mine" => $only_mine,
  "hide_pages" => $hide_pages
  );
  $param = array_merge($defaults, $param);
  return "?" . http_build_query($param);
}

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
<html>
  <head>
    <title>Coagmento Workspace</title>
    <link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
    <style></style>
    <link href="../assets/select2/select2.css" rel="stylesheet" type="text/css" />
    <link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap-flat-extras.css" rel="stylesheet" />
    <link type="text/css" href="assets/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" />
    <script src="assets/js/jquery-2.1.3.min.js"></script>
    <script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<<<<<<< HEAD

=======
>>>>>>> origin/master
  </head>
  <body class="pg_<?php echo $PAGE ?>" style="padding-top: 89px">
    <!--
    <div id="header_container">
      <header class="page_header">



        <nav class="navbar navbar-default navbar-fixed-top" style="margin-bottom:0px !important;background-color:#7eb3dd;border-bottom:4px black groove">
          <div class="container-fluid" >

            <!-- Brand and toggle get grouped for better mobile display -->


            <div class="navbar-header" style="border-right:2px white solid; height:55px; margin-top:2px; margin-bottom:2px;">

              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>



              <a class="navbar-brand" href="index.php" style="padding-top:0">
                <img alt="Coagmento" src="assets/img/clogo.png" style="height:50px"></a>

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
                    <li><a href="#">Contact Us</a></li>
                    <li class="divider"></li>
                    <li><a href="http://coagmento.org/download.php">Get Toolbar</a></li>
                    <li><a href="#">Recommendation</a></li>
                    <li><a href="interProject.php">Inter-Project</a></li>
                  </ul>
                </li>

              </ul>
            </div><!-- /.navbar-collapse -->

            <div class='clear'>
            <ul class="nav nav-tabs"style="margin-left:80px">
              <li><a class="tabnav <?php if($PAGE == 'ALL') echo 'current ' ?>" href="?page=ALL">All</a></li>
              <li><a class="tabnav <?php if($PAGE == 'PAGE_VISITS') echo 'current ' ?>" href="?page=PAGE_VISITS">Page Visits</a></li>
              <li><a class="tabnav <?php if($PAGE == 'BOOKMARKS') echo 'current ' ?>" href="?page=BOOKMARKS">Bookmarks</a></li>
              <li><a class="tabnav <?php if($PAGE == 'SNIPPETS') echo 'current ' ?>" href="?page=SNIPPETS">Snippets</a></li>
              <li><a class="tabnav <?php if($PAGE == 'SEARCHES') echo 'current ' ?>" href="?page=SEARCHES">Search History</a></li>
              <!-- <li><a class="<?php if($PAGE == 'SOURCES') echo 'current ' ?>" href="?page=SOURCES">Sources</a></li> -->
              <!-- <li><a class="<?php if($PAGE == 'CONTRIBUTIONS') echo 'current ' ?>" href="?page=CONTRIBUTIONS">User Contributions</a></li> -->
            </ul>
          </div>
        </nav>


        <!-- <hgroup class='left-side'>
          <a href="index.php"><img src="assets/img/clogo.png" alt="Coagmento Logo" /></a>
        </hgroup>
        <div class='middle-side project_section'>
          <label>Select a project</label>
          <select id="project_selection">
            <?php
            $base = Base::getInstance();
            $project_results = $base->getAllProjects();
            while($row = mysqli_fetch_assoc($project_results)) {
              $extra = "";
              if($row["projectID"] == $projectID){
                $extra = "selected";
              }
              printf("<option data-url='%s' %s>%s</option>", "selectProject.php?value=" . $row['projectID'], $extra, $row["title"]);
            }
            ?>
          </select>
        </div>
        <div class='right-side links'>
          <a href="help.php">Help</a><br/>
          <a href="settings.php">Settings</a><br/>
          <a href="workspace-logout.php?redirect=index.php">Logout</a><br/>
        </div>
        <nav class='clear'>
          <ul>
            <li><a class="<?php if($PAGE == 'ALL') echo 'current ' ?>" href="?page=ALL">All</a></li>
            <li><a class="<?php if($PAGE == 'PAGE_VISITS') echo 'current ' ?>" href="?page=PAGE_VISITS">Page Visits</a></li>
            <li><a class="<?php if($PAGE == 'BOOKMARKS') echo 'current ' ?>" href="?page=BOOKMARKS">Bookmarks</a></li>
            <li><a class="<?php if($PAGE == 'SNIPPETS') echo 'current ' ?>" href="?page=SNIPPETS">Snippets</a></li>
            <li><a class="<?php if($PAGE == 'SEARCHES') echo 'current ' ?>" href="?page=SEARCHES">Search History</a></li>
<<<<<<< HEAD
            <!-- <li><a class="<?php if($PAGE == 'SOURCES') echo 'current ' ?>" href="?page=SOURCES">Sources</a></li> -->
            <!-- <li><a class="<?php if($PAGE == 'CONTRIBUTIONS') echo 'current ' ?>" href="?page=CONTRIBUTIONS">User Contributions</a></li> -->
        <!-- </ul>
        </nav> -->
=======
          </ul>
        </nav>
>>>>>>> origin/master
      </header>
    </div>
  -->
    <?php require_once("header.php"); ?>

    <div id="container" class="container" >

      <div class="sidebar_col">
        <?php require_once("views/aside.php"); ?>
      </div>
      <div class="feed_col">
        <div class="scroller">
          <div class="welcome">
            <p>Welcome, <?php echo $username ?>!</p>
          </div>
          <?php
          if($projectID == -1):
          ?>
          Select a project to begin.
          <?php
          endif;
          ?>
          <ul id="feed"></ul>
        </div>
      </div>
      <div class="review_col">
          <h3>Workspace</h3>
          <div class="no_selections">
            <p>Select items to review and analyze</p>
            <p>Click a thumbnail to see the full image</p>
          </div>
          <div class="enlarged_thumbnail">
            <div class="close_thumbnail">Close</div>
            <img src="" />
          </div>
          <div class="some_selections">
            <h4>Review your Selections</h4>
            <a href="#" class="close_selections">Clear Selections</a>
            <ul class="selection_list">
              <li></li>
            </ul>
            <h4>Analyze with IRIS <img width="70" src="../assets/img/poweredbyIRIS.png" style="position:relative;top:10px" /></h4>

            <div class="clustering">
              <h5>Cluster Pages</h5>
              <p>Cluster into <input class="cluster_num" type="num" value="3" size="2" maxlength="3" /> clusters</p>
              <button class="cluster_btn">Go</button>
              <div class="cluster_results">
              </div>
            </div>
          </div>
      </div>

      <br class="clear" />
    </div>
    <script type="text/html" id="bookmark_template">
      <li data-bookmarkID="TODO" data-lunr="<%= lunr_id %>" class="item-<%= label.toLowerCase() %>">
        <div class="top">
          <div class="left_section">
            <div class="thumbnail_container" data-src="../thumbnails/<%= fileName %>">
              <div class="thumbnail_message">Click to view</div>
              <img src="../thumbnails/small/<%= fileName %>" width="120" class="thumbnail" />
            </div>
            <div>
              <input type="checkbox" class="feed_selection" data-title="<%= title %>" data-url="<%= url %>" id="ck_<%= lunr_id %>" /> <label for="ck_<%= lunr_id %>" >Select</label>
            </div>
          </div>
          <div class="right_section">

            <div>
              <span class="label <%= label.toLowerCase() %>"> <%= label %> </span>
              <span><a class="bookmark_link" target="_blank" href="<%= url %>"><%= title %></a></span>
              <% if(tags.length > 0){ %>
                <div class="tagList">
                <b>Tags:</b>
                <% for(var i = 0; i < tags.length; i++){ %>
                  <span class="tag"><%= tags[i] %></span>
                <% } %>
                </div>
              <% } %>
            </div>

            <% if(note) { %>
            <p><b>Notes:</b> <%= note %> </p>
            <% } %>
            <% if(rating > 0) { %>
              <p><b>Rating:</b>
                <span class="rating">
                  <% for(var i = 0; i < rating; i++){ %>
                    <img src="assets/img/star_filled.png" />
                  <% } %>
                  <% for(var i = rating; i < 5; i++){ %>
                    <img src="assets/img/star_unfilled.png" />
                  <% } %>
                </span></p>
            <% } %>
          </div><!-- /right-section -->
          <br class="clear" />
        </div><!--/top-->
        <div class="sub">
          <span class="added_by">Added by <b><%= username %></b></span>
          <span class="date"><%= pretty_date %></span>
          <span class="real_date">(<%= real_date %>)</span>

          <a href="#" class="bookmark-related">See related snippets</a>

          <% if(editable){ %>
          <div class="sub-right">
            <!-- <a href="#" class="delete" data-id="TODO">Delete</a> -->
            <!-- <a href="#" class="edit" data-state="closed">Edit</a> -->
          </div>
          <% } %>
        </div>
        <div class="bookmark-related-section">
          <h4>Related Snippets</h4>
          <div class="bookmark-snippets"></div>
        </div>
        <% if(editable){ %>
        <div class="more">
          <form>
            <p class="feedback"></p>
            <label>Tags (add tags with a comma)</label>
            <div class="row">
              <select name="tags" multiple="multiple" class="tag-input">
                <% for(var i = 0; i < tags.length; i++){ %>
                  <option selected value="<%= tags[i] %>"><%= tags[i] %></span>
                <% } %>
              </select>
            </div>
            <div class="row">
              <label>Notes</label><br/>
              <textarea name="note"><%= note %></textarea>
            </div>
            <a href="#" class="save" data-id="TODO">Save Changes</a>
          </form>
        </div>
        <% } %>
      </li>
    </script>
    <script type="text/html" id="page_template">
      <li data-lunr="<%= lunr_id %>" class="item-<%= label.toLowerCase() %>">
        <div class="top">
          <div class="left_section">
            <div class="thumbnail_container" data-src="../thumbnails/<%= fileName %>">
              <div class="thumbnail_message">Click to view</div>
              <img src="../thumbnails/small/<%= fileName %>" width="120" class="thumbnail" />
            </div>
            <div>
              <input type="checkbox" class="feed_selection" data-title="<%= title %>" data-url="<%= url %>" id="ck_<%= lunr_id %>" /> <label for="ck_<%= lunr_id %>" >Select</label>
            </div>
          </div>
          <div class="right_section">
            <span class="label <%= label.toLowerCase() %>"> <%= label %> </span>
            <span><a href="<%= url %>"><%= title %></a></span>
            <p><small><%= pretty_url %></small><p>
          </div>
          <br class="clear" />
        </div>
        <div class="sub">
          <span class="added_by">Added by <b><%= username %></b></span>
          <span class="date"><%= pretty_date %></span>
          <span class="real_date">(<%= real_date %>)</span>
          <div class="sub-right">
            <% if(editable){ %>
            <!--<a class="delete" href="#" data-id="<%= pageID %>">Delete</a>-->
            <% } %>
          </div>
        </div>
      </li>
    </script>
    <script type="text/html" id="snippet_template">
      <li data-snippetID="<%= snippetID %>" data-lunr="<%= lunr_id %>" class="item-<%= label.toLowerCase() %>">
        <div class="top">
          <span class="label <%= label.toLowerCase() %>"> <%= label %> </span>
          <a target="_blank" class="snippet_link" href="<%= url %>"><%= title %></a>
          <p class="preview"><%= snippet %></p>
        </div>
        <div class="sub">
          <span class="added_by">Added by <b><%= username %></b></span>
          <span class="date"><%= pretty_date %></span>
          <span class="real_date">(<%= real_date %>)</span>
          <div class="sub-right">
            <% if(editable){ %>
            <!--<a class="delete" href="#" data-id="<%= snippetID %>">Delete</a>-->
            <% } %>
          </div>
        </div>
      </li>
    </script>
    <script type="text/html" id="query_template">
      <li data-queryID="<%= queryID %>" data-lunr="<%= lunr_id %>" class="item-<%= label.toLowerCase() %>">
        <div class="top">
          <span class="label <%= label.toLowerCase() %>"> <%= label %> </span>
          <a class="query_link" target="_blank" href="<%= url %>"><%= query %> (<%= source %>)</a>
        </div>
        <div class="sub">
          <span class="added_by">Added by <b><%= username %></b></span>
          <span class="date"><%= pretty_date %></span>
          <span class="real_date">(<%= real_date %>)</span>
          <div class="sub-right">
            <% if(editable){ %>
            <!--<a class="delete" href="#" data-id="<%= queryID %>">Delete</a>-->
            <% } %>
          </div>
        </div>
      </li>
    </script>
    <script type="text/html" id="source_template">
      <li data-lunr="<%= lunr_id %>" class="item-<%= label.toLowerCase() %>">
        <div class="top">
          <span class="label <%= label.toLowerCase() %>"> <%= label %> </span>
          <span class="source_name"> <%= source %></span>
        </div>
        <div class="sub">
          <a href="#" class="related">See related bookmarks and snippets</a>
        </div>
        <div class="related-section">
          <h4 class="bookmarks_heading">Related Bookmarks</h4>
          <div class="bookmarks">
          </div>
          <h4 class="snippets_heading">Related Snippets</h4>
          <div class="snippets">
          </div>
        </div>
      </li>
    </script>
    <script type="text/html" id="year">
      <h2><%= year %></h2>
    </script>
    <script type="text/html" id="month">
      <h3><%= month %></h3>
    </script>
    <script type="text/html" id="day">
      <h4 class="day"><%= month %> <%= day %>, <%= year %></h4>
    </script>
    <script src="assets/js/jquery-2.1.3.min.js"></script>
    <script src="assets/js/simple_template.js"></script>
    <script src="assets/js/utils.js"></script>
    <script src="assets/js/IRIS.js"></script>
    <script src="assets/js/WORKSPACE.js"></script>
    <script src="assets/js/lunr.js"></script>
    <script type="text/javascript" src="../assets/select2/select2.full.min.js"></script>

    <script>
    (function(){
      <?php
      printf("WORKSPACE.init('%s',%s,%s,%s,%s);", $PAGE,json_encode($feed_data),json_encode($tag_data), $userID, $only_mine?"true":"false");
      ?>

      $(".tag-input").select2({
    		tags: true
    	})

    }());
    </script>
  </body>
</html>
