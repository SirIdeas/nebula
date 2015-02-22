<?php 

return array(
  "env" => array(
    "_env"  => new AmObject(Am::getConfig("conf/env")),
    "pasos" => Am::getConfig("usr/pasos")
  ),
  "routes" => array(

    // Assets
    "/:file(sitemap\.xml|favicon\.ico|robots\.txt|styles/.*|scripts/.*|images/.*|font/.*|videos/.*)"   => "file#public/:file",
    "/:file(styles/.*\.css|scripts/.*\.js)" => "assets#:file",

    "/" => "template#views/index.php",
    "/:vista" => "template#views/:vista.php",
    
  )
);