/* ========================================================================
 * Nebula Arduino Lib: Sample Bluetooth Single Comunication v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

// Incluir Librerías
#include <SPP.h>
#include <Nb.h>

// Instanciar objetos necesarios
USB Usb;
BTD Btd(&Usb);
SPP SerialBT(&Btd, "NebulaBoard", "1234");

// Instanciación de objeto a utilizar
NbSPP com(&SerialBT);

// Configuración inicial
void setup(){
  if (Usb.Init() == -1) {
    while(1); //halt
  }
}

// Función de procesamiento
void loop(){
  Usb.Task();
  com.task();
}
