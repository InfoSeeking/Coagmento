<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    
    <title>Coagmento 2.0 Announcement</title>
    
	<link rel="icon" type="image/png" href="images/coagfavicon.png" />
    <link href="css/vendor/bootstrap.css" rel="stylesheet" />
	<link href="css/splash.css" rel="stylesheet" />    
    
    <!--     Fonts     -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>
  
</head>

<body>

<!-- Facebook SDK -->
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1638539226407668',
      xfbml      : true,
      version    : 'v2.4'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<nav class="navbar navbar-transparent navbar-fixed-top" role="navigation">  
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      
      <ul class="nav navbar-nav navbar-right">
            <li>
                <a id="facebook_share" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fwww.coagmento.org/new"> 
                    <i class="fa fa-facebook-square"></i>
                    Share
                </a>
            </li>
             <li>
                <a href="https://twitter.com/intent/tweet?status=Check%20out%20the%20next%20version%20of%20Coagmento%3A+http//www.coagmento.org/new"> 
                    <i class="fa fa-twitter"></i>
                    Tweet
                </a>
            </li>
			 <li>
                <a href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://www.coagmento.org/new&title=Coagmento"> 
                    <i class="fa fa-google-plus"></i>
                    Google+
                </a>
            </li>
             <li>
                <a href="https://github.com/InfoSeeking/Coagmento"> 
                    <i class="fa fa-github"></i>
                    Github
                </a>
            </li>
       </ul>
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
<div class="main" style="background-image: url('/images/splash/bg16.jpg')">

<!--    Change the image source '/images/default.jpg' with your favourite image.     -->
    
    
     
<!--   You can change the black color for the filter with those colors: blue, green, red, orange       -->

    <div class="container">

		<h1 class="logo" itemprop="name">
            <img src="images/logo.png" itemprop="image" alt="logo" WIDTH=100 HEIGHT=100>
			COAGMENTO 2.0
        </h1>
		
        <div class="row">
			<div class="col-lg-12 motto">
				<h4>COMING SOON ...</h4>
				<p><i class="fa fa-cog"> Realtime feed of user activity</i></p>
				<p><i class="fa fa-cog"> Simple and open API</i></p>
				<p><i class="fa fa-cog"> Painless study setup</i></p>
				
			</div>
		</div>
		
            <div class="subscribe">
                @include('helpers.showAllErrors')
                @if(session('emailSaved'))
                <div class="alert alert-success">
                    <p>Got it! We'll email {{ session('emailSaved') }} when Coagmento 2.0 is released.</p>
                </div>
                @endif
                <h5 class="info-text">
                    Get notified when Coagmento 2.0 is fully released!
                </h5>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4 col-sm6-6 col-sm-offset-3 ">
                        <form class="form-inline" role="form" method="post" action="/new/notify">
                          <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail2">Email address</label>
                            <input type="email" name="email" class="form-control transparent" placeholder="Your email here..." value="{{ old('email') }}">
                          </div>
                          <button type="submit" class="btn btn-danger btn-fill">Notify Me</button>
                        </form>

                    </div>
                </div>
            </div>
       
    </div>
    <div class="footer">  
    </div>
 </div>
    <script src="js/vendor/jquery-1.10.2.js" type="text/javascript"></script>
    <script src="js/vendor/bootstrap.min.js" type="text/javascript"></script>
    <script>
        $("#facebook_share").on("click", function(e) {
            e.preventDefault();
            FB.ui({
                method: 'share',
                href: 'http://coagmento.org/new'
            });
        });
    </script>
 </body>
</html>