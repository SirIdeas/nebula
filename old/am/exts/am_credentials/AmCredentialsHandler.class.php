<?php

/**
 * Clase que sirve de apoyo para el proceso de atutenticación
 * y aprobación de permisos con credenciales.
 */
final class AmCredentialsHandler{
  
  protected
    // Clase que servirá como clase de autenticación.
    // Esta clase deberá implementar la interfaz AmWithCredentials
    $credentialsClass = null,

    // Identificador del usuario logeado
    $credentialsId = null,

    // Instancia de las credenciales del usuario logeado
    $credentials = null,

    // Url donde se autentica el usuario.
    $authUrl = null;

  // Constructor de la clase
  public function __construct(){

    // Obtener la configuracion
    $conf = Am::getAttribute("credentials", array());

    // Inicializar parametros
    $this->authUrl = itemOr("authUrl", $conf);
    
    // Aignar clase que se utilizará para las credenciales    
    $this->setCredentialsClass(
      itemOr("class", $conf),             // Clase
      AmSession::get("credentials_id") // Identificador
    );

  }

  // Asignar la clases que se utilizara para las credenciales
  public function setCredentialsClass($credentialClass, $credentialsId) {
    
    // Asignar valores
    $this->credentialsClass = $credentialClass;
    $this->credentialsId = $credentialsId;
    
    // Si la clase no existe no se puede buscar las credenciales
    if(!class_exists($credentialClass))
      return;

    // Obtener instancia de las credenciales mediante el Id.
    $this->credentials = $credentialClass::getCredentialsInstance($credentialsId);
    
    // Sino se obtivieron credenciales se destruye el ID guardado
    if(!$this->isAuth()){
      AmSession::delete("credentials_id");
      $this->credentialsId = null;
    }

  }
  
  // Indica si hay un usuario autenticado o no
  public function isAuth(){
    return isset($this->credentials);
  }
  
  // Redirigue al enlace para autenticar al usuario
  public function redirectToAuth(){
    Am::gotoUrl($this->authUrl);
  }
  
  // Devuelve la instancia del usuario logeado
  public function getCredentials(){
    return $this->credentials;
  }
  
  // Chequea su hay un usuario logeado. De lo contrario
  // Redirige al enlace de autenticacion.
  public function checkAuth(){
    if(!$this->isAuth())
      $this->redirectToAuth();
  }
  
  // Asignar una la autenticacion de un usuario.
  public function setAuthenticated(AmCredentials $credentials = null){
    
    // Asignar credenciales
    $this->credentials = $credentials;
    
    // Si son unas credenciales válidas
    // Guardar el ID de las credenciales
    if($this->isAuth())
      AmSession::set("credentials_id", $credentials->getCredentialsId());

    // De lo contrario borrar el ID de la session
    else
      AmSession::delete("credentials_id");

  }
  
  // Indica si la session actual tiene las credenciales recibidas por parametros
  public function hasCredentials($credentials){
    
    // Si no hay usuario autenticado retornar falso
    if(!$this->isAuth())
      return false;
    
    // Si las credenciales solicitadas no son un arrauy
    // se puede preguntar directamente al usuario 
    // autenteicado
    if(!is_array($credentials))
      return $this->credentials->hasCredential($credentials);
    
    // Veriricar cada credencial
    foreach($credentials as $credential){
      
      // Si no es una grupo de credenciales volverla un grupo de uno
      if(!is_array($credential))
        $credential = array($credential);
      
      // Verificar si el usuario tiene al menos una
      // de las credenciales del grupo.
      $hasOneCredential = false;
      foreach($credential as $credentialOr){
        if($this->credentials->hasCredential($credentialOr)){
          $hasOneCredential = true;
          break;
        }
      }
      
      // Si No tiene al menos una de las credenciales del grupo
      // no se le otorga permisos
      if(!$hasOneCredential){
        return false;
      }
    }
    
    // Tiene todas las credenciales
    return true;

  }

  // Chequear los permisos para una accion especifica.
  public function checkCredentials($action, $credentials){

    // Si las credenciales no es un array no se realiza la verificacion
    if(!is_array($credentials))
      return;

    // Si un array vacío solo se debe verificar que
    // existe un usuario logeado.
    if(empty($credentials)){
      $this->checkAuth();
      return;
    }

    // Verificar cada crendencial
    foreach($credentials as $credential){

      // Si la accion que se ejecutada no necetida dicha
      // credencial se continua con la verificacion de la próxima
      if(!self::actionNeedCredentials($action, $credential))
        continue;
        
      // Convertir la credencia en array si no lo es.
      if(!is_array($credential))
        $credential = array($credential);
      // Si es un arrahy asociativo se debe obtener el item "roles"
      elseif(isAssocArray($credential))
        $credential = itemOr("roles", $credential, array());

      // Si no posee dichas credenciales rediriguir a la pantalla de logueo.
      if(!$this->hasCredentials($credential))
        $this->redirectToAuth();

    }

  }
  
  // Verificar su un accion necesita la credencial solicitada
  private static function actionNeedCredentials($action, $credential){
    
    // si la credenciale soliocitada no es un array se
    // se entenderá que todas las acciones nececitan 
    // dicha credencial.
    if(!is_array($credential))
      return true;

    // Si esta definido el parametro only se chequea
    // que la accion este dentro de las acciones que necesitan
    // esta credencial.
    if(isset($credential["only"]) && is_array($credential["only"]))
      return in_array($action, $credential["only"]);

    // Si esta definido el parametro except se chequea
    // que la accion este dentro de las acciones que no 
    // necesitan esta credencial.
    if(isset($credential["except"]) && is_array($credential["except"]))
      return !in_array($action, $credential["except"]);

    // De lo contrario la accion no requiere dicha credencial
    return false;


  }

  // Devuelve una instancia para del manejador de credenciales.
  // Destinada a un callback.
  public static function getInstance(){
    return Am::getInstance("AmCredentialsHandler");
  }
  
}
