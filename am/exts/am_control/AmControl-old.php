<?php
/**
 * Manejo de las cabeceras de las respuestas
 * 
 * Agrega la cabecera 'header' a la lista de header que se incluiran en la
 * respuestas. Si key es recibida, indicara elnombre de la cabecera
 **/

/**
 * @@return     asdasd as dasd as asdf asdf
 * @@description  Esta clase es utilizada para despachar las peticiones
 *          que se realizan
 **/

class AmControl extends AmHash{
  
  public static $initConf = array(
    
    // Domain
    // 'domain' => 'localhost',
    
    'icon' => '/media/images/favicon.png',  // Icon
    'title' => 'Amathista', // Title pages
    'author' => '',         // Icon
    'description' => '',    // Description
    'contentType' => 'text/html', // ContentType
    
    // Template (may be include extension file)
    // 'tpl' => 'tpl.php',
    
    // State
    'state' => 'development',
    'langs' => array(),
    'values' => array(),
    'actions' => array(),

    // // Cores classes name list to include. Is merge with parent configuration
    // 'core' => array(
    //   /*
    //    * // indicates don't to be loaded parent configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.:
    //    * array('AmBox')
    //    */
    // ), 
    
    // // Helpers list to include. Is merge with parent configuration
    // 'helpers' => array(
    //   /*
    //    * // indicates don't to be loaded parent configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.:
    //    * array('jQuery', 'dataTable')
    //    */
    // ), 
    
    // Langs files to include. Is merge with parent configuration
    // 'langs' => array('site'
      
    //    * // indicates don't to be loaded parent configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.:
    //    * array('backend', 'admin')
       
    // ),
    
    // Default values. Is merge with parent configuration
    // 'values' => array(
      
    //    * // indicates don't to be loaded parent configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.:
    //    * array('name' => 'Peter', 'color' => 'red')
       
    // ),
    
    // // Formats. Always is merge with parent configuration
    // 'formats' => array(
    //   'date' => 'M-d-Y h:m:s'
    // ),
    
    // Objets types can be searched in the parent control. Always is merge with parent configuration
    // 'recursive' => array(
    //   'conf' => false,
    //   'views' => false,
    //   'tpls' => true,
    //   'partials' => true,
    //   'media' => true,
    //   'menues' => true,
    //   'mails' => true,
    // ),
    
    // // Folders name where ubicates each object type. Always is merge with parent configuration
    // 'folders' => array(
    //   'control' => 'control',
    //   'views' => 'views',
    //   'tpls' => 'views',
    //   'partials' => 'views',
    //   'media' => 'views',
    //   'menues' => 'menues',
    //   'mails' => 'mails',
    // ),
    
    // // Prefixs for each method types. Always is merge with parent configuration
    // 'prefixs' => array(
    //   'actions' => 'action_',
    //   'getActions' => 'get_',
    //   'postActions' => 'post_',
    //   'filters' => 'filter_',
    //   'sections' => 'section_',
    // ),
    
    // List headers to intclude. Is merge with parent configuration
    // 'headers' => array(
      
    //    * // indicates don't to be loaded parent headers configuration
    //    * 0 => false,
       
    // ),
    
    // Roles lists for controller
    // 'credentials' => false
      /*
       * // Inidcates dont't to be loaded parent credentials
       * array(false)
       * 
       * // no require authentication
       * false
       * 
       * // require authentication in all actions
       * true
       * 
       * // require authenticacion and credentiales in all actions
       * 'root'
       * 
       * // require authentication in all actions
       * array()
       * 
       * // require authenticacion and credentiales in all actions
       * array('super', 'admin')
       * 
       * // require authentication only in especifics actions
       * array(
       *    'only' => array('index', 'profile', 'exit')
       * )
       * 
       * // require authentication except in especifics actions
       * array(
       *    'except' => array('auth', 'newuser')
       * )
       * 
       * // require authentication and credentials only in especifics actions
       * array(
       *    'roles' => array('super', 'admin'),
       *    'only' => array('index', 'profile', 'exit')
       * )
       * 
       * // require authentication and credentials except in especifics actions
       * array(
       *    'roles' => array('super', 'admin'),
       *    'except' => array('auth', 'newuser')
       * )
       * 
       */
    // ,
    
    // // Filters configurations. Dont is merge with parent configuration
    // 'filters' => array(
      
    //    * // indicates to be loaded de parent configuration
    //    * 0 => true,
    //    * 
    //    * // e.g.: this filter excute {prefixFilter}filterName method before 
    //    * // from 'edit' and 'delete' actions
    //    * 'before' => array(   // OR 'before_get' OR 'before_post' OR 'after' OR 'after_get' OR 'after_post' 
    //    * 
    //    *    // List of array method an configuration
    //    *    'filterName' => array(              // Filter Name method
    //    *      'scope' => 'only',              // OR 'all' (default) for ignore 'to' param
    //    *      'to' => array('edit', 'delete')       // actions to which to apply the filter
    //    *      'redirect' => '/url/'             // URL to redirect if it not passes the filter
    //    *    )
    //    * )
       
    // ),
    
    // // Sections configurations
    // 'sections' => array(
    //   /*
    //    * // e.g.:
    //    * 'sectionName' => '<h1>Amathista</h1>'  // section with constant content
    //    * 'sectionName' => true          // dinamic section. call section method and render sectionName.php
    //    */
    // ),
    
    // // Routes. Dont is merge with parent configuration
    // 'routes' => array(
    //   /*
    //    * // indicates to be loaded de parent routes
    //    * 0 => true,
    //    * 
    //    * // e.g.:
    //    * 'auth' => 'user/auth'  // no begin and ending slashes
    //    */
    // ),
    
    // Default configuration for WebServices
    'servicesDefaults' => array(
      'content' => null,
      'type' => 'text'
    ),
    
    // Webservices configuration list. Dont is merge with parent configuration
    'services' => array(
      /*
       * // indicates to be loaded de parent configurations
       * 0 => true,
       * 
       * // e.g:
       * 'actionName' => array(
       *    'type' => 'json',     //Or 'text' 
       *    'content' => null
       * ),
       * 
       * // Only Methos POST e.g.: 
       * 'post_actionName' => array(...)
       */
    // ),
    
    // // Javascript files list to include in html request
    // 'js' => array(
    //   /*
    //    * // indicates don't to be loaded parent javascript files configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.: include de javascript file into media folder (no extension)
    //    * js/jquery-1.8.2,
    //    * 
    //    * // e.g.: include de javascript file into media folder
    //    * array(
    //    *    
    //    *    // resources type
    //    *    // 'global '(default) find file into root site
    //    *    // 'local' find file into current control folder
    //    *    // 'external' find file outside site. 'js' param may be complete url
    //    *    'type' => 'global',
    //    * 
    //    *    // Priority number (default: Am::MEDIUM). indicates the order for include the file
    //    *    // Can to use Am::VERY_HIGH=0, Am::HIGH=100, Am::MEDIUM=200, Am::LOW=300 and Am::VERY_LOW=400 const
    //    *    // The lower higher priority
    //    *    'priority' => 0,
    //    * 
    //    *    // resources name (no extension)
    //    *    'js' => 'js/main'
    //    * )
    //    */
    // ),
    
    // // CSS files List to include 
    // 'css' => array(
    //   /*
    //    * // indicates don't to be loaded parent css files configuration
    //    * 0 => false,
    //    * 
    //    * // e.g.: include de javascript file into media folder (no extension)
    //    * css/grid,
    //    * 
    //    * // e.g.: include de javascript file into media folder
    //    * array(
    //    *    
    //    *    // resources type
    //    *    // 'global '(default) find file into root site
    //    *    // 'local' find file into current control folder
    //    *    // 'external' find file outside site. 'css' param may be complete url
    //    *    'type' => 'global',
    //    * 
    //    *    // Priority number (default: Am::MEDIUM). indicates the order for include the file
    //    *    // Can to use Am::VERY_HIGH=0, Am::HIGH=100, Am::MEDIUM=200, Am::LOW=300 and Am::VERY_LOW=400 const
    //    *    // The lower higher priority
    //    *    'priority' => 0,
    //    * 
    //    *    // resources name (no extension)
    //    *    'css' => 'css/reset'
    //    * )
    //    */
    // ),

    // 'mailsDefaults' => array(
    //   'smtp' => false
    // ),
      
    //   // Configuration for each mail.
    // 'mails' => array(
      
    //    * e.g.: mails configuration
    //    * 'index' => array(  // Nombre del mensaje
    //    * 
    //    *    // Remitente del mensaje
    //    *    'from' => 'test@amathista.com', // Correo: test@amathista.com, nombre: test@amathista.com
    //    *    
    //    *    'from' => array(
    //    *      'user' => 'test@amathista.com',
    //    *      'name' =>   'Test Amathista'
    //    *    ),
    //    * 
    //    *    'charSet' => 'UTF-8'
    //    * 
    //    *    // Subject del mensaje
    //    *    'subject' => 'Mensaje de Prueba de Amathista', 
    //    * 
    //    *    // Pantilla para el mensaje
    //    *    'tpl' => 'tpl',
    //    * 
    //    *    // Cuerpo Alternativo
    //    *    'altBody' => '',
    //    * 
    //    *    // Nombre del mensaje (Para buscar la vista a renderizar)
    //    *    'message' => 'confirmar',
    //    * ),
       
    //   ),
    
    // Configuration for each action.
    // 'actions' => array(
      
    //    * e.g.: action configuration
    //    * 'index' => array(
    //    * 
    //    *    // Params speficications equals to controller configuration
    //    *    'icon' => ...,
    //    *    'description' => ...,
    //    *    'author' => ...,
    //    *    'title' => ...,
    //    *    'contentType' => ...
    //    *    'tpl' => ...,
    //    *    'core' => ....,
    //    *    'hepers' => ....,
    //    *    'langs' => ....,
    //    *    'values' => ....,
    //    *    'formats' => ....,
    //    *    'headers' => ....,
    //    *    'credentials' => ....,
    //    *    'filter' => ....,
    //    *    'sections' => ....,
    //    *    'js' => ....,
    //    *    'css' => ....,
    //    * ),
    //    * 'get_index' => array(...)    // Only for action index for get method
       
    // )
    
  );
  
  // public static $requestPath = null;
  // public static $control = null;
  // public static $action = null;
  // public static $method = null;
  // public static $params = null;
  // public static $crendentialsInstance = null;
  
  // protected $server = null;
  // protected $get = null;
  // protected $post = null;
  // protected $session = null;
  // protected $cookie = null;
  // protected $request = null;
  // protected $env = null;
  // protected $files = null;
  // protected $args = null;
  
  // private $render = null;
  // private $conf = null;
  // private $name = null;
  // private $fileForResponse = null;
  // private $path = null;
  
  // final public function getCredentials(){
    
  //   if(!isset(self::$crendentialsInstance)){
  //     self::$crendentialsInstance = new AmCredentials;
  //     self::$crendentialsInstance->setCredentialsClassDefault();
  //   }
    
  //   return self::$crendentialsInstance;
  
  // }

  // final public function getProfile(){
  //   return $this->getCredentials()->getCredentials();
  // }

  // final public function hasCrendentials($credential){
  //   return $this->getCredentials()->hasCredentials($credential);
  // }

  // final public function isAuth(){
  //   return $this->getCredentials()->isAuth();
  // }
  
  // final protected function conf(){ return $this->conf; }
  // final protected function name(){ return $this->name; }
  final protected function fileForResponse(){ return $this->fileForResponse; }
  // final protected function root(){ return $this->root; }
  // final public function path(){ return $this->path; }
  
  // final public function domain($domain = null){ return $this->conf->attr('domain', $domain); }
  // final protected function icon($icon = null){ return $this->conf->attr('icon', $icon); }
  // final protected function description($description = null){ return $this->conf->attr('description', $description); }
  // final protected function author($author = null){ return $this->conf->attr('author', $author); }
  // final protected function contentType($contentType = null){ return $this->conf->attr('contentType', $contentType); }
  // final protected function title($title = null){ return $this->conf->attr('title', $title); }
  // final protected function state($state = null){ return $this->conf->attr('state', $state); }
  final protected function servicesDefaults($servicesDefaults = null){ return $this->conf->attr('servicesDefaults', $servicesDefaults); }
  
  // final protected function recursive($key = null){ return itemOr($this->conf->recursive, $key, true); }
  // final protected function folders($key = null){ return itemOr($this->conf->folders, $key, '/'); }
  // final protected function prefixs($key = null){ return itemOr($this->conf->prefixs, $key); }
  final protected function headers($key = null){ return itemOr($this->conf->headers, $key); }
  // final protected function sections($key = null){ return itemOr($this->conf->sections, $key); }
  // final protected function filters($key = null){ return itemOr($this->conf->filters, $key, array()); }
  // final protected function routes($key = null){ return itemOr($this->conf->routes, $key); }
  // final public function mails($key = null){ return itemOr($this->conf->mails, $key, array()); }
  // final public function mailsDefaults($mailsDefaults = null){ return $this->conf->attr('mailsDefaults', $mailsDefaults); }
    
  // final protected function getHttpReferer(){
  //   return $this->server->HTTP_REFERER;
  // }
  
  // final static public function currentControl(){
  //   return self::$control;
  // }
  
  // final protected function formats($key = null, $value = null){
    
  //   if(!isset($value)){
      
  //     return itemOr($this->conf->formats, $key);
      
  //   }
    
  //   $this->conf->formats[$key] = $value;
    
  // }
  
  // final public function resources($ext){
    
  //   return $this->conf->$ext;
    
  // }
  
  // final private function haveResource($ext, $resource, $type){
    
  //   foreach($this->conf->$ext as $v){
  //     if($v[$ext] == $resource && $v['type'] == $type){
  //       return true;
  //     }
  //   }
  //   return false;
    
  // }
  
  // final public function addResource($ext, $resource, $priority = Am::MEDIUM, $type = 'global', $unique = true){
    
  //   if(!is_numeric($priority)){
  //     $type = $priority;
  //     $priority = Am::MEDIUM;
  //   }
    
  //   if(!$unique || !$this->haveResource($ext, $resource, $type)){
  //     array_push($this->conf->$ext, array($ext => $resource, 'type' => $type, 'priority' => $priority));
  //     return true;
  //   }
    
  //   return false;
    
  // }
  
  // final public function deleteResource($ext, $resource = null, $type = 'local', $num = 0){
    
  //   $ret = array();
    
  //   if(isset($resource)){
      
  //     $i = 0;
  //     foreach($this->conf->$ext as $v){
  //       if($v[$ext] == $resource && $v['type'] == $type){
  //         $i++;
  //         if($num != 0 && $i != $num){
  //           $ret[] = $v;
  //         }
  //       }
  //     }
      
  //   }
    
  //   $this->conf->$ext = $ret;
    
  // }
  
  
  
  /**
   * Manejo de las cabeceras de las respuestas
   * 
   * Agrega la cabecera 'header' a la lista de header que se incluiran en la
   * respuestas. Si key es recibida, indicara elnombre de la cabecera
   **/
  // final protected function addHeader($header, $key = null){

  //   if(empty($key)){
  //     $this->conf->headers[] = $header;
  //   }else{
  //     $this->conf->headers[$key] = $header;
  //   }
    
  //   return $this;

  // }

  // /**
  //  * Elimina la cabecera guardada en 'key'
  //  **/
  // final protected function delHeader($key){

  //   unset($this->conf->headers[$key]);
  //   return $this;

  // }

  // *
  //  * Elimina todas las cabeceras agregadas
  //  *
  // final protected function clearHeader(){

  //   $this->conf->headers = array();
  //   return $this;

  // }

  // /**
  //  * Incluye las cabeceras agregadas, la cabecera que indica el tipo
  //  **/
  // final protected function includeHeaders(){

  //   $this->addHeader('content-type: ' . $this->contentType());
    
  //   foreach($this->conf->headers as $header){
  //     header($header);
  //   }

  // }
  
  /**
   * Control y manejo de secciones
   * Esta funcion inserta lo generado por la funcion 'callback' en la secion
   * 'name'.
   **/
  // final protected function setSectionContent($name, $content){
    
  //   $this->conf->sections[$name] = $content;
    
  // }

  // /**
  //  * Inicializa una seccion
  //  **/
  // final protected function initSection(){
    
  //   ob_start();
    
  //   return $this;

  // }

  // /**
  //  * Inserta en la seccion 'name' lo generado hasta el momento siempre y
  //  * cuando este dentro de un nivel
  //  **/
  // final public function endSection($name = null, $rw = true){
    
  //   $return = ob_get_clean();

  //   if(empty($name)){

  //     return $return;

  //   }
    
  //   if(!isset($this->conf->sections[$name]) || $rw){
  //     $this->conf->sections[$name] = $return;
  //   }
    
    
  //   return $this;

  // }
  
  // final public function getSection($sectionName, $renderPartial = null, $args = array()){
    
  //   if(is_array($renderPartial)){
  //     $args = $renderPartial;
  //     $renderPartial= null;
  //   }
    
  //   if(!isset($renderPartial)){
  //     $renderPartial = "$sectionName.php";
  //   }
    
  //   $funcSectionName = $this->prefixs('sections') . $sectionName;
    
  //   if(method_exists($this, $funcSectionName)){
    
  //     $this->initSection();
  //     $this->$funcSectionName();
  //     echo $this->partial($renderPartial, $args);
  //     return $this->endSection();
      
  //   }
    
  //   return null;
    
  // }
  
  // /**
  //  * Control de filtros
  //  **/
  // final protected function addFilter($nameFilter, $state, $to = 'all', $except = array(), $redirect = null){
    
  //   if(is_array($to)){
  //     $scope = 'only';
  //   }else{
  //     $scope = $to;
  //     $to = array();
  //   }
    
  //   if(!isset($this->conf->filters[$state][$nameFilter])){
      
  //     $this->conf->filters[$state][$nameFilter] = array(
  //       'scope' => $scope,
  //       'to' => array(),
  //       'except' => $except,
  //       'redirect' => $redirect
  //     );
      
  //   }
    
  //   foreach($to as $m){
  //     $this->conf->filters[$state][$nameFilter]['to'][] = $m;
  //   }
    
  //   $this->conf->filters[$state][$nameFilter]['to'] =
  //       array_unique($this->conf->filters[$state][$nameFilter]['to']);
    
  // }
  
  // final protected function addBeforeFilter($nameFilter, $to = 'all', $except = array(), $redirect = null){
  //   $this->addFilter($nameFilter, 'before', $to, $except, $redirect);
  // }
  
  // final protected function addBeforeGetFilter($nameFilter, $to = 'all', $except = array(), $redirect = null){
  //   $this->addFilter($nameFilter, 'before_get', $to, $except, $redirect);
  // }
  
  // final protected function addBeforePostFilter($nameFilter, $to = 'all', $except = array(), $redirect = null){
  //   $this->addFilter($nameFilter, 'before_post', $to, $except, $redirect);
  // }
  
  // final protected function addAfterFilter($nameFilter, $to = 'all', $except = array()){
  //   $this->addFilter($nameFilter, 'after', $to, $except);
  // }
  
  // final protected function addAfterGetFilter($nameFilter, $to = 'all', $except = array()){
  //   $this->addFilter($nameFilter, 'after_get', $to, $except);
  // }
  
  // final protected function addAfterPostFilter($nameFilter, $to = 'all', $except = array()){
  //   $this->addFilter($nameFilter, 'after_post', $to, $except);
  // }
  
  // final protected function executeFilters($state, $methodName, $extraParams){
    
  //   if(isset($this->conf->filters[$state])){
      
  //     foreach($this->conf->filters[$state] as $filterName => $filter){
        
  //       if(($filter['scope'] == 'all' || in_array($methodName, $filter['to']))){
  //         if(!isset($filter['except']) || !in_array($methodName, $filter['except'])){
            
  //           $ret = call_user_func_array(array(&$this, $this->prefixs('filters') . $filterName), $extraParams);
            
  //           if($ret === false && $state == 'before'){
              
  //             if(isset($filter['redirect'])){
  //               $this->redirect(url($filter['redirect']));
  //             }else{
  //               return false;
  //             }


  //           }
  //         }
  //       }
        
  //     }
      
  //   }
    
  //   return true;
    
  }
  
  /**
   * Redirecciona a otra pagina
   **/
  // final public function redirect($url){
    
  //   if(!empty($url)){
      
  //     header("location: $url");
  //     exit();
      
  //   }
    
  // }

  /**
   * Redireccion a a una pagina siempre y cuando la condicion sea true
   **/
  // final protected function redirectIf($condition, $url){

  //   if($condition){

  //     $this->redirect($url);
      
  //   }

  // }

  /**
   * Redireccion a a una pagina siempre y cuando la condicion sea false
   **/
  // final protected function redirectUnless($condition, $url){

  //   $this->redirectIf(!$condition, $url);

  // }
  
  final protected function responseAsService($conf = array()){
    
    if($this->conf->services === false){
      $this->conf->services = $this->conf->servicesDefaults;
    }
    
    if(is_array($conf)){
      $this->conf->services = array_merge($this->conf->services, $conf);
    }else{
      $this->conf->services = $conf;
    }
    
    return $this->conf->services;
    
  }
  
  // final protected function respondeWithFile($file, $mimeType = null, $name = null, $attachment = false){
    
  //   if(!isset($mimeType)){
  //     $mimeType = AmFileSystem::mimeType($file);
  //   }
    
  //   if(!isset($name)){
  //     $name = basename($file);
  //   }
    
  //   $this->contentType($mimeType);
  //   $this->addHeader('Content-Disposition: ' . ($attachment ? 'attachment;' : '') . 'filename="'.$name.'"');
  //   $this->addHeader('Content-Transfer-Encoding: binary');
  //   $this->addHeader('Expires: 0');
  //   $this->addHeader('Cache-Control: must-revalidate');
  //   $this->addHeader('Pragma: public');
  //   $this->addHeader('Content-Length: ' . filesize($file));
    
  //   $this->fileForResponse = $file;

  // }
  
  /**
   * Renderiza una la vista de una action. Si se omite el
   * nombre del controlador se tomara por defecto el nombre del controlador
   * de la vista actual. Si se omite el action se tomara el nombre de la
   * Vista actual. Si no se omite "method' se toma el nombre de la vista a
   * buscar sera 'method'."_".'action'.
   **/
  // final protected function render($view = ''){
    
  //   if(!isset($this->render)){
      
  //     $this->render = $this->_render($view);
      
  //   }
    
  //   return $this->render;
    
  // }
  
  // // Obtiene el nombre de la vista
  // final private function _render($view = ''){
    
  //   if(!isset($view)){
  //     return '';
  //   }
    
  //   if($view === ''){
  //     return $this->render;
  //   }
    
  //   return $this->pathFile($view, 'views');
    
  // }
  
  // final protected function partial($partial, $args = array()){
    
  //   $renderPath = $this->pathFile($partial, 'partials');
    
  //   $args = array_merge($this->toArray(), $args);
  //   extract($args);
    
  //   $this->initSection();
    
  //   if($renderPath){
  //     include $renderPath;
  //   }
        
  //   return $this->endSection();
    
  // }
  
  /**
   * Acciones base
   **/
  
  // final protected function media(){
    
  //   $this->tpl(null);
    
  //   $file = $this->pathFile(implode('/', func_get_args()), 'media');
    
  //   if(is_file($file)){
      
  //     $this->respondeWithFile($file);
      
  //   }else{
      
  //     $this->addHeader("HTTP/1.0 404 Not Found");
  //     $this->addHeader("Status: 404 Not Found");
      
  //   }
    
  // }
  
  // final protected function parentControl(){
  //   return Am::getInstances(get_parent_class($this));
  // }
  
  // final protected function childControl($name){
    
  //   $controlName = get_class($this) . _tcc($name, true);
  //   $control = Am::getInstances($controlName);
    
  //   if(isset($control)){

  //     return $control; 

  //   }else{
      
  //     $inFolder = trim($this->folders('control'));
      
  //     if(!empty($inFolder)){
  //       $inFolder = "$inFolder/";
  //     }
      
  //     $controlPath = $this->root() . "/$inFolder" . _fcc($name);
      
  //     if(Am::control("$controlPath/$controlName")){

  //       $control = Am::instances($controlName);
  //       $control->initialize($controlPath);
        
  //     }
      
  //   }
    
  //   return $control;
    
  // }
  
  // final protected function findControl(array $file){
    
  //   if(count($file)>1){
      
  //     $folder = array_shift($file);
  //     $control = $this->childControl($folder);

  //     if(isset($control)){

  //       return $control->findControl($file);

  //     }

  //     array_unshift($file, $folder);
      
  //   }
    
  //   return array($this, $file);
    
  // }
  
  // final protected function findFile($file, $in, $withRoot = true, $recursive = false){
    
  //   $recursiveThis = $recursive || $this->recursive($in);
    
  //   $inFolder = trim($this->folders($in));

  //   if(!empty($inFolder)){
  //     $inFolder = "$inFolder/";
  //   }

  //   $filePath = ($withRoot? (SITE_FOLDER . Am::APP_FOLDER) : '') . $this->root() . "/$inFolder" . implode('/', $file);
    
  //   $filePathAbsolute = SITE_FOLDER . Am::APP_FOLDER . $this->root() . "/$inFolder" . implode('/', $file);
    
  //   if(!is_file($filePathAbsolute) && !is_dir($filePathAbsolute) && $recursiveThis && get_class($this) != 'Control'){
  //     return $this->parentControl()->findFile($file, $in, $withRoot, $recursive);
  //   }
    
  //   return $filePath;
    
  // }
  
  // final public function pathFile($file, $in, $withRoot = true, $recursive = false){
    
  //   if(!is_array($file)){
  //     $file = explode('/', $file);
  //   }
    
  //   $control = $this;
  //   $path = array();
    
  //   foreach($file as $folder){
  //     if($folder == '..'){
  //       if(!empty($path)){
  //         array_pop($path);
  //       }elseif(get_class($control) != 'Control'){
  //         $control = $control->parentControl();
  //       }
  //     }elseif(!empty($folder) && $folder != '.'){
  //       $path[] = $folder;
  //     }
  //   }
    
  //   $control = $file[0] == '' ? Am::getInstances('Control') : $control;
    
  //   list($control, $path) = $control->findControl($path);
    
  //   return $control->findFile($path, $in, $withRoot, $recursive);
    
  // }
  
  private static function mergeConf($conf, $confParent){
    
    foreach(array('filters', 'routes', 'services') as $i){
      
      if(isset($conf[$i][0]) && $conf[$i][0] === true){
        if(isset($confParent[$i])){
          unset($conf[$i][0]);
          $conf[$i] = array_merge($confParent[$i], $conf[$i]);
        }
      }else{
        $confParent[$i] = array();
      }
      
    }
    
    foreach(array('langs', 'core', 'helpers', 'values', 'css', 'js', 'headers') as $i){
      
      if(isset($confParent[$i])){
        $sw = !isset($conf[$i][0]) || $conf[$i][0] !== false;
        if(isset($conf[$i][0]) && $conf[$i][0] === false){
          unset($conf[$i][0]);
        }
        if($sw){
          $conf[$i] = isset($conf[$i])? array_merge($confParent[$i], $conf[$i]) : $confParent[$i];
        }
        
      }

    }

    foreach(array('recursive', 'folders', 'prefix', 'formats') as $i){
      
      if(isset($confParent[$i]) && isset($conf[$i])){
        $conf[$i] = array_merge($confParent[$i], $conf[$i]);
      }

    }

    $conf['mailsDefaults'] = array_merge(itemOr($confParent, 'mailsDefaults', array()), itemOr($conf, 'mailsDefaults', array()));
    $conf['servicesDefaults'] = array_merge(itemOr($confParent, 'servicesDefaults', array()), itemOr($conf, 'servicesDefaults', array()));
//    
//    if(isset($conf['services'][0]) && $conf['services'][0] === true){
//      unset($conf['services'][0]);
//      $confParent['services'] = array();
//    }
//    
    $conf['services'] = array_merge(itemOr($conf, 'services', array()), itemOr($confParent, 'services', array()));

    $conf['mails'] = array_merge(itemOr($conf, 'mails', array()), itemOr($confParent, 'mails', array()));
    $conf['mails'] = self::parseMails($conf['mails'], $conf['mailsDefaults']);
    
    $conf['services'] = self::parseServices(itemOr($conf, 'services', array()), $conf['servicesDefaults']);

    $conf['credentials'] = self::parseCredentials(itemOr($conf, 'credentials'));
    $confParent['credentials'] = self::parseCredentials(itemOr($confParent, 'credentials'));
    
    if(is_array($conf['credentials']) && is_array($confParent['credentials'])){
      
      if(isset($conf['credentials'][0]) && $conf['credentials'][0] !== false){
        $conf['credentials'] = array_merge($conf['credentials'], $confParent['credentials']);
      }
      
    }elseif($conf['credentials'] === false){
      
      $conf['credentials'] = $confParent['credentials'];
      
    }

    return array_merge($confParent, $conf);
    
  }
  
  private static function parseCredentials($credentials){
    
    if(!isset($credentials) || $credentials === false){
      return false;
    }
    
    if($credentials === true){
      return array();
    }
    
    if(!is_array($credentials) || isset($credentials['roles']) || isset($credentials['only']) || isset($credentials['except'])){
      return array($credentials);
      
    }
    
    return $credentials;
    
  }
  
  private static function parseServices($services, array $servicesDefaults){
    
    foreach($services as $i => $s){
      
      if($s === true){
        $services[$i] = $servicesDefaults;
      }else{
        $services[$i] = array_merge($servicesDefaults, $services[$i]);
      }
      
    }
    
    return $services;
      
  }

  private static function parseMails($mails, array $mailsDefaults){
    foreach($mails as $i => $s){
      $mails[$i] = array_merge($mailsDefaults, $mails[$i]);
    }
    
    return $mails;
    
  }
  
  // public function initialize($root = ''){
    
  //   $this->root = $root;
    
  //   $conf = readConf(SITE_FOLDER . "/control{$this->root}/conf");
    
  //   if(get_class($this) == 'Control'){
      
  //     if(isset($_SERVER['SERVER_NAME'])){
  //       $conf['domain'] = $_SERVER['SERVER_NAME'];
  //     }
      
  //     $this->path = array();
  //     $confParent = self::$initConf;
      
  //   }else{
      
  //     $parent = $this->parentControl();
      
  //     $confParent = $parent->conf()->toArray();
      
  //     $this->name = _fcc(substr_replace(get_class($this), '', 0, strlen(get_parent_class($this))));
  //     $this->path = $parent->path();
  //     array_push($this->path, $this->name());
      
  //   }

  //   $this->conf = new AmHash(self::mergeConf($conf, $confParent));
    
  //   if(isset($parent)){
  //     $this->conf->title = str_replace('{prev}', $parent->title(), $this->conf->title);
  //   }
    
  // }
  
  // final public function respondeWithAction($url, $method = null){
    
  //   $url = explode('/', $url);
  //   $params = array();
  //   $control = trim($params[0]) == '' ? Am::instances('Control') : $this;
    
  //   foreach($url as $param){
  //     $param = trim($param);
  //     if($param == '..'){
  //       array_pop($params);
  //     }elseif($param != '.' && $param == ''){
  //       $params[] = $param;
  //     }
  //   }
    
  //   return $control->dispatch($params, $method);
    
  // }
  
  // final public function dispatch(array $extraParams, $method = null){
    
  //   $action = 'index';
    
  //   if(!isset($method)){
  //     $method = self::$method;
  //   }
    
  //   if(!empty($extraParams)){
      
  //     $action = array_shift($extraParams);
      
  //     if($action == '..'){
        
  //       if(get_class($this) == 'Control'){
  //         return $this->parentControl()->dispatch($extraParams);
  //       }
        
  //       return $this->dispatch($extraParams);
  //     }
      
  //     $route = $this->routes(_fcc($action));
  //     if(isset($route)){
        
  //       return $this->dispatch(array_merge(explode('/', $route), $extraParams));
        
  //     }
      
  //     $control = $this->childControl($action);
      
  //     if(isset($control)){
        
  //       $conf = $control->conf();
        
  //       switch ($conf->state) {
  //         case 'development':
  //           error_reporting(E_ALL);
  //           break;
  //         default:
  //           error_reporting(0);
  //           break;
  //       }
        
  //       return $control->dispatch($extraParams);
        
  //     }
      
  //   }
    
  //   $actionName = _tcc($action);
  //   $controlMethods = array_merge(
  //       array_diff(
  //         get_class_methods(get_class($this)),
  //         get_class_methods(get_parent_class('Control'))));
    
  //   if($actionName != 'media' &&
  //       !in_array($this->prefixs('actions') . $actionName , $controlMethods) &&
  //       !in_array($this->prefixs("{$method}Actions")  . $actionName, $controlMethods)){
        
  //       array_unshift($extraParams, $action);
  //       $action = 'index';
        
  //   }
    
  //   $redirect = $this->conf->redirect;
    
  //   if(isset($redirect[$action])){
      
  //     $redirect = $redirect[$action];
      
  //     if(is_array($redirect)){
  //       if(!isset($redirect['method'])){
  //         $redirect['method'] = $method;
  //       }
  //     }else{
  //       $redirect = array(
  //         'url' => $redirect,
  //         'method' => $method
  //       );
  //     }
      
  //     if($method == $redirect['method']){
        
  //       array_shift($extraParams);
        
  //       $ret = array();
  //       $redirect = explode('/', $redirect['url']);
        
  //       foreach ($redirect as $value) {
  //         $value = trim($value);
  //         if($value == '..'){
  //           array_pop($ret);
  //         }elseif($value != '.'){
  //           $ret[] =  $value;
  //         }
  //       }
        
  //       foreach ($ret as $value) {
  //         if(trim($value) != ''){
  //           array_unshift($extraParams, $value);
  //         }
  //       }
        
  //       if(trim($ret[0]) == ''){
  //         Am::instances('Control')->dispatch($extraParams);
  //       }else{
  //         $this->dispatch($extraParams);
  //       }
        
  //       return;
        
  //     }
      
  //   }
    
  //   $this->callAction($method, $action, $extraParams);
    
  // }
  
  /**
   * Realiza el despacho de a una peticion. la accion solicitada se indica
   * por ''action', solicitada por el metodo 'method' y con los parametros
   * 'params
   **/
  // final private function callAction($method, $action, $args){
    
  //   self::$control = $this;
  //   self::$action = $action;
  //   self::$method = $method;
  //   self::$params = $args;

  //   $this->conf->actions[$action] = itemOr($this->conf->actions, $action, array());
  //   $this->conf->actions["{$method}_{$action}"] = itemOr($this->conf->actions, "{$method}_{$action}", array());
    
  //   $this->conf->actions[$action]['filters'] = $this->conf->actions[$action]['routes'] = $this->conf->actions[$action]['services'] = array(true);
  //   $this->conf->actions["{$method}_{$action}"]['filters'] = $this->conf->actions["{$method}_{$action}"]['routes'] = $this->conf->actions["{$method}_{$action}"]['services'] = array(true);

  //   $this->conf = new AmHash(self::mergeConf($this->conf->actions[$action], $this->conf->toArray()));
  //   $this->conf = new AmHash(self::mergeConf($this->conf->actions["{$method}_{$action}"], $this->conf->toArray()));

  //   $services = itemOr($this->conf->services, $action, false);
  //   $services = itemOr($this->conf->services, "{$method}_{$action}", $services);
  //   $this->conf->services = $services;
  //   unset($services);
    
  //   $this->loadCore();
  //   $this->loadHelpers();
  //   $this->loadLangs();
  //   $this->loadValues();
    
  //   $this->server = new AmHash($_SERVER);
  //   $this->get = new AmHash($_GET);
  //   $this->post = new AmHash($_POST);
  //   $this->session = new AmHash($_SESSION);
  //   $this->cookie = new AmHash($_COOKIE);
  //   $this->request = new AmHash($_REQUEST);
  //   $this->env = new AmHash($_ENV);
  //   $this->files = new AmHash($_FILES);
  //   $this->args = $args;
    
  //   $action = _tcc($action);

  //   $this->initSection();
    
  //   if($action == 'media'){
      
  //     call_user_func_array(array(&$this, 'media'), $args);

  //   }else{
      
  //     $actionName = $this->prefixs('actions') . $action;
  //     $methodName = "{$method}Action";
  //     $actionMethodName = $this->prefixs("{$method}Actions") . $action;
      
  //     // Verificar las credenciales
  //     $this->getCredentials()->checkCredentials($this->conf->credentials, $action);
      
  //     $ret = call_user_func_array(array(&$this, "action"), $args);
      
  //     if($this->executeFilters('before', $action, $args)){
        
  //       $error = true;
        
  //       if(method_exists($this, $actionName)){
  //         $error = false;
  //         $retTmp = call_user_func_array(array(&$this, $actionName), $args);
  //         isset($retTmp) AND $ret = $retTmp;
  //       }
        
  //       if(method_exists($this, $methodName)){
  //         $error = false;
  //         $retTmp = call_user_func_array(array(&$this, $methodName), $args);
  //         isset($retTmp) AND $ret = $retTmp;
  //       }
        
  //       if($this->executeFilters("before_{$method}", $action, $args)){
          
  //         // Si no existe el metodo ni la vista de la accin solicitada, entonces
  //         // Se debe renderizar el error.
          
  //         if(method_exists($this, $actionMethodName)){
            
  //           $error = false;
  //           $retTmp = call_user_func_array(array(&$this, $actionMethodName), $args);
  //           isset($retTmp) AND $ret = $retTmp;
            
  //         }

  //         $this->executeFilters("after_{$method}", $action, $args);
  //         $this->executeFilters('after', $action, $args);

  //         if ('' === ($render = $this->render())) {

  //           $error = false;

  //         }elseif (is_file($render = $this->render())){

  //           $error = false;

  //         }elseif (is_file($render = $this->_render("$action.php"))){

  //           $error = false;

  //         }
          
  //         if(!$error){
            
  //           if($this->conf->services === false && !is_array($ret) && !is_object($ret)){
              
  //             echo $ret;
              
  //             // Se generan nuevas variables basados en las variables generadas
  //             // durante la ejecucion del metodo

  //             extract($this->toArray());

  //             if(is_file($render)){
                
  //               include $render;
                
  //             }
              
  //           }
            
  //         }else{
            
  //           array_unshift($args, lang('not_found_action') . ': '. get_class($this) ."->". $action);
  //           call_user_func_array(array(&$this, 'actionError404'), $args);
            
  //         }

  //       }

  //     }

  //   }
    
  //   $this->endSection('body');
    
  //   if($this->fileForResponse()){
      
  //     // Incluir cabeceras
  //     $this->includeHeaders();
      
  //     readfile($this->fileForResponse());
      
  //   }else{
      
  //     if(isset($ret)){
  //       if(is_object($ret)) $ret = (array)$ret;
  //       if(is_array($ret)) $this->responseAsService(array(
  //           'content' => $ret
  //       ));
  //     }
      
  //     if($this->conf->services !== false){
      
  //       $type = $this->conf->services['type'];
  //       $content = $this->conf->services['content'];
        
  //       if(!isset($content)){
  //         $content = $this->conf->sections['body'];
  //       }

  //       switch ($type){
  //         case 'text':
  //           $contentType = 'text/plain';
  //           $fnEncode = 'print_r';
  //           break;
  //         case 'json':
  //           $contentType = 'application/json';
  //           $fnEncode = 'json_encode';
  //           break;
  //         default :
  //           $contentType = 'text/html';
  //           $fnEncode = 'var_dump';
  //       }
        
  //       $this->contentType($contentType);
  //       $this->conf->sections['body'] = $fnEncode($content);

  //     }
      
  //     if(in_array($this->contentType(), array('text/plain', 'application/json', 'application/x-javascript', 'text/javacript'))){
      
  //       $this->includeHeaders();
  //       echo $this->conf->sections['body'];

  //     }elseif(in_array($this->contentType(), array('text/html'))){
        
  //       $this->includeHeaders();
        
  //       // Configurar parametros para el template
  //       $sections = new AmHash;

  //       foreach($this->conf->sections as $k => $content){
  //         if($content === true){
  //           $content = $this->getSection($k);
  //         }
  //         $sections->$k = $content;
  //       }

  //       $pathTemplate = $this->pathFile($this->conf->tpl, 'tpls');

  //       if(!empty($this->conf->tpl) && is_file($pathTemplate)){

  //         include $pathTemplate;

  //       }else{

  //         echo $sections->body;

  //       }
  //     }else{

  //       echo lang('unknown_content_type') . " '{$this->contentType()}'";

  //     }
    
  //   }
    
  // }
  
  // final public static function processRequest(){
    
  //   error_reporting(E_ALL);
    
  //   $pathinfo = substr_replace($_SERVER['REDIRECT_URL'], '', 0, strlen(Am::vars('site_root')));
  //   $method = strtolower($_SERVER['REQUEST_METHOD']);
  //   $extraParams = array();
    
  //   foreach(explode('/', $pathinfo) as $v){
  //     if(!empty($v)!= ''){
  //       $extraParams[] = trim($v);
  //     }
  //   }
    
  //   self::$requestPath = "/" . implode('/', $extraParams);
  //   self::$method = $method;

  //   Am::control('Control');
    
  //   $control = Am::instances('Control');

  //   $control->initialize();
      
  //   $control->dispatch($extraParams);
    
  // }
  
  // protected function action(){}
  // protected function getAction(){}
  // protected function postAction(){}
  
  // protected function actionError404($errorMsg){
    
  //   AmAlert::danger("Error 404: {$errorMsg}");
    
  //   $this->render(null);
    
  // }
  
}
