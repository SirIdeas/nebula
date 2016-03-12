(:: set:siteTitle='Nébula' :)
(:: set:title='Inicio' :)
(:: set:pagina='home' :)

<!DOCTYPE html>
<html class="page-(:= $pagina :)">
  <head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
      (:= $siteTitle :) |
      (:= $title :)
    </title>
    <link rel="shortcut icon" href="(:/:)/favicon.ico"/>
    <link rel="stylesheet" href="(:/:)/font/flaticon.css"/>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap.css"/>
    <!-- Bootstrap theme -->
    <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap-theme.min.css"/>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link rel="stylesheet" href="(:/:)/vendor/ie10-viewport-bug-workaround.css">
    <!-- Prism styles -->
    <link rel="stylesheet" href="(:/:)/vendor/prism/prism.css"/>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="(:/:)/css/styles.css"/>

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="(:/:)(:/:)/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="(:/:)/vendor/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    (::place:views/nav.php :)
    (:: child :)
    (::place:views/foot.php :)
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-41615550-12', 'auto');ga('send', 'pageview');
    </script>
    <script type="text/javascript" src="(:/:)/bower_components/jquery/dist/jquery.js"></script>
    <script type="text/javascript" src="(:/:)/bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <!-- <script type="text/javascript" src="(:/:)/vendor/docs.min.js"></script> -->
    <script type="text/javascript" src="(:/:)/vendor/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript" src="(:/:)/vendor/prism/prism.js"></script>
  </body>
</html>