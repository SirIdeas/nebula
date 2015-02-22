/* ========================================================================
 * Nebula Arduino Lib: Sample Serial Single Comunication v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

// Incluir Librerías
#include <Nb.h>
#include <NbSerial.h>

// Instanciación de objeto a utilizar
NbSerial com(&Serial);

// Configuración inicial
void setup(){
  Serial.begin(115200);
}

// Función de procesamiento
void loop(){
  com.task();
}