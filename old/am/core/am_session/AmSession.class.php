<?php

/**
 * Clase principal de Amathista
 */

final class AmSession{
  
  // Devuelve un array con todas las variables de sesion
  public final static function all(){
    return Am::call("session.all");
  }

  // Devuelve el contenido de una variable de sesion
  public final static function get($index){
    return Am::call("session.get", $index);
  }

  // Indica si existe o no una variable de sesion
  public final static function has($index){
    return Am::call("session.has", $index);
  }

  public final static function set($index, $value){
    return Am::call("session.set", $index, $value);
  }
  
  // Elimina una variable de la sesion
  public final static function delete($index){
    return Am::call("session.delete", $index);
  }
  
  // Asigna una ID de sesion
  public final static function id($id){
    return Am::call("session.id", $id);
  }

}
