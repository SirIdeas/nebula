/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
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