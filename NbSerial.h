/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
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
