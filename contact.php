<?php
	require_once("header.php");
?>
      
      <!-- Highlighted region -->
      
      <div id="highlighted-region">

        <div id="google-map-block" class="block">
          <div class="row-fluid">
            <div class="span12">
              <div id="map_canvas" class="google-map"></div>
            </div> <!-- /span12 -->
          </div> <!-- /row-fluid -->
        </div> <!-- /google-map-block -->

      </div> <!-- /highlighted-region -->
      
      <!-- End of Highlighted region -->

    	<!-- Content region -->

    	<div id="main-content">
    	  
    	  <div class="row-fluid wrapper">
    	    <div class="span12">
    	      <h1 class="page-title">Contact</h1>
    	    </div>
    	  </div>
    	  
    	  <div class="row-fluid wrapper">
    	    
    	    <!-- Sidebar First -->
    	    
    	    <div id="sidebar-first-region" class="span3">

            <div id="contact-info-side-block" class="block">
              
              <h2 class="block-title">Contact info</h2>

              <div class="content">
                <strong>InfoSeeking Lab</strong><br />
                4 Huntington St<br />
                New Brunswick, NJ 08901<br /><br />
                <b>Email:</b> <a href="&#109;&#97;&#105;&#108;&#116;&#111;:&#99;&#104;&#105;&#114;&#97;&#103;&#115;&#64;&#114;&#117;&#116;&#103;&#101;&#114;&#115;&#46;&#101;&#100;&#117;" >&#99;&#104;&#105;&#114;&#97;&#103;&#115;&#64;&#114;&#117;&#116;&#103;&#101;&#114;&#115;&#46;&#101;&#100;&#117;</a><br />
              </div> <!-- /content -->
            
            </div> <!-- /contact-info-side-block -->
          
          </div> <!-- /sidebar-first-region -->
    	    
    	    <!-- End of Sidebar First -->

    	    <!-- Main content -->

    		  <div id="contact-content-region" class="span6">

   	<?php
   		require_once("connect.php");
   		date_default_timezone_set('America/New_York');
		$datetime = getdate();
		$date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);
   		if ($_POST['name']) {
   			$name = addslashes($_POST['name']);
   			$email = addslashes($_POST['email']);
   			$message = addslashes($_POST['message']);
   			
   			$query = "INSERT INTO contact VALUES('','$date','$time','$name','$email','$message')";
   			$results = mysql_query($query) or die(" ". mysql_error());

   			echo "Thank you for your message. We will do our best to get back to you with a response if needed.<br/>If you have a support question, please email us directly at <a href=\"mailto:support@coagmento.org\">support@coagmento.org</a>.";
   		}
   		else {
   	?>
    		    
    		    <div id="contact-content-block" class="block">
    		      <p>We would love to hear from you; please drop us a line and we will get back to you soon.</p>
    		    </div> <!-- /contact-content-block -->

            <div id="contact-form-messages-block" class="block">
              
                            
            </div> <!-- /contact-form-messages-block -->
            
            <div id="contact-form-block" class="block">
              
              <form action="contact.php" method="post" id="contact-site-form" accept-charset="UTF-8" />
                <div class="form-item">
                  <label for="name">Your name <small> (required)</small></label>
                  <input class="large-input" type="text" id="name" name="name" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->
                
                <div class="form-item">
                  <label for="mail">Your e-mail address <small> (required)</small></label>
                  <input class="large-input" type="text" id="email" name="email" value="" size="60" maxlength="255" />
                </div> <!-- /form-item -->

                <div class="form-item">
                  <label for="message">Message <small> (required)</small></label>
                  <textarea id="message" name="message" cols="60" rows="5" class="input-xxlarge"></textarea>
                </div> <!-- /form-item -->

                <div class="form-actions" id="edit-actions">
                  <input class="btn btn-primary form-submit" type="submit" id="submit" name="submit" value="Send message" />
                </div>
              </form>
              
            </div> <!-- /contact-form-block -->
            
    		  </div> <!-- /content-region -->
    		  
    		  <!-- End of the main content -->
    		  
    		  <!-- Sidebar Second -->
    	    
    	   
    	    
    	    <!-- End of Sidebar First -->		  
 <?php
 	}
 ?>

    		</div> <!-- /wrapper -->
    	</div> <!-- /main-content -->

    	<!-- End of Content region -->

<?php
	require_once("footer.php");
?>
