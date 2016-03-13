/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */
 
#include "NbSPP.h"

//========================================================================================================
// Nb Sketch Bluetooth
//--------------------------------------------------------------------------------------------------------

// Cosntructor
NbSPP::NbSPP(SPP* spp) : Nb(){
  _spp = spp;
}

// Indica si esta conectado
bool NbSPP::connected(void){
  return _spp->connected? true : false;
}

// Carga el buffer de la instancia SPP en el buffer de entrada
void NbSPP::loadBuffer(void){
  while(_spp->available()) loadByte(_spp->read());
}

// Envia el buffer de salida
void NbSPP::print(char* buffer, int len){
  _spp->write((byte*)buffer, len);
}

// Leer un byte
int NbSPP::readByte(void){
  return _spp->read();
}

// Preguntar si existen bytes disponibles
bool NbSPP::hasIn(void){
  return _spp->available() != 0;
}
