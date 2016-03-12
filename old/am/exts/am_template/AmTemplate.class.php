<?php

/**
 * Clase renderizar vistas
 */

final class AmTemplate extends AmObject{

  // Carpeta donde se guardan los compilados de las vistas
  const BUILD_FOLDER = "../gen/";

  protected
    $file = null,             // Vista a buscar
    $realFile = null,         // Ruta real de la vista
    $content = "",            // Contenido del archivo
    $env = array(),           // Entorno
    $params = array(),        // Variables definidas en la vista 
    $parent = null,           // Vista padre
    $openSections = array(),  // Lista de secciones abiertas
    $sections = array(),      // Lista de secciones y su contenido
    $child = null,            // Contenido de la vista hija
    $dependences = array(),   // Lista de vistas de las que depende (padre, hijas y anidadas)
    $paths = array(),         // Lista de directorios donde se buscará la vista
    $ignore = false,          // Bandera que indica si se ignoran las vistas inexistentes sin generar error
    $errors = array(),        // Indica si se generó o no un error durante el renderizado
    $options = array();       // Guarda los parametros con los que se inicializó la vista

  public function __construct($file, $paths, $options = array()){
    parent::__construct($options);

    // setear paths
    if(is_array($paths)){
      $this->paths = $paths;
    }else{
      $this->paths[] = $paths;
    }

    // Asignar atributos
    $this->options = $options;
    $this->file = $file;
    $this->realFile = $this->findView($file);

    // Leer archivo
    if($this->realFile !== false)
      $this->content = file_get_contents($this->realFile);

    // Obtener padre
    preg_match_all("/\(# parent:(.*) #\)/", $this->content, $parents);
    $this->parent = array_pop($parents[1]);
    
    // Quitar sentencias de padres
    $this->content = implode("", preg_split("/\(# parent:(.*) #\)/", $this->content));

    // Obtener lista de hijos en comandos place
    preg_match_all("/\(# (place:(.*)|put:.* = (.*)) #\)/", $this->content, $dependences1);
    
    // Obtener lista de hijos en comandos put
    $this->dependences = array_merge($dependences1[2], $dependences1[3]);
    if(!empty($this->dependences))
      $this->dependences = array_keys(array_filter(
        array_combine($this->dependences, $this->dependences)
      ));

    // Instanciar padre dentro de las dependencias
    if(null !== $this->parent)
      array_unshift($this->dependences, $this->parent);

    // Convertir el array de dependencias a un array asociativo
    // donde todos los valores sean false
    if(0<count($this->dependences)){
      $this->dependences = array_combine($this->dependences, array_fill(0, count($this->dependences), false));
    }

  }

  // Busca una vista en los paths definidos
  public function findView($file){
    // Si no existe la vista mostrar error
    if(false === ($fileRet = Am::findFileIn($file, $this->paths))){
      $this->errors[] = "Am: No existe view '{$file}.'";
      $this->ignore or die(implode(" ", $this->errors));
    }
    return $fileRet;
  }

  // Compilar la vista
  public function compile($child = null, array $sections = array()){

    // Asignar secciones recibidas
    $this->sections = $sections;
    $this->child = $child;  // Contenido de un vista hija

    // Dividir por comandos
    $parts = preg_split("/\(# (.*) #\)/", $this->content);

    // Obtener comando
    preg_match_all("/\(# (.*) #\)/", $this->content, $cmds);
    $cmds = $cmds[1];

    ob_start(); // Para optener todo lo que se imprima durante el compilad

    // Recorrer las partes entre los comando
    foreach($parts as $i => $part){
      echo $part; // Imprimir la parte actual
      if(isset($cmds[$i])){ // Si existe un comando en la misma posicion

        // Obtener parametros del comando
        list($method, $param) = array_merge(explode(":", $cmds[$i]), array("", null));

        // Si no existe un metodo con el mismo nombre del comando mostrar error
        method_exists($this, $method) or die("Am: unknow method AmTemplate->$method");

        // Si el metodo es set
        if($method == "set")
          // Se divide el argumento en dos parametros
          $param = explode("=", $param);
        else
          $param = array($param);

        // Llamado el metodo
        call_user_func_array(array($this, $method), $param);

      }

    }

    // Obtener el contenido
    $content = ob_get_clean();

    // Si la vista tiene un padre
    if(null !== $this->parent){
      // Obtener instancia de vista del padre
      $parentView = $this->getSubView($this->parent)
        // Compilar padre
        ->compile($content, $this->sections);

      // Mezclar generadas en el padre con las definidas en la vista acutal
      $this->params = $parentView["vars"] = array_merge($parentView["vars"], $this->params);
      $this->errors  = array_merge($this->errors, $parentView["errors"]);
      return $parentView;
    }

    return array(
      "content" => $content,          // Todo lo impreso
      "sections" => $this->sections,  // Devolver secciones definidas
      "vars" => $this->getVars(),     // Variables definidas
      "errors" => $this->errors       // Indica si se generó un error
    );

  }

  // Obtiene una vista con el mismo entorno de la vista actual
  public function getSubView($name){
    // Si no esta definida la dependiencia mostrar error
    isset($this->dependences[$name]) or die("Am: not found subview \"{$name}\" in \"{$this->realFile}\"");
    // Si la dependencia no es una instancia de AmView
    if(!$this->dependences[$name] instanceof self){
      // Se instancia la vista 
      $this->dependences[$name] = new self($name, $this->paths, $this->options);
    }
    // Devolver instancia de la vista
    return $this->dependences[$name];
  }

  // Inserta una vista anidada
  public function place($view){
    $view = $this->getSubView($view)->compile("", $this->sections);
    echo $view["content"];
    $this->sections = array_merge($view["sections"], $this->sections);
    $this->params = array_merge($view["vars"], $this->params);
    $this->errors  = array_merge($this->errors, $view["errors"]);
  }

  // Imprimir una seccion
  public function put($name){

    // Si tiene una vista por defecto se carga
    if(preg_match("/(.*) = (.*)/", $name, $m)){
      array_shift($m);
      list($name, $path) = $m;
      $this->place($path);
    }

    $section = isset($this->sections[$name])? $this->sections[$name] : "";
    echo $section;

  }

  // Abrir una seccion
  public function section($name){
    $this->openSections[] = $name;
    ob_start();
  }

  // Cerrar seccion
  public function endSection(){
    // Si no existen secciones abiertas entonces mostrar error
    !empty($this->openSections) or die("Am: closing section unopened");

    // Obtener lo impreso hasta hora
    $content = ob_get_clean();

    // Obtener el nombre de la ultima seccion abierta
    $name = array_pop($this->openSections);

    // Agregar seccion si no existe
    // Obtener directivas del nombre de la seccion
    preg_match("/^([+]?)(.*[^+])([+]?)$/", $name, $m);
    array_shift($m);
    list($start, $name, $end) = $m;

    // Crear seccion si no existe
    if(!isset($this->sections[$name]))
      $this->sections[$name] = "";

    // No se recibió comandos
    if(empty($start) && empty($end))
      $this->sections[$name] = $content;
    
    // Agregar al principio
    if($start === "+")
      $this->sections[$name] = $content . $this->sections[$name];

    // Agregar al final
    if($end === "+")
      $this->sections[$name] = $this->sections[$name] . $content;

  }

  // Imprimir el contenido de la vista hija
  public function child(){
    echo $this->child;
  }

  // Agregar variable
  public function set(){
    extract($this->getVars());
    eval("\$this->params['".func_get_arg(0)."'] = ".func_get_arg(1).";");
  }

  // Obtener variables de la vista. Cinluye el entorno + las variables definidas en la vista
  public function getVars(){
    return array_merge($this->env, $this->params);
  }

  // Obtener lista de dependencas
  public function dependences(){

    // La primera dependencia es el archivo pripio de la vista
    $dependences = array($this->realFile);

    // Sedebe agregar las dependencias de las vistas relacionadas (padre hijas, y anidadas)
    foreach ($this->dependences as $key => $value) {
      $dependences = array_merge($dependences, $this->getSubView($key)->dependences());
    }

    return $dependences;

  }

  // Devuelve la ruta donde se guardara la vista compilada
  public function getCompiledFile(){
    if($this->realFile === false) return false;
    return self::BUILD_FOLDER . $this->realFile;
  }

  // Indica si la vista esta acutalizada.
  // Para esto se verifica que ninguna de las vista dependientes 
  // haya sido modificada despues de la fecha de la ultima fecha
  // de compilacion de la vista actual 
  public function isUpdate($compiledView){

    // Si no se ha compilado no esta actualizado
    if(!is_file($compiledView)) return false;

    // Obtener fecha de creacion de la vista compilada
    $compiledTime = filemtime($compiledView);

    // Obtener dependencias
    $dependences = $this->dependences();

    // Si alguna fue modificada despues de la fecha de compilacion
    // No esta actualizada
    foreach ($dependences as $file) {
      if($compiledTime<filemtime($file)) return false;
    }

    return true;

  }

  // Generar vista
  public function save(){

    // Obtener vista generada
    if(false === $compiledView = $this->getCompiledFile()) return;

    // Si esta actualizada salir
    // if($this->isUpdate($compiledView)) return;

    // Carpeta donde se ubicara la vista compilada
    $compileFolder = dirname($compiledView);

    // Si no existe el directorio se crea, y sino se puede crear se muestra un error
    is_dir($compileFolder) or mkdir($compileFolder, 0775, true) or die("Am: can't to create folder \"{$compileFolder}\"");
    
    // Obtener contenido compilado de la vista
    $result = $this->compile($this->child);

    // Guardar vista minificada
    file_put_contents($compiledView, $result["content"]);

  }

  // incluye la vista compilada
  public function includeView(){
    if(is_file($this->getCompiledFile())){
      extract($this->getVars());  // Crear variables
      include $this->getCompiledFile();          // Inluir vista
    }
  }

  // Método que indica si se generó algun error al renderizar la vista
  public function hasError(){
    return count($this->errors)>0;
  }

  // Funcion para atender el llamado de render.tempalte
  public static function render($file, $paths, $options = array()){

    // Obtener configuraciones del controlador
    $confs = Am::getAttribute("views", array());
    
    // Obtener valores por defecto
    $defaults = itemOr("defaults", $confs, array());

    // Si no existe configuracion para la vista
    $conf = isset($confs[$file])? $confs[$file] : array();

    // Mezclar todas las opciones
    $options = array_merge_recursive($defaults, $conf, $options);

    $view = new self($file, $paths, $options); // Instancia vista
    $view->save();        // Compilar y guardar
    $view->includeView(); // Incluir vista
    return !$view->hasError();
  }

}
