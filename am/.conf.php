<?php

/**
 * Configuracion de carga inicial
 */

return array(

  "errorReporting" => E_ALL,    // Indicar que errores se mostrarán

  "sessionManager" => "normalSession", // MAnejador de session
  
  "requires" => array(
    "exts/am_route/",
    "exts/am_control/",
    "exts/am_data_time/",
    "exts/am_asset/",
    "exts/am_template/",
    "exts/am_mailer/",
    "exts/am_flash/",
    "exts/am_orm/",
    "exts/am_credentials/",
  ),

);
