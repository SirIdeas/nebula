/* ========================================================================
 * Nebula Arduino Lib: NbAdk v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#include "NbAdk.h"

//========================================================================================================
// Nb Sketch ADK
//--------------------------------------------------------------------------------------------------------

// Cosntructor
NbAdk::NbAdk(ADK* adk) : Nb( ){
  _adk = adk;
  setSendFirstMessage(false);
}

// Indica si esta conectado
bool NbAdk::connected(void){
  return _adk->isReady()? true : false;
}

// Carga el buffer de la instancia SPP en el buffer de entrada
void NbAdk::loadBuffer(void){
  uint8_t rcode;
  uint8_t msg[10];
  uint16_t len;
  
  do{
    len = sizeof(msg);
    rcode = _adk->RcvData(&len, msg);
    
    if(rcode && rcode != hrNAK){
      // NB_TRACE2("\r\nData rcv: ", rcode);
    }else if(len > 0){
      for(int i=0; i<len; i++){
        loadByte((char)msg[i]);
      }
    }
    
  }while(len>0);
  
}

// Envia el buffer de salida
void NbAdk::print(char* buffer, int len){
  
  delay(NB_ADK_PAUSE);
    
  uint8_t rcode;
  
  rcode = _adk->SndData(len, (uint8_t*)buffer);
  
  // if(rcode && rcode != hrNAK){
  //     NB_TRACE("\r\nData send: ", rcode);
  // }else if (rcode != hrNAK){
  //     NB_TRACE("\r\nSended send: (");
  //     NB_TRACE2(len, ")");

  //   for(int i=0; i<len; i++){
  //     NB_TRACE2(buffer[i], " ");
  //   }
  // }

}

// Leer un byte
int NbAdk::readByte(void){
  return -1;
}

// Preguntar si existen bytes disponibles
bool NbAdk::available(void){
  return false;
}
