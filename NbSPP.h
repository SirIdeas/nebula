/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
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
  bool hasIn(void);
};

#endif
