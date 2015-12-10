<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Coagmento Sidebar</title>
    <!-- Bootstrap Core CSS -->
    <link href="/css/vendor/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/css/global.css" rel="stylesheet">
    <link href="/css/sidebar.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

<body>
<script src='/js/vendor/jquery-1.10.2.js'></script>
<script src='/js/vendor/bootstrap.min.js'></script>

<div id="wrapper">
        <!-- Navigation -->
            <div class="navbar-default sidebar">
                <div class="sidebar-nav navbar-collapse">
                    @yield('content')
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
	</div>
    <!-- /#wrapper -->
</body>
</html>