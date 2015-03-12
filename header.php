<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Coagmento - Connect with Information, Collaborate with People</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="img/coagfavicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/flexslider.css" />
    <link rel="stylesheet" href="css/colors/orange.css" />
    <link rel="stylesheet" href="css/style-responsive.css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
  
  <body>
  <?php include_once("analyticstracking.php") ?>
    
    <div id="main-wrapper" class="container-fluid">

      <!-- Header region -->

       <div id="header-region" class="bg-color-grayDark2 text-color-grayLight1">
        <div class="row-fluid wrapper">
          <div class="span6">
<!--             <div class="block text-center-responsive"><strong>Site under maintenance, so some of the functions won't work. We'll be back soon!</strong></div> -->
          </div>
            <div class="span6 text-right text-center-responsive">
<!--             <section class="loginform cf">   -->
	<?php
		if (isset($_SESSION['CSpace_userID'])) {
				echo "Go to your <a href=\"http://www.coagmento.org/CSpace/index.php?projects=all&objects=all&years=all&months=all&displayMode=timeline&formSubmit=Submit\">CSpace</a>";
		}
		else {
		?>
			<section class="loginform cf">  
				<form name="login" action="login.php" method="post" accept-charset="utf-8">  
    			<ul>  
        			<li> 
        			<input type="text" name="userName" placeholder="Username" required></li>  
        			<li>
       				 <input type="password" name="password" placeholder="Password" required></li>  
       				<li>  
        				<input type="submit" value="Login"></li>  
    				</ul>  
					</form>  
				</section>  
				
<!--
				<form name="login" action="login.php" method="post" accept-charset="utf-8">  
        			<input type="text" name="userName" placeholder="Username" required></li>  
       				 <input type="password" name="password" placeholder="Password" required></li>  
        				<input type="submit" value="Login"></li>  
					</form> 
-->
	<?php
		}
	?> 
<!-- 				</section>   -->
          </div> <!-- /span6 -->

<!--
          <div class="span6 text-right text-center-responsive">
            <section class="loginform cf">  
				<form name="login" action="login.php" method="post" accept-charset="utf-8">  
    			<ul>  
        			<li> 
        			<input type="text" name="userName" placeholder="Username" required></li>  
        			<li>
       				 <input type="password" name="password" placeholder="Password" required></li>  
       				<li>  
        				<input type="submit" value="Login"></li>  
    				</ul>  
					</form>  
				</section>  
          </div> 
-->
        </div> <!-- /wrapper -->
      </div> <!-- /header-region -->
      
      
      <!-- End of Header region -->

    	<header>
    	  <div class="row-fluid wrapper">

    	  <!-- Logo or Site name section -->

    		   <div id="logo" class="span3">
    			  <a href="index.php">
              <img src="img/logo/clogo.png" alt="Home" class="logo" />
            </a>
    		  </div> <!-- /span3 -->

    		<!-- End of Logo or Site name section -->
    		<!-- Main menu navigation section -->

    		  <div id="nav" class="span9">
    			  <div class="navbar">
    				  <div class="navbar-inner">
    					  <div class="container" data-toggle="collapse" data-target=".nav-collapse">
    						  <a class="brand">Menu</a>

    						  <nav class="nav-collapse collapse">
    						    <ul class="nav">
                      <li><a href="index.php">Home</a></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="#">Research<i class="icon-angle-down"></i></a>
                        <ul class="dropdown-menu">
                          <li><a href="publications.php">Publications</a></li>
                          <li><a href="talks.php">Talks</a></li>
                          <li><a href="collaboratory.php">Collaboratory</a></li>
                        </ul>
                      </li>
                      <li><a href="download.php">Download</a></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-target="#">Documentation<i class="icon-angle-down"></i></a>
                        <ul class="dropdown-menu">
                          <li><a href="about.php">About Coagmento</a></li>
                          <li><a href="instructions.php">How to Use</a></li>
<!--                           <li><a href="#">Screenshots</a></li> -->
                          <li><a href="scenarios.php">Scenarios</a></li>
                          <li><a href="videos.php">Video Tutorials</a></li>
                          <li><a href="http://www.coagmento.org/weblog/" target="_blank">Blog</a></li>
                        </ul>
                      </li>
                     <li><a href="contact.php">Contact</a></li>
                    </ul>
    						  </nav>
    					  </div> <!-- /container -->
    				  </div> <!-- /navbar-inner -->
    			  </div> <!-- /navbar -->
    		  </div> <!-- /span9 -->


    		<!-- End of Main menu navigation section -->

    	  </div> <!-- /wrapper -->
    	</header>

      <!-- Top content region -->
      
     
      <!-- End of top content region -->

      