<?php

return array(

  'env' => array(
    'siteName' => 'Nébula',
  ),

  'routing' => array(

    '/' => 'template => pages/index.php',
    '/{view}' => 'template => pages/{view}.php',
    
  ),

  'resources' => array(
    'js' => array(
      '/bower_components/jquery/dist/jquery.js',
      '/bower_components/bootstrap/dist/jquery.js',
      '/vendor/prism/prism.js',
    )
  ),

  'requires' => array(
    'helpers/functions',
    'exts/am_route',
    'exts/am_tpl',
    'exts/am_controller',
  ),

);