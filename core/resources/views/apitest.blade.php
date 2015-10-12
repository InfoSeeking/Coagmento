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
  function test(write, url, method, data) {
    $.ajax({
      url: url,
      method: method,
      data: data,
      success: function(resp) {
        if (write) {
          document.write(resp);
        } else {
          console.log("===");
          console.log(url);
          console.log(resp);
          console.log("===");
        }
      },
      complete: function(xhr) {
        var resp = xhr.responseText;
        if (write) {
          document.write(resp);
        } else {
          console.log("===");
          console.log(url);
          console.log(resp);
          console.log("===");
        }
      }
    })
  }

  // test(true, 'api/v1/projects', 'post', {
  //   'title': 'A new project',
  //   'description': 'A second project template'
  // });

  // test(true, 'api/v1/bookmarks', 'post', {
  //   'title': 'A new bookmark 2',
  //   'url': 'http://yahoo.com',
  //   'project_id': 36
  // });

  // test(true, 'api/v1/bookmarks/1', 'put', {
  //   'title': 'Changed title'
  // });

  // test(true, 'api/v1/bookmarks/42/move', 'put', {
  //   'project_id': 33
  // });

  //test(true, 'api/v1/projects/34', 'delete', {});

  </script>
</body>
</html>