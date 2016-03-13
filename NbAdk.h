/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
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
