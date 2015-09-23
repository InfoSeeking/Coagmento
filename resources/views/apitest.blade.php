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
  /*
  	$.ajax({
		url: "/api/v1/projects/",
		method: "post",
    data: {
      'title' : 'New Project',
      'description' : 'Testing a new project'
    },
		success: function(resp) {
			document.write(resp);
		},
    complete: function(xhr) {
      document.write(xhr.responseText);
    }
	});
*/

    $.ajax({
    url: "/api/v1/bookmarks",
    method: "post",
    data: {
      'title' : 'Google',
      'url' : 'http://google.com',
      'project_id' : 11
    },
    success: function(resp) {
      document.write(resp);
    },
    complete: function(xhr) {
      document.write(xhr.responseText);
    }
  });

  </script>
</body>
</html>