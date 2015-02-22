/* ========================================================================
 * Nebula Arduino Lib: NbSoftwareSerial v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#include "NbSerial.h"

//========================================================================================================
// Nb SoftwareSerial
//--------------------------------------------------------------------------------------------------------

// Cosntructor
NbSerial::NbSerial(HardwareSerial* hs): Nb( ){
  _hs = hs;
};

// Indica si esta conectado
bool NbSerial::connected(){

  if(_connected) return true; // Si esta conectado retornar true.

  printByte((char)NB_MSG_FINISH);
  delay(1000);

  // Si aun no esta conectado y existe un byte en la entrada
  if(_hs->available()){
    // Se obviaran los primeros bytes
    while(_hs->available()) _hs->read();
    return true; // Retornar como conectado
  }

  // Si no esta conectado y tampoco exiten bytes entrantes, seguir desconectado
  return false;

}

// Carga el buffer de la instancia SPP en el buffer de entrada
void NbSerial::loadBuffer(){
  while(_hs->available()) loadByte(_hs->read());
}

// Envia el buffer de salida
void NbSerial::print(char* buffer, int len){
  for(int i=0;i<len; i++) _hs->write(buffer[i]);
}

// Leer un byte
int NbSerial::readByte(void){
  return _hs->read();
}

// Preguntar si existen bytes disponibles
bool NbSerial::available(void){
  return _hs->available();
}
