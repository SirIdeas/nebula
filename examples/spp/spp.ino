/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
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
