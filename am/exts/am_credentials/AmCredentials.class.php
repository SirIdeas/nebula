<?php

/**
 * Interfaz para clases que serviran para la verificacion de 
 * La autenticación
 */

interface AmCredentials {

  // Autentica un usuario por nombre y passsword
  public static function auth($nick, $password);
  
  // Pregunta si el usuario autenteicado tiene
  // una determinada credencial.  
  public function hasCredential($credential);
  
  // Devuelve el identidicador únicodel usuario
  public function getCredentialsId();
  
  // Devuelve la instancia de un usuario apartir de su
  // identificador unico.
  public static function getCredentialsInstance($crendentialId);
  
}
