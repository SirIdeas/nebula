/* ========================================================================
 * Nebula Arduino Lib: Sample Bluetooth Single Comunication with custom objects v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

// Comunicacion
#include <SPP.h>
#include <Nb.h>

USB Usb;
BTD Btd(&Usb);
SPP SerialBT(&Btd, "NebulaBoard", "1234");
NbSPP com(&SerialBT);

/* Procesamiento de tareas para objetos
 * ========================================================================
 * Descripción: Callback que permite procesar las tareas enviadas a
 *              objetos.
 * ------------------------------------------------------------------------
 * return:      Debe retornar verdadero si logra realizar una tarea
 *              satisfactoriamente.
 * ------------------------------------------------------------------------
 * id:          Id del objeto al que se envia la tarea
 * ------------------------------------------------------------------------
 */
bool callback_object(int id){
  return true;
}

/* Procesamiento de comandos desconocidos
 * ========================================================================
 * Descripción: Callback llamado en el caso de que el comando no sea
 *              reconocido.
 * ------------------------------------------------------------------------
 * return:      Debe retornar verdadero si logra realizar una tarea
 *              satisfactoriamente.
 * ------------------------------------------------------------------------
 * cmd:         Comando
 * ------------------------------------------------------------------------
 */
bool callback_unk(int cmd){
  return true;
}

/* Envio de data personalizada
 * ========================================================================
 * Descripción: Callback para los datos personalizados enviados por el
 *              usuario.
 * ------------------------------------------------------------------------
 */
void callback_send(void){
}

/* Preprocesamiento de comandos
 * ========================================================================
 * Descripcion: Callback quer permite procesar los bytes recibidos antes
 *              de ser manejados por la biblioteca.
 * ------------------------------------------------------------------------
 * return:      Debe retornar verdadero si logra realizar la tarea
 *              satisfactoriamente.
 * ------------------------------------------------------------------------
 * cmd:         Proximo byte leído
 * unk:         Variable que indica si el comando recibido no es un
 *              comando procesado por el callback. en este caso se debe
 *              asignar false al parametro unk.
 * ------------------------------------------------------------------------
 */
bool callback_pre(char cmd, bool& unk){
  return true;
}

// Inicializacion
void setup(){

  // Init CMD funtions
  com.setCallbackObject(callback_object);
  com.setCallbackUnk(callback_unk);
  com.setCallbackSend(callback_send);
  com.setCallbackPre(callback_pre);
  
  if (Usb.Init() == -1) {
    while(1); //halt
  }

}

// Loop principal
void loop(){
  Usb.Task();
  com.task();
}
