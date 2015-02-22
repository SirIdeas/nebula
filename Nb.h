/* ========================================================================
 * Nebula Arduino Lib: Nb v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#ifndef Nb_H
#define Nb_H

//========================================================================================================
// Definiciones
//--------------------------------------------------------------------------------------------------------

// Dialect
#define NB_MSG_FINISH            0x01
#define NB_MSG_DIGITAL_IN_CONF   0x02
#define NB_MSG_PIN_MODE_OUT      0x03
#define NB_MSG_ANALOG_WRITE      0x04
#define NB_MSG_ANALOG_IN_CONF    0x05
#define NB_MSG_ANALOG_IN_READ    0x06
#define NB_MSG_DIGITAL_WRITE     0x07
#define NB_MSG_OBJECT_CMD        0x08
#define NB_MSG_DIGITAL_IN_READ   0x09
#define NB_MSG_MOTORDC_WRITE     0x0A
#define NB_MSG_STEPTOSTEP_MOVE   0x0B
#define NB_MSG_SET_STATE_DIGITAL 0x0C
#define NB_MSG_SET_STATE_ANALOG  0x0D

// Esta constante define el ultimo comando
#define __NB_LAST_MSG_CODE NB_MSG_SET_STATE_ANALOG

// Tipos de entrada/salida
#define NB_MSG_TYPE_DIGITAL   0x01
#define NB_MSG_TYPE_ANALOG    0x02

// Comandos para las LCD
#define NB_CMD_LCD_CLEAR        0x01
#define NB_CMD_LCD_SET_CURSOR   0x02
#define NB_CMD_LCD_PRINT        0x03

// Valor de pin invalido
#define NB_INVALID_PIN 255

#include <stdio.h>

// Configuraciones del globales del usuario
#include "NbSettings.h"

// Para manejo de buffer
#include "NbBuffer.h"

//========================================================================================================
// Para mostrar Trazas
//--------------------------------------------------------------------------------------------------------

// Incluir el funciones para mostrar trazas
#ifdef NB_SHOW_TRACE
  #define NB_TRACE(v) Serial.print(v)
#else
  #define NB_TRACE(v) // Definir una macro vacio para los trazos si no se ha definido
#endif

#define NB_TRACE2(v1, v2) NB_TRACE((byte)v1); NB_TRACE(v2)
#define NB_TRACE_IN_NL(v) NB_TRACE("\r\n"); NB_TRACE(v)
#define NB_TRACE_INVALID_PIN(cmd) NB_TRACE_IN_NL("NB_INVALID_PIN: "); NB_TRACE(cmd)
#define NB_TRACE_BAD_CMD(cmd, size) NB_TRACE("\r\nBAD_CMD: "); NB_TRACE(cmd); NB_TRACE("SIZE: "); NB_TRACE(size);

// Algunas funcionalidades de trazas
void nb_trace_buffer(NbBuffer*, const char*);
void nb_trace_bits(char*, char);

//========================================================================================================
// Manejo de bits
//--------------------------------------------------------------------------------------------------------
bool nb_bits_get(char*, int);
void nb_bits_set(char*, int, bool);
int nb_bits_next(char*, char, int);

//========================================================================================================
// 
//--------------------------------------------------------------------------------------------------------
#define NB_DIGITAL_INS_LEN 7  // 7 bytes (8 bits cada uno) = 56 bits
#define NB_ANALOG_INS_LEN  2  // 2 bytes (8 bits cada uno) = 16 bits

//========================================================================================================
// Nb Sketch
//--------------------------------------------------------------------------------------------------------

// Clase general para el manejo de comunicacion
class Nb{
private:

  bool (*_fnPre)(char, bool&);  // Funcion para preprocesar los comandos
  bool (*_fnObject)(int);       // Funcion para procesar los comandos personalizados
  bool (*_fnElse)(char);        // Funcion para procesar los comandos desconocidos
  void (*_fnSend)(void);        // Funcion para enviar datos personalizados

  NbBuffer* _bufferIn;  // Buffer de entrada
  NbBuffer* _bufferOut; // Buffer de salida
  
  char _digitalIns[NB_DIGITAL_INS_LEN];       // Lista de estados de entradas digitales
  char _digitalInsValues[NB_DIGITAL_INS_LEN]; // Lista de valores de entradas digitales

  char _analogIns[NB_ANALOG_INS_LEN];       // Lista de estados entradas analogicas
  int _analogInsValues[NB_ANALOG_INS_LEN];  // Lista de valores de entradas analogicas

  bool _sendFirstMessage;
  bool _resetWhenDisconnected;

  void* _;  // Quitar esto hace el codigo lento

protected:
  bool _connected;

public:
  
  // Constructor
  Nb(void);

  // Obtener los bufferes
  NbBuffer* getIn(void);
  NbBuffer* getOut(void);

  // Asignacion de callbacks
  void setCallbackPre(bool (*)(char, bool&));
  void setCallbackObject(bool (*)(int));
  void setCallbackUnk(bool (*)(char));
  void setCallbackSend(void (*)(void));

  // Aigna si se debe restear el microcontrolador cuando se desconecte
  void setResetWhenDisconnected(bool);

  // Funciones a definir en las especificaciones
  virtual bool connected()=0;
  virtual void loadBuffer()=0;
  virtual void print(char*, int)=0;
  virtual bool available(void)=0;
  virtual int readByte(void)=0;

  void printByte(char);
  
  // Funciones principales
  void operate(void);
  bool execute(char cmd);
  void restart(void);
  void task(int pin=-1);

  // Primer mensjase
  bool firstMessage(void);
  void setSendFirstMessage(bool);
  void sendFirstMessage(int);

  // Para lectura
  int availables(void);      // cantidad de byets disponibles
  bool available(int);  // Existen N bytes en la entrada
  void recovery(void);
  void purge(void); // Quitar bytes procesados
  int read(void);
  int readInt(void);
  long readLong(void);
  int readBytes(char*, int len);
  void loadByte(char);  // Cargar en la entrada

  // Para la escritura
  int addByte(char);
  int addInt(int);
  int addLong(long);
  void send(void);  // Enviar bytes en el buffer de salida
  
  //--------------------------------------------------------------------------------------------------------
  // Comandos principales
  bool cmdSetStateDigital(void);
  bool cmdSetStateAnalog(void);
  bool cmdInitOutput(void);
  bool cmdInitInput(char);
  bool cmdWrite(char);
  bool cmdFinish(void);
  bool cmdObject(void);
  bool cmdElse(char);
  
  void setObjectValue(int); // Para enviar valores a objetos

  //--------------------------------------------------------------------------------------------------------
  // Puentes H
  bool cmdMotorDcWrite(void);

  //--------------------------------------------------------------------------------------------------------
  // Step To Step Motors
  bool cmdStepToStepMove(void);

  //--------------------------------------------------------------------------------------------------------
  // Escribir en la salida
  void digitalInListenersRead(void);
  void analogInListenersRead(void);
  
};

//========================================================================================================
// Dependiendo de las conexiones incluidas
//--------------------------------------------------------------------------------------------------------

// Incluir el soporte a SoftwareSerial si se incluyo la libreria
#ifdef SoftwareSerial_h
  #include "NbSoftwareSerial.h"
#endif

// Incluir el soporte a ADK si se incluyo la libreria
#ifdef _ADK_H_
  #include "NbAdk.h"
#endif

// Incluir el soporte a SPP si se incluyo la libreria (Bluetooth)
#ifdef _spp_h_
  #include "NbSPP.h"
#endif

#endif
