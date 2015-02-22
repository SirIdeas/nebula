/* ========================================================================
 * Nebula Arduino Lib: Nb v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#include "Nb.h"

//========================================================================================================
// Funciones para trazas de algunos objetos
//--------------------------------------------------------------------------------------------------------

// Imprime el contenido del buffer
void nb_trace_buffer(NbBuffer* buffer, const char* prefix){
#ifdef NB_SHOW_TRACE

  NbByte* node = buffer->first();
  
  NB_TRACE_IN_NL(prefix);
  NB_TRACE(": (");
  NB_TRACE2(buffer->availables(), ")");

  // Recorrer toda la lista
  while(node){
    NB_TRACE2(node->data, " ");
    node = node->next;
  }

#endif
}

void nb_trace_bits(char* pn, char len=1){
#ifdef NB_SHOW_TRACE
  char i;
  while(len){
    len--;
    i=8;
    while(i){
      i--;
      NB_TRACE(0x1<<i & (*(pn+len))? "1" : "0");
    }
  }
#endif
}

//========================================================================================================
// Manejo de bits
//--------------------------------------------------------------------------------------------------------
bool nb_bits_get(char* bits, int pos){
  int bit = pos%8;
  int byte = pos/8;
  return (bits[byte] & 0x1 << bit)!=0;
}

void nb_bits_set(char* bits, int pos, bool v){
  char b = pos/8;
  char m = 0x1 << pos%8;
  if(v){
    *(bits+b) |= m;
  }else{
    *(bits+b) &= ~m;
  }
}

int nb_bits_next(char* bits, char len, int last){
  last++;
  int v;
  int bit = last%8;
  int ret = -1;
  for(int i=last/8; i<len; i++){
    v = bits[i] >> bit;
    for(int j=bit; v && j<8; j++){
      if(v & 0x1) return i*8+j;
      v >>= 1;
    }
    bit = 0;
  }
  return -1;
}

//========================================================================================================
// Nb Sketch
//--------------------------------------------------------------------------------------------------------

// Constructor
Nb::Nb(){
  
  // Inicializacion digitales
  for(int i=0; i<NB_DIGITAL_INS_LEN; i++){
    _digitalIns[i] = _digitalInsValues[i] = 0;
  }

  // Inicializacion analogicas
  for(int i=0; i<NB_ANALOG_INS_LEN; i++){
    _analogIns[i] = _analogInsValues[i] = 0;
  }

  _bufferIn = new NbBuffer();
  _bufferOut = new NbBuffer();
  
  _connected = false;
  _sendFirstMessage = true;
  _resetWhenDisconnected = true;

  // Inicializacion de punteros a funciones
  _fnPre = NULL;
  _fnObject = NULL;
  _fnElse = NULL;
  
}

// Devuelve el buffer de entrada
NbBuffer* Nb::getIn(void){
  return _bufferIn;
}

// Devuelve el buffer de salida
NbBuffer* Nb::getOut(void){
  return _bufferOut;
}

// Asignar una funcion para procesar los comandos antes de ejecutarlos
void Nb::setCallbackPre(bool (*fn)(char, bool&)){
  _fnPre = fn;
}

// Asignar una funcion para manejar los comandos personalizados
void Nb::setCallbackObject(bool (*fn)(int id)){
  _fnObject = fn;
}

// Asignar una funcion para manejar los comandos desconocidos
void Nb::setCallbackUnk(bool (*fn)(char)){
  _fnElse = fn;
}

// Asignar una funcion para manejar los comandos desconocidos
void Nb::setCallbackSend(void (*fn)(void)){
  _fnSend = fn;
}

// 
void Nb::operate(void){
  
  char cmd;
  _connected = true;
  if(_sendFirstMessage) sendFirstMessage(NB_MSG_FINISH);

  loadBuffer();

// #ifdef NB_SHOW_TRACE
//   if(available(1)) nb_trace_buffer(_bufferIn, "bufferIn");
// #endif

  bool unk = false;
  while(available(1)){
    recovery();
    cmd = read();
    if(unk && _fnPre){
      if((*_fnPre)(cmd, unk)){
        if(!unk) purge();  // comando ejecutado satisfactoriamente
      }else return; // Faltan bytes
    }else{
      if(execute(cmd)){
        unk = false;
        purge();
      } else return;  // Faltan bytes
    }
  }

}

// Procesa un cmd
bool Nb::execute(char cmd){
  
  switch(cmd){
    case NB_MSG_PIN_MODE_OUT:       return cmdInitOutput();
    case NB_MSG_SET_STATE_DIGITAL:  return cmdSetStateDigital();
    case NB_MSG_SET_STATE_ANALOG:   return cmdSetStateAnalog();
    case NB_MSG_DIGITAL_IN_CONF:    return cmdInitInput(NB_MSG_TYPE_DIGITAL);
    case NB_MSG_ANALOG_IN_CONF:     return cmdInitInput(NB_MSG_TYPE_ANALOG);
    case NB_MSG_DIGITAL_WRITE:      return cmdWrite(NB_MSG_TYPE_DIGITAL);
    case NB_MSG_ANALOG_WRITE:       return cmdWrite(NB_MSG_TYPE_ANALOG);
    case NB_MSG_MOTORDC_WRITE:      return cmdMotorDcWrite();
    case NB_MSG_STEPTOSTEP_MOVE:    return cmdStepToStepMove();
    case NB_MSG_FINISH:             return cmdFinish();
    case NB_MSG_OBJECT_CMD:         return cmdObject();
  }
  
  return cmdElse(cmd);
  
}

// Restaura la lista
void Nb::restart(void){
  if(_resetWhenDisconnected && _connected == true)
    asm volatile("  jmp 0"); // Comando para resetear el microcontrolador
  _bufferIn->restart();
}

// Restaura la lista
void Nb::task(int pinOn){
  if (connected()){
    operate();
    if(pinOn!=-1) digitalWrite(pinOn, HIGH);
  }else{
    if(pinOn!=-1) digitalWrite(pinOn, LOW);
    restart();
  }
}

void Nb::setResetWhenDisconnected(bool resetWhenDisconnected){
  _resetWhenDisconnected = resetWhenDisconnected;
}

// Asigna si se debe enviar el un primer mensaje tras la conexión o no.
void Nb::setSendFirstMessage(bool sendFirstMessage){
  _sendFirstMessage = sendFirstMessage;
}

// Envia el primer mensaje
void Nb::sendFirstMessage(int msg){
  setSendFirstMessage(false);
  addByte(msg);
  send();
}

// Devuelve la cantidad de elementos cargados en el buffer de entrada
int Nb::availables(void){
  return _bufferIn->availables();
}

// Disponibilidad en el buffer de entrada
bool Nb::available(int size){
  return _bufferIn->available(size);
}

// Recupera los byte leidos del buffer de entrada
void Nb::recovery(void){
  _bufferIn->recovery();
}

// Purga el buffer de entrada
void Nb::purge(void){
  _bufferIn->purge();
}

// Leer byte del buffer de entrada
int Nb::read(void){
  return _bufferIn->read();
}

// Leer int del buffer de entrada
int Nb::readInt(void){
  return _bufferIn->readInt();
}

// Leer long del buffer de entrada
long Nb::readLong(void){
  return _bufferIn->readLong();
}

// Leer bytes de buffer indicados por la siguiente lectura
int Nb::readBytes(char* buffer, int len){
  return _bufferIn->readBytes(buffer, len);
}

// Cargar un byte en el buffer de entrada
void Nb::loadByte(char data){
  _bufferIn->addByte(data);
}

// Agrega un byte a la salida
int Nb::addByte(char data){
  return _bufferOut->addByte(data);
}

// Agrega un int a la salida
int Nb::addInt(int data){
  return _bufferOut->addInt(data);
}

// Agrega un long a la salida
int Nb::addLong(long data){
  return _bufferOut->addLong(data);
}

// Enviar los bytes contenidos en el buffer
void Nb::send(void){
  
  if(_bufferOut->available(1)){
    // nb_trace_buffer(_bufferOut, "bufferOut");
  
    // Recuperar los bytes y el contenido
    char bytes[IO_LEN];
    int count;
    
    // Mientras se puedan recuperar bytes se envian y se purga la salida
    while((count = _bufferOut->readBytes(bytes, IO_LEN))>0){
      print(bytes, count);
      _bufferOut->purge();
    }

  }
  
}

// Enviar un byte
void Nb::printByte(char byte){
  print(&byte, 1);
}

//--------------------------------------------------------------------------------------------------------
// Comandos principales
//--------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------
// Cambiar estado de una salida
bool Nb::cmdSetStateDigital(void){
  if(available(2)){
    char state = read();
    char pin = read();
    if(pin != NB_INVALID_PIN){

      nb_bits_set(_digitalIns, pin, state!=0);
      
      NB_TRACE_IN_NL("cmdSetStateDigital(");
      NB_TRACE2(state, ",");
      NB_TRACE2(pin, ")");

    }else{
      NB_TRACE_INVALID_PIN("cmdSetStateDigital");
    }
    return true;
  }

  NB_TRACE_BAD_CMD("cmdSetStateDigital", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Cambiar estado de una salida
bool Nb::cmdSetStateAnalog(void){
  if(available(2)){
    char state = read();
    char pin = read();
    if(pin != NB_INVALID_PIN){

      nb_bits_set(_analogIns, pin, state!=0);
      
      NB_TRACE_IN_NL("cmdSetStateAnalog(");
      NB_TRACE2(state, ",");
      NB_TRACE2(pin, ")");

    }else{
      NB_TRACE_INVALID_PIN("cmdSetStateAnalog");
    }
    return true;
  }

  NB_TRACE_BAD_CMD("cmdSetStateAnalog", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Configurar pines de salida
bool Nb::cmdInitOutput(void){
  if(available(1)){
    char pin = read();
    if(pin != NB_INVALID_PIN){
      pinMode(pin, OUTPUT);
      NB_TRACE_IN_NL("cmdInitOutput(");
      NB_TRACE2(pin, ")");
    }else{
      NB_TRACE_INVALID_PIN("cmdInitOutput");
    }
    return true;
  }

  NB_TRACE_BAD_CMD("cmdInitOutput", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Configurar pines de entrada
bool Nb::cmdInitInput(char type){
  if(available(1)){
    char pin = read();
    if(pin != NB_INVALID_PIN){
      if(type==NB_MSG_TYPE_DIGITAL){
        nb_bits_set(_digitalIns, pin, true);
        pinMode(pin, INPUT);
      }else if(type==NB_MSG_TYPE_ANALOG){
        nb_bits_set(_analogIns, pin, true);
      }
      NB_TRACE_IN_NL("cmdInitInput(");
      NB_TRACE2(pin, ",");
      NB_TRACE2(type, ")");
    }else{
      NB_TRACE_INVALID_PIN("cmdInitInput");
    }
    return true;
  }

  NB_TRACE_BAD_CMD("cmdInitInput", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Escribir salida
bool Nb::cmdWrite(char type){
  if(available(2)){
    char pin = read();
    if(pin != NB_INVALID_PIN){
      char value = read();
      if(type == NB_MSG_TYPE_DIGITAL){
        digitalWrite(pin, value==0? LOW : HIGH);
      }else if(type == NB_MSG_TYPE_ANALOG){
        analogWrite(pin, value);
      }
      NB_TRACE_IN_NL("cmdWrite(");
      NB_TRACE2((int)type, ",");
      NB_TRACE2(pin, ",");
      NB_TRACE2(value, ")");
    }
    else{
      NB_TRACE_INVALID_PIN("cmdWrite");
    }
    return true;
  }

  NB_TRACE_BAD_CMD("cmdWrite", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Respuesta
bool Nb::cmdFinish(void){
  digitalInListenersRead();
  analogInListenersRead();
  if(_fnSend) (*_fnSend)();
  addByte(NB_MSG_FINISH);
  send();
  return true;
}

//--------------------------------------------------------------------------------------------------------
// Procesar comandos personalizados
bool Nb::cmdObject(void){
  if(available(2)){
    int id = readInt();
    if(_fnObject) return (*_fnObject)(id);
    return true;
  }
  return false;
}

//--------------------------------------------------------------------------------------------------------
// Procesar comandos desconocidos
bool Nb::cmdElse(char msg){
  if(_fnElse) return (*_fnElse)(msg);
  return true;
}

//--------------------------------------------------------------------------------------------------------
// Para enviar valores a objectos
void Nb::setObjectValue(int id){
  addByte(NB_MSG_OBJECT_CMD);
  addInt(id);
}

//--------------------------------------------------------------------------------------------------------
// Motores DC
//--------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------
// Escribir Puente H
bool Nb::cmdMotorDcWrite(void){
  if(available(6)){
    char enabledPin = read();
    char in1Pin = read();
    char in2Pin = read();
    char enabledValue = read();
    char in1Value = read();
    char in2Value = read();

    if(enabledPin != NB_INVALID_PIN) analogWrite(enabledPin, enabledValue);
    if(in1Pin != NB_INVALID_PIN) digitalWrite(in1Pin, in1Value);
    if(in2Pin != NB_INVALID_PIN) digitalWrite(in2Pin, in2Value);

    NB_TRACE_IN_NL("cmdMotorDcWrite(");
    NB_TRACE2(enabledPin, ",");
    NB_TRACE2(in1Pin, ",");
    NB_TRACE2(in2Pin, ",");
    NB_TRACE2(enabledValue, ",");
    NB_TRACE2(in1Value, ",");
    NB_TRACE2(in2Value, ")");

    return true;
  }

  NB_TRACE_BAD_CMD("cmdMotorDcWrite", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Step to Step motors
//--------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------
// Mover motor paso a paso
bool Nb::cmdStepToStepMove(void){
  
  if(available(4)){
    char pinA = read();
    char pinB = read();
    char pinC = read();
    char pinD = read();

    if(available(6) && pinA != NB_INVALID_PIN && pinB != NB_INVALID_PIN && pinC != NB_INVALID_PIN && pinD != NB_INVALID_PIN){
    
      int steps = readInt();
      int vel = readInt();
      char dir = read();
      char currenStep = read();

      // Mover el motor una cantidad de pasos a una velocidad determinada en una direccion determinada
      // Parametros:
      //    steps: cantidad de pasos
      //    vel: velocidad del paso (mientras mas bajo mas rapido)
      //    dir: direccion del movimiento (1: avanzar, 2: retroceder)
      dir = dir>0? 1 : -1; 
      
      for(int i=0; i<steps; i++){
      
        currenStep = (currenStep+dir);
        
        if(currenStep == -1) currenStep = 3;
        else if(currenStep == 4) currenStep = 0;
        
        digitalWrite(pinA, (currenStep==0 || currenStep==1)? HIGH : LOW);
        digitalWrite(pinB, (currenStep==2 || currenStep==3)? HIGH : LOW);
        digitalWrite(pinC, (currenStep==3 || currenStep==0)? HIGH : LOW);
        digitalWrite(pinD, (currenStep==1 || currenStep==2)? HIGH : LOW);
        delay(vel);
        
      }

      NB_TRACE_IN_NL("cmdStepToStepMove(");
      NB_TRACE2(pinA, ",");
      NB_TRACE2(pinB, ",");
      NB_TRACE2(pinC, ",");
      NB_TRACE2(pinD, ",");
      NB_TRACE2(steps, ",");
      NB_TRACE2(vel, ",");
      NB_TRACE2(dir, ",");
      NB_TRACE2(currenStep, ")");

      return true;

    }
  }

  NB_TRACE_BAD_CMD("cmdStepToStepMove", availables());

  return false;
}

//--------------------------------------------------------------------------------------------------------
// Leer y enviar entradas digitales
void Nb::digitalInListenersRead(void){
  int next = -1;
  bool value;
  
  while(-1 !=(next = nb_bits_next(_digitalIns, NB_DIGITAL_INS_LEN, next))){
    value = digitalRead(next)!=0;

    if(nb_bits_get(_digitalInsValues, next) != value){

      nb_bits_set(_digitalInsValues, next, value);
      addByte(NB_MSG_DIGITAL_IN_READ);
      addByte(next);  // Pin
      addByte(value);

      NB_TRACE_IN_NL("digitalInListenersRead(");
      NB_TRACE2(next, ",");
      NB_TRACE2(value, ")");

    }
  }

}

//--------------------------------------------------------------------------------------------------------
// Leer y enviar entradas analogicas
void Nb::analogInListenersRead(void){
  int next = -1;
  int value;

  while(-1 !=(next = nb_bits_next(_analogIns, NB_ANALOG_INS_LEN, next))){
    analogRead(next);
    value = analogRead(next);
    if(_analogInsValues[next] != value){
      _analogInsValues[next] = value;
      addByte(NB_MSG_ANALOG_IN_READ);
      addByte(next);  // Pin
      addInt(value);

      NB_TRACE_IN_NL("analogInListenersRead(");
      NB_TRACE2(next, ",");
      NB_TRACE2(value, ")");
    }
  }

}

