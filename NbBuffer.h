/* ========================================================================
 * Nebula Arduino Lib: NbBuffer v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#ifndef NbBuffer_h
#define NbBuffer_h

//========================================================================================================
// List Bytes
//--------------------------------------------------------------------------------------------------------

// Macros basicos
#define BYTEOF(num)   (num & 0xFF)
#define BYTE_BITS     8
#define LONG_BYTES    4

#define IO_LEN        32
#define MAX_LEN       512

#include "Arduino.h"

// Estructura de un nodo de una lista de bytes
typedef struct __NbByte{
  char data;
  struct __NbByte* next;
}NbByte;

// Clase para el manejo de una lista de bytes utilizada como buffer
class NbBuffer{
private:
  NbByte* _list;  // Primer nodo de la lista
  NbByte* _last;  // Ultimo nodo de la lista
  NbByte* _next;  // Siguiente nodo a leer del buffer
  int _available; // Cantidad de elementos disponibles para leer
  int _index;     // Cantidad de elementos leidos sin liberar
  
public:
  NbBuffer(void);
  NbByte* first(void);
  int addByte(char);
  int addBytes(long, int);
  int addInt(int);
  int addLong(long);
  int read(void);
  long read(int);
  int readInt(void);
  long readLong(void);
  int readBytes(char*);
  int readBytes(char*, int);
  int availables(void);
  bool available(int);
  
  void recovery(void);
  void purge(void);
  void restart(void);
  
};

#endif
