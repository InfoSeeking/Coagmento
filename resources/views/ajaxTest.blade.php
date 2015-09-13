<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">


  <title></title>
  <meta name="description" content="">
  <meta name="author" content="Kevin Albertson">

  <style type="text/css">
  </style>

  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
</head>

<body>
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script>
  	$.ajax({
		url: "/api/bookmarks/",
		method: "post",
    data: {
      'url' : 'http://kevinalbs.com',
      'title' : "XD"
    },
		success: function(resp) {
			console.log(resp);
		}
	})
  </script>
</body>
</html>