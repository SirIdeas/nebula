<?php 

/**
 * Clase para controlador estandar. Basado en el objeto estandar de Amathista
 */

class AmControl extends AmObject{

  private static

    // Callbacks para mezclar atributos
    $mergeFunctions = array(
      "paths"         => "array_merge",
      "prefix"        => "array_merge",
      "actionAllows"  => "array_merge",
      "filters"       => "merge_r_if_snd_first_not_false",
    );

  protected
    $url = "",                // URL base del controlador
    $root = null,             // Carpeta raiz del controlador
    $paths = array(),         // Carpetas donde se buscara las vistas
    $view = null,             // Nombre de la vista a renderizar
    $filters = array(),       // Filtros agregados
    $credentials = false,     // Credenciales para el controlador
    $prefixs = array(),       // Prefijos para diferentes elementos en el controlador
    $actionAllows = array(),  // Acciones permitidas

    $server = null,     // Variables de SERVER
    $get = null,        // Variables recibidas por GET
    $post = null,       // Variables recibidas por POST
    $request = null,    // Todas las variables recibidas
    $cookie = null,     // Çookies
    $env = null;        // Variables de entorno

  public function __construct($data = null){
    parent::__construct($data);

    $this->server = new AmObject($_SERVER);
    $this->get = new AmObject($_GET);
    $this->post = new AmObject($_POST);
    $this->cookie = new AmObject($_COOKIE);
    $this->request = new AmObject($_REQUEST);
    $this->env = new AmObject($_ENV);

  }

  // Devuelve la URL de base del controlador
  final public function getUrl(){
    return $this->url;
  }

  // Propiedad para get/set para render
  final protected function getView(){ return $this->view; }
  final protected function setView($value){ $this->view = $value; return $this; }

  // Devuelve un array de los paths de ambito del controlador
  final protected function getPaths(){
    
    $ret = array_filter($this->paths);  // Tomar valores validos
    $ret = array_unique($ret);          // Valor unicos
    $ret = array_reverse($ret);         // Invertir array

    // Agregar carpeta raiz del controlador si existe si existe
    if(isset($this->root))
      array_unshift($ret, $this->root);

    // Agregar carpeta raiz del controlador para vistas
    // si existe si existe
    if(isset($this->views))
      array_unshift($ret, $this->root . $this->views);

    // Invertir array,
    return $ret;

  }

  // Devuelve el método de la peticion
  final protected function getMethod(){
    return strtolower($this->server->REQUEST_METHOD);
  }

  // Devuelve el nombre normal de una vista
  final protected static function getViewName($value){ 
    return "{$value}.view.php";
  }

  // Devuelve el prefijo para determinado elemento
  final protected function getPrefix($key){
    return itemOr($key, $this->prefixs, "");
  }

  // Asigna la vista que se renderizará.
  // Es un Alias de la funcion setView que agrega .view.php al final
  // del valore recibido.
  final protected function render($value){
    // Las vista de las acciones son de extencion .view.php
    return $this->setView(self::getViewName($value));
  }

  // Renderizar la vista
  final private function renderView(array $vars, $child){

    // Renderizar vista mediante un callback
    $ret = Am::call("render.template",

      // Obtener vista a renderizar
      $this->getView(),

      // Obtener carpetas de ambito para el controlador
      $this->getPaths(),
      
      // Paths para las vistas
      array(
        // Variables en la vista
        "env" => array_merge($vars, $this->toArray()),
        "ignore" => true,
        "child" => $child,
      )
      
    );

    // Si no se logra renderizar la vista se imprime
    // se imprime lo que viene en $child
    if($ret === false)
      echo $child;

  }

  // Responder como servicio
  final private function renderService($content){
    
    $type = "json";

    isset($content) && is_object($content) AND $content = (array)$content;

    switch ($type){
      case 'json':
        $contentType = 'application/json';
        $content = json_encode($content);
        break;
      default:
        $contentType = 'text/plain';
        $content = print_r($content, true);
        break;
    }
    
    header("content-type: {$contentType}");
    echo $content;

  }

  // Devuelve el array de acciones permitidas
  final public function getActionAllows(){
    return $this->actionAllows;
  }

  // Indica si una accion esta permitida o no.
  // Si las acciones permitidas no tiene el item 
  // correspondiente a la acción solicitada entonces
  // se asume que esta permitida la acción.
  final public function isActionAllow($action){
    return isset($this->actionAllows[$action])? 
      $this->actionAllows[$action] : true;
  }

  // Revisa si una accion esta permitida. Si la acción no esta 
  // permitida se redirigue a la url raiz del controlador
  final public function checkIsActionAllow($action){
    if(!$this->isActionAllow($action))
      Am::gotoUrl($this->url);
  }

  // Despachar una acción
  final public function dispatch($action, array $env, array $params){
    
    // Todo lo que se imprimar desde este punto hasta 
    // ob_get_clean() se guardará en una variable.
    ob_start();
    
    // Ejecutar accion con sus respectivos filtros.
    $ret = $this->executeAction($action, $this->getMethod(), $params);

    // Para obtener la salida
    $buffer = ob_get_clean();

    // Si la salida es indicada como salida de un servicio
    // o si el último retorno es un array o un objeto se procesa
    // la salida como un servicio. De lo contrario se renderizará
    // la vista correspondiente.

    // Responder como un sericio
    if(is_array($ret) || is_object($ret))
      $this->renderService($ret);

    else
    // Renderizar la vista
      $this->renderView(array_merge($env, $params), $buffer);

  }

  // Manejo de filtros para las acciones de los controladores

  // Agregar un filtro
  final protected function addFilter($name, $cls, $to = "all", $except = array(), $redirect = null){
    
    // Filtro "only" para ciertos métodos
    if(is_array($to)){
      $scope = "only";
      $redirect = $except;
      $except = array();

    // Filtro para "all" métodos o para "except"
    }else{
      $scope = $to;
      $to = array();
    }
    
    // Si no se ha creado el contenedor del filtro, se crea
    if(!isset($this->filters[$state][$name])){

      // Crear array vacío en el state si no existe.
      if(!isset($this->filters[$state]))
        $this->filters[$state] = array();
      
      // Agregar filtro vacío
      $this->filters[$state][$name] = array(
        
        // A que metodo se aplicara el filtro: "all", "only" o "except"
        "scope" => $scope,
        
        // A quienes se aplicara el filtro en caso de que scope=="only"
        "to" => array(),

        // A quienes no se aplicará el filtro en caso de que scope=="except"
        "except" => $except,

        // Si la peticion no pasa el filtro rediriguir a la siguiente URL
        "redirect" => $redirect

      );
      
    }
    
    // Mezclar los métodos a los que se aplicará el filtro con los que 
    // ya habian sido agregados y obtener los valores unicos
    $this->filters[$state][$name]["to"] = array_unique(array_merge(
      $this->filters[$state][$name]["to"],
      $to
    ));

  }
  
  // Agregar un filtro antes de la ejecucion de metodos
  final protected function addBeforeFilter($name, $to = "all", $except = array(), $redirect = null){
    $this->addFilter($name, "before", $to, $except, $redirect);
  }
  
  // Agregaun filtro antes de la ejecucion de métodos GET
  final protected function addBeforeGetFilter($name, $to = "all", $except = array(), $redirect = null){
    $this->addFilter($name, "before_get", $to, $except, $redirect);
  }
  
  // Agregaun filtro antes de la ejecucion de métodos POST
  final protected function addBeforePostFilter($name, $to = "all", $except = array(), $redirect = null){
    $this->addFilter($name, "before_post", $to, $except, $redirect);
  }
  
  // Agregaun filtro despues de la ejecucion de métodos
  final protected function addAfterFilter($name, $to = "all", $except = array()){
    $this->addFilter($name, "after", $to, $except);
  }
  
  // Agregaun filtro despues de la ejecucion de métodos GET
  final protected function addAfterGetFilter($name, $to = "all", $except = array()){
    $this->addFilter($name, "after_get", $to, $except);
  }
  
  // Agregaun filtro despues de la ejecucion de métodos POST
  final protected function addAfterPostFilter($name, $to = "all", $except = array()){
    $this->addFilter($name, "after_post", $to, $except);
  }
  
  // Ejecuta los filtros correspondiente para un método.
  // state: Indica el estado que se ejecutara: before, before_get, bofore_post, after, after_get, after_post
  // methodName: Nombre del metodo del que se desea ejecutar los filtros.
  // estraParams: Parámetros extras para los filtros.
  final protected function executeFilters($state, $methodName, $extraParams){
    
    // Si no hay filtro a ejecutar para dicha peticion salir
    if(!isset($this->filters[$state]))
      return true;

      
    // Recorrer los filtros del peditoestado
    foreach($this->filters[$state] as $filterName => $filter){
      
      // Si el filtro no se aplica a todos y si el metodo solicitado no esta dentro de los
      // métodos a los que se aplicará el filtro actual continuar con el siguiente filtro.
      if($filter["scope"] != "all" && !in_array($methodName, $filter["to"]))
        continue;

      // Si el método esta dentro de las excepciones del filtro
      // continuar con el siguiente filtro
      if(isset($filter["except"]) && in_array($methodName, $filter["except"]))
        continue;

      // Obtener le nombre real del filtro
      $filterRealName = $this->getPrefix("filters") . $filterName;

      // Llamar el filtro
      $ret = call_user_func_array(array(&$this, $filterRealName), $extraParams);
      
      // Si la accion pasa el filtro o no se trata de un filtro before
      // se debe continuar con el siguiente filtro
      if($ret !== false || $state != "before")
        continue;
      
      // Si se indica una ruta de redirección se lleva a esa ruta
      if(isset($filter["redirect"]))
        Am::gotoUrl($filter["redirect"]);

      // Si no retornar false para indicar que no se pasó el filtro.
      return false;

    }

    // Si todos los filtros pasaron retornar verdadero.
    return true;
    
  }

  // Ejecuta una accion determinada
  final protected function executeAction($action, $method, array $params){

    // Chequear si esta permitida o no la acción
    $this->checkIsActionAllow($action);

    // Verificar las credenciales
    Am::getCredentialsHandler()
      ->checkCredentials($action, $this->credentials);

    // Valor de retorno
    $ret = null;

    // Si el metodo existe llamar
    if(method_exists($this, $actionMethod = "action"))
      call_user_func_array(array($this, $actionMethod), $params);

    // Ejecutar filtros para la acción
    if(!$this->executeFilters("before", $action, $params))
      return false;

    // Si el metodo existe llamar
    if(method_exists($this, $actionMethod = $this->getPrefix("actions") . $action)){
      $retTmp = call_user_func_array(array($this, $actionMethod), $params);
      // Sobre escribir la salida
      if($retTmp){
        echo $ret;
        $ret = $retTmp;
      }
    }

    // Ejecutar filtros para la acción por el método enviado
    if(!$this->executeFilters("before_{$method}", $action, $params))
      return false;

    // Si el metodo existe llamar correspondiente al metodo de la peticion
    if(method_exists($this, $actionMethod = $this->getPrefix("{$method}Actions") . $action)){
      $retTmp = call_user_func_array(array($this, $actionMethod), $params);
      // Sobre escribir la salida
      if($retTmp){
        echo $ret;
        $ret = $retTmp;
      }
    }

    $this->executeFilters("after_{$method}", $action, $params);
    $this->executeFilters('after', $action, $params);

    return $ret;

  }

  // Mezclador de configuraciones
  private static function mergeConf(array $confParent, array $conf){

    // Agregar items de
    foreach ($confParent as $key => $value)
      
      // Si no existe en la configuraicon hija se asigna.
      if(!isset($conf[$key]))
        $conf[$key] = $confParent[$key];

      // Si no se ha indicado una funcion para mezclar
      // continuar con la siguiente propiedad
      else if(!isset(self::$mergeFunctions[$key]))
        continue;

      // De lo contrario mezclar los datos 
      else
        $conf[$key] = call_user_func_array(self::$mergeFunctions[$key],
          array(
            $confParent[$key],
            $conf[$key]
          )
        );

    return $conf;

  }

  // Devuelve la configuracion para un controlador
  // Ademas incluye el archivo conrrespondiente
  public static function includeControl($control){

    // Obtener configuraciones del controlador
    $confs = Am::getAttribute("control");

    // Obtener valores por defecto
    $defaults = itemOr("defaults", $confs, array());

    // Si no existe configuracion para el controlador
    $conf = itemOr($control, $confs, array());

    // Si no es un array, entonces el valor indica el path del controlador
    if(is_string($conf))
      $conf = array("root" => $conf);

    // Mezclar con el archivo de configuracion en la raiz del
    // controlador.
    if(is_file($realFile = "{$conf["root"]}.control.php"))
      $conf = self::mergeConf($conf, require($realFile));

    // Si tiene no tiene padre o si el padre esta vacío
    // y se mezcla con la configuracion por defecto
    if(!isset($conf["parent"]) || empty($conf["parent"])){
      
      // Mezclar con valores por defecto
      $conf = self::mergeConf($defaults, $conf);

      // Obtener el nombre real del controlador
      $controlName = itemOr("name", $conf, $control);

    // Mezclar con configuracion del padre
    }else{

      // Obtener la configuracion del padre
      $confParent = self::includeControl($conf["parent"]);

      // Agregar carpeta de vistas por defecto del padre.
      $confParent["paths"][] = $confParent["root"];
      $confParent["paths"][] = $confParent["root"] . $confParent["views"];

      // Obtener el nombre real del controlador antes de mezclar con el padre
      $controlName = itemOr("name", $conf, $control);

      // Mezclar con la configuracion del padre
      $conf = self::mergeConf($confParent, $conf);

    }

    // Obtener la ruta del controlador
    // Incluir controlador si existe el archivo
    if(is_file($controlFile = "{$conf["root"]}{$controlName}.control.php")){
      $conf["name"] = $controlName;
      require_once $controlFile;
    }

    // Incluir como extension
    Am::load($conf["root"]);
    
    // Retornar la configuracion obtenida
    return $conf;

  }

  // Funcion para atender las respuestas por controlador.
  // Recive el nombre del controlador, la accion a ejecutar,
  // Los parametros y el entorno
  public static function response($control, $action, array $params, array $env){

    // Valores por defecto
    $conf = array_merge(
      // Incluye el controlador y devuelve la configuracion para el mismo
      self::includeControl($control),
      // Asignar vista que se mostrará
      array(
        "view" => self::getViewName($action),
      )
    );

    // Si no se puede instanciar el controlador retornar false.
    if(null === ($control = Am::getInstance("{$conf["name"]}Control", $conf)))
      return false;

    // Despachar la accion
    $control->dispatch($action, $env, $params);
    
    return true;

  }

}
