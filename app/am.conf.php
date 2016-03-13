<?php

return array(

  'env' => array(
    'siteName' => 'Nébula',
  ),

  'routing' => array(

    '/' => 'template => pages/index.php',
    '/{view}' => 'template => pages/{view}.php',
    '/presentation/' => 'template => ../public/presentation/index.html',
    
  ),

  'requires' => array(
    'helpers/functions',
    'exts/am_route',
    'exts/am_tpl',
    'exts/am_controller',
  ),

);