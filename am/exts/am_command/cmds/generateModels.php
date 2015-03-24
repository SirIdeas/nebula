<?php

// General las clases para el modelo de la BD
function am_command_generateModels($target = null, $params = null, $config = null, $file = null, $argv = array()){
  
  echo "\n";
  
  $model = trim(array_shift($argv));
  $source = trim(array_shift($argv));

  // Si no se recibió el model se buscará el modelo por defecto
  if(!$source)
    $source = "default";
  
  // Si no existe la configuración para la fuente
  if(null === AmORM::getSourceConf($source)){
    echo "Fuente de datos inválida";
    return;
  }

  // Obtener instancia de la fuente
  $sourceInstance = AmORM::source($source);

  function echoResult($table, $result){
    echo
      "\n  {$table}:".
      "\n    folders              : " . ($result["folders"]?    "createds" : "").
      "\n    configuration file   : " . ($result["conf"]?       "created" : "already exists").
      "\n    class base for table : " . ($result["tableBase"]?  "created" : "already exists").
      "\n    class for table      : " . ($result["table"]?      "created" : "already exists").
      "\n    class base for model : " . ($result["modelBase"]?  "created" : "already exists").
      "\n    class for model      : " . ($result["model"]?      "created" : "already exists").
      "\n";
  }

  // Si no se indico el modelo entonces se genera
  // el ORM de toda la fuente
  if($model === null ||  empty($model)){

    // Generar todos los modelos
    $ret = $sourceInstance->createClassModels();

    // Mostrar el resultado de la creación de archivo
    // de configuracion de la fuente
    echo "\nsource {$source}:";
    echo "\n";
    echo "\n  configuration file     : " . ($ret["source"]? "created" : "already exists");
    echo "\n";

    // Mostrar el resultado
    // El resultado esta agrupado por tabla
    foreach ($ret["tables"] as $table => $result) {
      echoResult("table ".$table, $result);
    }

  }else{

    // Obtener instancia de la tabla
    $tableInstance = $sourceInstance->describeTable($model);

    // Si no se encuentra la instancia de la tabla
    if(!$tableInstance){
      echo "No se encontró la tabla '{$source}'.'{$model}'";
      return;
    }

    // Mostrar el resultado
    echoResult($model, $tableInstance->createClassModels());

  }

}