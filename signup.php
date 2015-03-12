<?php
	require_once("header.php");
?>

    	<!-- Content region -->

    	<div id="main-content">
    	  
    	  <div class="row-fluid wrapper">
    	    <div class="span12">
    	      <h1 class="page-title">Sign Up</h1>
<!--     	      <strong>Note: We are experiencing some problems with signup. Sorry for the inconvenience. We'll be back soon.</strong> -->
    	    </div>
    	  </div>
<?php
	require_once("connect.php");
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
	$date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	$ip=$_SERVER['REMOTE_ADDR'];

	if (isset($_POST['userName']) && isset($_POST['password']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['email'])) {
//		require_once("randstring.php");
		$userName = urlencode($_POST['userName']);
		$tPassword = $_POST['password'];
		$password = sha1($tPassword);
		$firstName = urlencode($_POST['firstName']);
		$lastName = urlencode($_POST['lastName']);
		$email = $_POST['email'];
		$hear = urlencode($_POST['hear']);
		$code = $_POST['code'];

		if ((strlen($userName)>20) || (strlen($firstName)>20)) {
			echo "<table width=80%><tr><td colspan=2><font color=\"red\">Error: Please choose a shorter string.</font><br/></td></tr></table>\n";
		}
		else {
			$query = "SELECT * FROM users WHERE username='$userName' AND active=1";
			$results = mysql_query($query) or die(" ". mysql_error());
			if (mysql_num_rows($results)==0) {					
				$query = "INSERT INTO users VALUES('','$userName','$password','$firstName','$lastName','','$email','','$code','1','$date','','','0','-1','male1.gif','','0','0','$hear','Beta 4 tester')";
				$results = mysql_query($query) or die(" ". mysql_error());
				$query = "SELECT max(userID) as num FROM users";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);	
				$userID = $line['num'];
						
				$query = "INSERT INTO projects VALUES ('','Default','Default project','$date','$time','1','1')";
				$results = mysql_query($query) or die(" ". mysql_error());
				$query = "SELECT max(projectID) as num FROM projects";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);	
				$projectID = $line['num'];
				
				$query = "INSERT INTO memberships VALUES ('','$projectID','$userID','1')";
				$results = mysql_query($query) or die(" ". mysql_error());
				
				// Create an email
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: Coagmento Support <support@coagmento.org>' . "\r\n";
			
				$subject = 'Welcome to Coagmento!';
				$message = "Hello <strong>$firstName $lastName</strong>,<br/><br/>We are excited to welcome you to Coagmento - Latin for &ldquo;connect&rdquo;. Coagmento is a free service to let you keep track of your online browsing, collect useful information in an effective way, and connect with friends and co-workers without leaving your browser! Please visit <a href=\"http://www.coagmento.org/\">Coagmento website</a> to login with the following credentials.<br/><br/>Username: $userName<br/>Password: $tPassword<br/><br/>From Coagmento website, you can download a plug-ins for Firefox or Chrome browsers, access your profile to change your password and profile picture, and learn more about the functionalities that Coagmento offers. Before you get started using Coagmento, you may want to watch some of the tutorials on the Coagmento website.<br/><br/>Coagmento is constantly evolving and you will keep finding more features and enhancements as we keep developing. We love to hear from you, so do drop us a line (or more) through the feedback form available on the website telling how you are using Coagmento and what more you would like to see in the next version!<br/><br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">Connect with Information, Collaborate with People</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>p.s. Don't forget to invite your friends to use Coagmento!<br/>\n";
				mail ($email, $subject, $message, $headers);
				mail ('chirags@rutgers.edu', $subject, $message, $headers);
				
				echo "<table width=80%><tr><td colspan=2><br/>Thank you for signing up with Coagmento. You can now login from <a href=\"index.php\">here</a>.</td></tr>\n";
				echo "<tr><td colspan=2>Once you login, you will be directed to your CSpace, where you can access your profile to change your password and profile picture, and learn more about the functionalities that Coagmento offers. Make sure you <a href=\"download.php\">download</a> browser plugins and mobile apps to make the most out of Coagmento.</td></tr>\n";
	/* 			echo "<tr><td colspan=2>Once you login to Coagmento, you can enroll as an official beta tester to win great prizes!</td></tr>\n"; */
				echo "<tr><td colspan=2><br/>At any time you can watch the <a href=\"tutorials.php\">tutorials</a> showing you how to install and use Coagmento.</td></tr>\n</table>\n";
			}
			else {
				echo "<table width=80%><tr><td colspan=2><font color=\"red\">Error: this username is already taken. Please choose a different one.</font><br/></td></tr></table>\n";
?>

            <div id="contact-form-block" class="block">
              
              <form action="signup.php" method="post" id="contact-site-form" accept-charset="UTF-8" />
                <div class="form-item">
                  <label for="name">Username <small> (required)</small></label>
                  <input class="large-input" type="text" id="userName" name="userName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="mail">Password<small> (required)</small></label>
                  <input class="large-input" type="password" id="password" name="password" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->

                <div class="form-item">
                  <label for="subject">First name<small> (required)</small></label>
                  <input class="large-input" type="text" id="firstName" name="firstName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="subject">Last name<small> (required)</small></label>
                  <input class="large-input" type="text" id="lastName" name="lastName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="subject">Email<small> (required)</small></label>
                  <input class="large-input" type="text" id="email" name="email" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->

                <div class="form-item">
                  <label for="message">How did you hear about Coagmento?</label>
                  <textarea id="hear" name="hear" cols="60" rows="5" class="input-xxlarge"></textarea>
                </div> <!-- /form-item -->

                <div class="form-actions" id="edit-actions">
                  <input type="hidden" id="code" value="<?php echo $code;?>" />
                  <input class="btn btn-primary form-submit" type="submit" id="submit" name="submit" value="Sign Up" />
                </div>
              </form>
              
            </div> <!-- /contact-form-block -->
        
<?php
			}
		}
	}
	else {
		$code = "";
		if (isset($_GET['code'])) {
			$code = $_GET['code'];
			$query = "SELECT * FROM invitations WHERE code='$code'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);	
			$userID = $line['userID'];
			$email = $line['email'];
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);	
			$hear = $line['firstName'] . " " . $line['lastName'];	
			$aQuery = "INSERT INTO actions VALUES('','$userID','0','$timestamp','$date','$time','join-coagmento','$code','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());	
		}
?>
    	  
    	  <div class="row-fluid wrapper">
    	    
    	    <!-- Sidebar First -->
    	    
    	    
    	    <!-- End of Sidebar First -->

    	    <!-- Main content -->

    		  <div id="contact-content-region" class="span6">
    		    
    		    <div id="contact-content-block" class="block">
    		      <p>Coagmento is a unique system that lets you work on multi-session and/or multi-user projects without ever learning your browser!</p>
    		      <p>Please enter the details below to join Coagmento for FREE. By signing up for an account, you are agreeing to our <a href="terms.php">Term & Conditions</a>.</p>
    		    </div> <!-- /contact-content-block -->

            <div id="contact-form-messages-block" class="block">
              
                            
            </div> <!-- /contact-form-messages-block -->
            
            <div id="contact-form-block" class="block">
              
              <form action="signup.php" method="post" id="contact-site-form" accept-charset="UTF-8" />
                <div class="form-item">
                  <label for="name">Username <small> (required)</small></label>
                  <input class="large-input" type="text" id="userName" name="userName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="mail">Password<small> (required)</small></label>
                  <input class="large-input" type="password" id="password" name="password" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->

                <div class="form-item">
                  <label for="subject">First name<small> (required)</small></label>
                  <input class="large-input" type="text" id="firstName" name="firstName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="subject">Last name<small> (required)</small></label>
                  <input class="large-input" type="text" id="lastName" name="lastName" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="subject">Email<small> (required)</small></label>
                  <input class="large-input" type="text" id="email" name="email" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->

                <div class="form-item">
                  <label for="message">How did you hear about Coagmento?</label>
                  <textarea id="hear" name="hear" cols="60" rows="5" class="input-xxlarge"></textarea>
                </div> <!-- /form-item -->

                <div class="form-actions" id="edit-actions">
                  <input type="hidden" id="code" value="<?php echo $code;?>" />
                  <input class="btn btn-primary form-submit" type="submit" id="submit" name="submit" value="Sign Up" />
                </div>
              </form>
              
            </div> <!-- /contact-form-block -->
            
    		  </div> <!-- /content-region -->
    		  
    		  <!-- End of the main content -->
    		  
    		  <!-- Sidebar Second -->
    	    
    	   
    	    
    	    <!-- End of Sidebar First -->		  

    		</div> <!-- /wrapper -->
    	</div> <!-- /main-content -->

    	<!-- End of Content region -->

<?php
}
	require_once("footer.php");
?>
