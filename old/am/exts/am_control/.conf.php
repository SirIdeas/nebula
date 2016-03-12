<?php

return array(

  "extend" => array(

    "control" => array(
      "defaults" => array(
        "root" => "control/", // Carpeta raiz del controlador
        "views" => "views/",  // Carpeta por defecto para las vistas
        "paths" => array(),   // Carpetas de vistas del controlador
      ),
    ),
    
  ),

  "files" => array(
    "AmControl.class"
  ),

  "mergeFunctions" => array(
    "control" => "array_merge_recursive",
  )
  
);