(:: set:siteTitle='Nébula' :)
(:: set:title='Inicio' :)
(:: set:pagina='home' :)
(:: set:paso='home' :)

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
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap.css"/> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap-theme.min.css"/> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
    <!-- Prism styles -->
    <link rel="stylesheet" href="(:/:)/vendor/prism/prism.css"/>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="(:/:)/css/styles.css"/>
    
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link rel="stylesheet" href="(:/:)/vendor/ie10-viewport-bug-workaround.css">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="(:/:)/vendor/ie8-responsive-file-warning.js"></script><![endif]-->
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
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script type="text/javascript" src="(:/:)/bower_components/jquery/dist/jquery.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- <script type="text/javascript" src="(:/:)/bower_components/bootstrap/dist/js/bootstrap.js"></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script type="text/javascript" src="(:/:)/vendor/prism/prism.js"></script>
    <!-- <script type="text/javascript" src="(:/:)/vendor/docs.min.js"></script> -->
    <script type="text/javascript" src="(:/:)/vendor/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>