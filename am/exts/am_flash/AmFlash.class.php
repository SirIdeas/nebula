<?php

/**
 * Clase para el manejo de mensajes mensajes flash.
 * Los mensajes Flash son mesajes que guardados en 
 * session para mantenerse entre una peticion y otra.
 * Los mensajes son guardados por tipos
 */

final class AmFlash{
  
  // Obtener todos los mensajes
  public final static function all(){
    // Obtener todos los mensajes
    $ret = AmSession::get("flash");
    // Eliminar los mensajes de session
    AmSession::delete("flash");
    // Retornar los mensajes
    return $ret? $ret : array();
  }

  // Devuelve los mensajes flash de un tipo especifico
  public final static function get($index){
    
    // Obtener todos los mensajes flash
    $flash = AmSession::get("flash");
    
    // Si esta definida la lista de mensajes solicitada
    if(isset($flash[$index])){
      
      //Obtener la lista de mensajes
      $ret = $flash[$index];

      // Eliminarlos se sesion
      unset($flash[$index]);
      AmSession::set("flash", $flash);

      // Retornar los obtenido
      return $ret;

    }
    
    return array();
  }

  // Agrega un mensaje a los Flash
  public final static function add($index, $message){
    $flash = AmSession::get("flash");
    
    // Si no ha sido iniciada se inicializa
    if(!$flash)
      $flash = array();

    // Si no ha sido inicializada la lista de mensajes
    // para el tipo $index, entonces se inicializa
    if(!isset($flash[$index]))
      $flash[$index] = array();

    // Se agrega el mensaje
    $flash[$index][] = $message;

    // Guardar arra modificado en en sesion 
    AmSession::set("flash", $flash);

  }

  // Funcion comun para obtener/agregar cada tipo de mensaje
  private final static function _getAdd($index, $msg = null){
    if(isset($msg))
      return self::add($index, $msg);
    return self::get($index);
  }

  // Funciones obtener/agregar para cada tipo de mensaje
  public final static function success($msg = null){ return self::_getAdd("success", $msg); }
  public final static function info($msg = null){    return self::_getAdd("info", $msg); }
  public final static function warning($msg = null){ return self::_getAdd("warning", $msg); }
  public final static function danger($msg = null){  return self::_getAdd("danger", $msg); } 
  
}
