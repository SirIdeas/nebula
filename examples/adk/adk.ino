/* ========================================================================
 * Nebula Arduino Lib: Sample ADK Single Comunication v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

// Incluir Librerías
#include <adk.h>
#include <Nb.h>

// Instanciar objetos necesarios
USB Usb;
ADK adk(&Usb,
  "UNEG",
  "NebulaBoard",
  "Nebula Board",
  "1.0",
  "http://nebula.sirideas.com/",
  "0000000012345678");

// Instanciación de objeto a utilizar
NbAdk com(&adk);

// Configuración inicial
void setup() {
  if (Usb.Init() == -1) {
    while (1);
  }
}

// Función de procesamiento
void loop() {
  Usb.Task();
  com.task();
}
