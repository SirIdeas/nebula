<?php

/**
 * Clase principal de Amathista
 */

final class AmNormalSession{
  
  // Asigna una ID de sesion
  public final static function id($sessionId){
    
    self::$sessionId = $sessionId;
    
    // Crear contendor de la sesion   
    if(!isset($_SESSION[$sessionId]))
      $_SESSION[$sessionId] = array();
    
  }
  
  protected static
    $sessionId; // ID de la sesion
    
  // Devuelve un array con todas las variables de sesion
  public final static function all(){
    return $_SESSION[self::$sessionId];
  }

  // Devuelve el contenido de una variable de sesion
  public final static function get($index){
    return self::has($index) ? unserialize($_SESSION[self::$sessionId][$index]) : null;
  }

  // Indica si existe o no una variable de sesion
  public final static function has($index){
    return isset($_SESSION[self::$sessionId][$index]);
  }

  public final static function set($index, $value){
    $_SESSION[self::$sessionId][$index] = serialize($value);
  }
  
  // Elimina una variable de la sesion
  public final static function delete($index){
    unset($_SESSION[self::$sessionId][$index]);
  }

}
