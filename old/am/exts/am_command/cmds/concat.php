<?php

// Concatenar: PENDIENTE ORGANIZAR
function am_command_concat($target, $params, $config, $file, $argv){

  foreach ($config as $fileName => $assets) {
    // REVISAR: No se deberia usar AmAsset
    $asset = new AmAsset($fileName, $assets);
    file_put_contents($fileName, $asset->getContent());
    echo "\nAm: Asset created $fileName";
  }

}