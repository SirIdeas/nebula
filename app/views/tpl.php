(: $siteTitle = 'Nébula'
(: $title = 'Inicio'
(: $paso = 'home'

(: $siteTitle = 'Amathista Framework'
(: $pageTitle = 'Home'

(: section:'head'

  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  (:t icon('/favicon.ico') :)
  <title>(:= $siteTitle :) |(:= $title :)</title>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  (:t css('/fixes/ie10-viewport-bug-workaround.css')
  <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
  <!--[if lt IE 9]>
    (:t js('/fixes/ie8-responsive-file-warning.js') :)
  <![endif]-->
  (:t js('/fixes/ie-emulation-modes-warning.js')
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    (:t js('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js', true)
    (:t js('https://oss.maxcdn.com/respond/1.4.2/respond.min.js', true)
  <![endif]-->
  
  <!-- <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap.css"/> -->
  (:t css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', true, ['integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7', 'crossorigin' => 'anonymous'])
  
  <!-- <link rel="stylesheet" href="(:/:)/bower_components/bootstrap/dist/css/bootstrap-theme.min.css"/> -->
  (:t css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css', true, ['integrity' => 'sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r', 'crossorigin' => 'anonymous'])

  <!-- Prism styles -->
  (:t css('/vendor/prism/prism.css')

  <!-- Custom styles for this template -->
  (:t css('/font/flaticon.css')
  (:t css('/css/styles.css')

(: endSection

(: section:'foot'

  (:t js('/fixes/ie10-viewport-bug-workaround.js')
  <!--[if lt IE 9]>
    (:t js('https://cdnjs.cloudflare.com/ajax/libs/es5-shim/4.5.7/es5-shim.min.js', true)
    (:t js('https://cdnjs.cloudflare.com/ajax/libs/json3/3.3.2/json3.min.js', true)
  <![endif]-->
  
  (: if($_SERVER['SERVER_NAME'] != 'localhost'):
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-41615550-12', 'auto');ga('send', 'pageview');
    </script>
  (: endif

  (:t js('https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', true)
  (:t js('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', true, ['integrity' => 'sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS', 'crossorigin' => 'anonymous'])
  
  (:t js('/vendor/prism/prism.js')

(: endSection

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="page-(:= $pagina :)">
  <head>
    (: put:'head'
  </head>
  <body>
    (: insert:'views/nav.php'
    (: child
    (: insert:'views/foot.php'
    (: put:'foot'
  </body>
</html>
