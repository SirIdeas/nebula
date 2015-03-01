/* ========================================================================
 * Nebula Arduino Lib: NbSoftwareSerial v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

#ifndef NbSerial_h
#define NbSerial_h

//========================================================================================================
// Nb Serial
//--------------------------------------------------------------------------------------------------------

#include "Nb.h"

// Clase para comunicacion por SoftwareSerial con Nb
class NbSerial : public Nb{
private:
  HardwareSerial* _hs;
public:
  NbSerial(HardwareSerial*);
  bool connected(void);
  void loadBuffer(void);
  void print(char*, int);
  int readByte(void);
  bool hasIn(void);
};

#endif
