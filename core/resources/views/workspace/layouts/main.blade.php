<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/x-icon" href="/images/favicon.png" />
        <link rel="shortcut icon" type="image/x-icon" href="/images/favicon.png" />

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="/css/global.css" />
        <link rel="stylesheet" href="/css/workspace.css" />
        <link rel="stylesheet" href="/fonts/myriad-pro/style.css" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

        <script src='/js/vendor/underscore.js'></script>
        <script src='/js/vendor/socket.io.js'></script>
        <script src='/js/vendor/backbone.js'></script>
        <script src='/js/config.js'></script>
        <script src='/js/message.js'></script>

        <title>Coagmento Workspace</title>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class='@yield('page')'>
        <div id='page-container'>
            <nav id='page-navigation' class="navbar-fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="/workspace"><img alt="Coagmento" src="/images/workspace/titled-logo.png" /></a>
                    </div>
                    <ul class="nav navbar-nav">
                    </ul>
                </div>
                <div class="container-fluid" id="subnav">
                    @yield('navigation')
                    @if(isset($user))
                    <a class='pull-right' href='/auth/logout'>Logout</a>
                    @else
                    <a class='pull-right' href='/auth/login'>Login</a>
                    @endif
                </div>
            </nav>

            <div class="container-fluid" id="body-container">
                <div class="row">
                    <div class='col-md-2 sidebar'>@yield('sidebar')</div>
                    <div class="col-md-10 main-content">
                        @yield('main-content')
                    </div>
                </div>
            </div>
            
            <footer id='page-footer' class="container-fluid">
            Development of Coagmento is supported by Institute of Museum and Library Services (IMLS). Coagmento 2007-2016
            </footer>
        </div>
        
        <script>
            // Initialize the message display.

            // Compute the minimum height necessary of the body-container to render full screen.
            function computeMinHeight() {
                var value = $(window).height() - $('#page-footer').outerHeight(true) - $('#page-navigation').outerHeight(true);
                $('.main-content').css({
                    'min-height' : value + 'px'
                })
            }
            $(document).ready(computeMinHeight);
            $(window).on('resize', computeMinHeight)
        </script>
    </body>
</html>