<?php

/**
 * Clase para atender peticiones para comandos
 */

class AmCommand{

  protected static
    $paths = array(); // Carpetas donde se buscaran comandos

  // Agregar un carpeta a la lista de carpetas de comandos
  public static function addPath($path){
    self::$paths[] = realpath($path) . "/";
    self::$paths = array_unique(self::$paths);
  }

  // Ejecucion de un comando
  public static function exec($argv){

    // Obtener los targets del archivo de configuracion
    $targets = Am::getAttribute("commands", array());

    // 1er: origen de la peticion: HTTP/consola
    $file = array_shift($argv);

    // 2do: Comando a ejecutar
    $cmd = array_shift($argv);
    
    // El comando puede ser indicado con un target especifico: $cmd="comando:target"
    // Dividir para obtener target
    $params = explode(":", $cmd);    
    $cmd = array_shift($params);    // La primera parte es el comando real
    $target = array_shift($params); // El siguiente elemento es el target. Si no existe es null
                                    // $params queda con el resto de los parametros del argumento
                                    // $argv queda con el resto de los parametros recibidos

    // Incluir el archivo si existe
    $functionFile = Am::findFileIn("{$cmd}.php", self::$paths);
    require_once $functionFile;
    
    // Determinar el nombre de la funcion que ejecuta el comando
    $functionName = "am_command_{$cmd}";
    
    // Si la funcion no existe mostrar error
    function_exists($functionName) or die("Am: command not found {$cmd}");

    ob_start();
    // Imprimir el comando que se ejecutará
    echo "Amathista commands\n\n-- $cmd:";

    // Si el target esta indicado
    if(isset($target)){
      
      // Si el target esta definido pero no existe en la configuracion
      // mostrar error
      isset($targets[$cmd][$target]) or die("Am: target not found {$cmd}:{$target}");
      // Obtener la configuracion del target indicado
      $config = $targets[$cmd][$target];

      // Llamda de la funcion que atiende el comando
      // params: 1: target indicado
      //         2: parametros del argumento
      //         3: configuracion del target
      //         4: parametros recibidos
      $functionName($target, $params, $config, $file, $argv);

    // Sino se definio el target, pero existen targets en la configuracion para el comando
    }elseif(isset($targets[$cmd])){

      // Ejecutar el comando con todos los targets en la configuracion
      foreach($targets[$cmd] as $target => $conf)
        $functionName($target, $params, $conf, $file, $argv);

    }else

      // Llamado de la funcion 
      $functionName(null, array(), array(), $file, $argv);

    echo "\n";
    return ob_get_clean();

  }

  // Atender peticion por por terminar
  public static function asTerminal(){

    // se une los argumentos con "/"
    $arguments = implode("/", func_get_args());

    // Separar todos los argumentos
    $arguments = explode("/", $arguments);

    // Ejecutar comando
    echo self::exec($arguments);

  }

  // PENDIENTE DESARROLLAR
  public static function asRequest(){
    header("content-type: text/plain");
    call_user_func_array(array("AmCommand", "asTerminal"), func_get_args());
  }

}
