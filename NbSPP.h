/* ========================================================================
 * Nebula Arduino Lib: NbSPP v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

#ifndef NbSPP_h
#define NbSPP_h

//========================================================================================================
// Nb Sketch Bluetooth
//--------------------------------------------------------------------------------------------------------

#include "../USB_Host_Shield_2_0_master/SPP.h"
#include "Nb.h"

// Clase para comunicacion Bluetooth con Nb
class NbSPP : public Nb{
private:
  SPP* _spp;
  
public:
  NbSPP(SPP*);
  bool connected(void);
  void loadBuffer(void);
  void print(char*, int);
  int readByte(void);
  bool available(void);
};

#endif
