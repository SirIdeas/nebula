<?php

return array(
  // Rutas fisicas
  "concat" => array(
    // Target
    "public" => array(
      "public/styles/vendor.css" => array(
        // Archivos fisicos
        "../bower_components/materialize/dist/css/materialize.css"
      ),
      "public/scripts/ie-fixs.js" => array(
        "../bower_components/es5-shim/es5-shim.js",
        "../bower_components/json3/lib/json3.min.js"
      ),
      "public/scripts/vendor.js" => array(
        "../bower_components/jquery/dist/jquery.js",
        "../bower_components/materialize/dist/js/materialize.js"
      ),
    )
  ),
  "copy" => array(
    "btFonts" => array(
      "dest" => "font/",
      "src" => array(
        "../bower_components/materialize/font/*",
      )
    ),

  ),
);
