<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
  <meta name="viewport" content="width=device-width" />
  
  <title>Coagmento 3.0 Announcement</title>
  
	<link rel="icon" type="image/png" href="images/coagfavicon.png" />
  <!-- Bootstrap + FontAwesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link href='http://fonts.googleapis.com/css?family=Grand+Hotel' rel='stylesheet' type='text/css'>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<link href="css/splash.css" rel="stylesheet" />
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
      
      {{--<ul class="nav navbar-nav navbar-right">--}}
            {{--<li>--}}
                {{--<a target="_blank" id="facebook_share" href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fnew.coagmento.org"> --}}
                    {{--<i class="fa fa-facebook-square"></i>--}}
                    {{--Share--}}
                {{--</a>--}}
            {{--</li>--}}
             {{--<li>--}}
                {{--<a target="_blank" href="https://twitter.com/intent/tweet?status=Check%20out%20the%20next%20version%20of%20Coagmento%3A+http//new.coagmento.org"> --}}
                    {{--<i class="fa fa-twitter"></i>--}}
                    {{--Tweet--}}
                {{--</a>--}}
            {{--</li>--}}
			 {{--<li>--}}
                {{--<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&url=http://new.coagmento.org&title=Coagmento"> --}}
                    {{--<i class="fa fa-google-plus"></i>--}}
                    {{--Google+--}}
                {{--</a>--}}
            {{--</li>--}}
             {{--<li>--}}
                {{--<a target="_blank" href="https://github.com/InfoSeeking/Coagmento"> --}}
                    {{--<i class="fa fa-github"></i>--}}
                    {{--Github--}}
                {{--</a>--}}
            {{--</li>--}}
       {{--</ul>--}}
      
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container -->
</nav>
<div class="main" style="background-image: url('images/splash/bg16.jpg')">

<!--    Change the image source '/images/default.jpg' with your favourite image.     -->
    
    
     
<!--   You can change the black color for the filter with those colors: blue, green, red, orange       -->

    <div class="container">

		<h1 class="logo" itemprop="name">
            <img src="images/logo.png" itemprop="image" alt="logo" WIDTH=100 HEIGHT=100>
			Problem Help Study
        </h1>
		
        <div class="row">
			<div class="col-lg-12 motto">

                <div class="well" style="background-color: rgba(245, 245, 245, 0.4);">
                    <h4><strong><u>WELCOME TO THE STUDY!</u></strong></h4>
                    <p>If you have not registered please register below.  Otherwise, if you are here to participate in
                        the study, please log in.</p>
                    {{--<center><h4><strong><u>Research Study Registration</u></strong></h4></center>--}}

                    {{--<p>Welcome! This is the sign-up form to register for the paid research study.</p>--}}

                    {{--<p>The research project, Information Fostering – Being Proactive in Information Retrieval, funded by--}}
                        {{--the National Science Foundation, seeks participants in a study of information seeking and search.--}}
                        {{--Participants will conduct searches for two assigned search tasks related to different topics.--}}
                        {{--Participants will first complete a pre-search questionnaire in which they are asked for demographic--}}
                        {{--information, information about the knowledge background related to the tasks and topics, and--}}
                        {{--general search skills information. Then, participants will be introduced to the study’s--}}
                        {{--software and conduct a 5-min warm up task to get familiar with the study environment. After that,--}}
                        {{--participants will conduct two formal search tasks (20 mins each) and answer questions in the--}}
                        {{--post-search questionnaires. The study will end with a post-search exit interview in which the--}}
                        {{--researchers will ask questions about participants’ search experience and performance.--}}
                        {{--The whole procedure of this study will take approximately one hour.</p>--}}

                    {{--<p>All volunteers for this study will receive $20 cash for their participation. Taking part in this--}}
                        {{--study will help to advance the understanding of search process and contribute towards development--}}
                        {{--of search systems that can automatically provide help for user’s problems and obstacles in--}}
                        {{--different stages of search.</p>--}}

                    {{--<p>Requirements:--}}
                        {{--<ul>--}}
                        {{--<li>You must be at least 18 years old to participate.</li>--}}
                        {{--<li>You must be a full-time undergraduate student.</li>--}}
                        {{--<li>Proficiency in English is required.</li>--}}
                        {{--<li>Intermediate typing and online search skills are required.</li>--}}
                        {{--<li>You must use Google Chrome throughout the duration of the study.</li>--}}
                        {{--<li>The queries you submit to system during search must be in English.</li>--}}
                        {{--</ul>--}}
                    {{--</p>--}}


                    {{--<p>You will not be offered or receive any special consideration if you take part in this research;--}}
                        {{--it is purely voluntary. This study has been approved by the Rutgers Institutional Review Board--}}
                        {{--and will be supervised by Dr. Chirag Shah (chirags@rutgers.edu) at the School of Communication--}}
                        {{--and Information.</p>--}}

                    {{--<p>For more information about this study, please send e-mail to Jiqun Liu at--}}
                        {{--jl2033@scarletmail.rutgers.edu or Shawon Sarkar at ss2577@scarletmail.rutgers.edu. You can also--}}
                        {{--contact us to ask questions or get more information about the project.</p>--}}


                </div>
        {{--<p><i class="fa fa-cog"> Thorough developer documentation and an <a href='{{ url("apidoc")}}/'>open API</a></i></p>--}}
        {{--<p><i class="fa fa-cog"> Realtime feed of user activity</i></p>--}}
        {{--<p><i class="fa fa-cog"> Up to date Firefox extension</i></p>--}}
				{{--<p><i class="fa fa-cog"> Easy setup for your own custom studies</i></p>--}}
                <form action='/auth/login' method='get'>
                    <button class='btn btn-success btn-fill' type='submit'>Log in</button>
                </form>
                <form action='/auth/studywelcome' method='get'>

                    <button class='btn btn-primary btn-fill' type='submit'>Register</button>
                </form>
            </div>
		</div>


       
    </div>
    <div class="footer">  
    </div>
 </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <script>
        $("#facebook_share").on("click", function(e) {
            e.preventDefault();
            FB.ui({
                method: 'share',
                href: 'http://new.coagmento.org'
            });
        });
    </script>
 </body>
</html>