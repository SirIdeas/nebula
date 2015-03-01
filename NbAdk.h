/* ========================================================================
 * Nebula Arduino Lib: NbAdk v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */
 
#ifndef NbAdk_h
#define NbAdk_h

//========================================================================================================
// Nb Sketch ADK
//--------------------------------------------------------------------------------------------------------

#include "../USB_Host_Shield_2_0_master/adk.h"
#include "Nb.h"

// Clase para comunicacion Bluetooth con Nb
class NbAdk : public Nb{
private:
  ADK* _adk;
  
public:
  NbAdk(ADK*);
  bool connected(void);
  void loadBuffer(void);
  void print(char*, int);
  int readByte(void);
  bool hasIn(void);
};

#endif
