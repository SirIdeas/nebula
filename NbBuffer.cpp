/**
 * Proyecto Nébula
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

#include "NbBuffer.h"

//========================================================================================================
// List Bytes
//--------------------------------------------------------------------------------------------------------

// Constructor
NbBuffer::NbBuffer(void){

  // Inicializarion
  _next = _list = _last = NULL;
  _available = _index = 0;
  
}

// Obtiene el primero nodo
NbByte* NbBuffer::first(void){
  return _list; 
}

// Agrega un nodo
int NbBuffer::addByte(char data){

  // Inicializar el nodo
  NbByte* node = new NbByte;
  node->data = BYTEOF(data);
  node->next = NULL;
  
  // Agregar al principio
  if(_list == NULL){
    _next = _list = _last = node;
    
  // Agregar al final
  }else{
    _last->next = node;
    _last = node;
  }
  
  // Incrementamos los disponibles
  _available++;
  
  return _available;
  
}

// Agregar los primeros N bytes del numero data
int NbBuffer::addBytes(long data, int size){
  for(int i=size; i>0; i--){
    addByte(data);
    data >>= BYTE_BITS;
  }
  return _available;
}

// Agregar un int como dos bytes
int NbBuffer::addInt(int data){
  addBytes(data, sizeof(int));
  return _available;
}

// Agregar un long como 4 bytes
int NbBuffer::addLong(long data){
  addBytes(data, sizeof(long));
  return _available;
}

// Recupera los bytes leidos sin purgar
void NbBuffer::recovery(void){
  _next = _list;
  _available += _index;
  _index = 0;
}

// Libera los elementos ya leidos
void NbBuffer::purge(void){

  NbByte* node;
  
  // Recorrer la lista hasta llegar al elementos next
  while(_list != _next){
    node = _list;
    _list = _list->next;
    delete(node);
  }
  
  // Reiniciamos los leidos
  _index = 0;
  
}

// Libera todos los elementos de la lista
void NbBuffer::restart(void){

  NbByte* node;
  
  // Recorrer toda la lista
  while(_list){
    node = _list;
    _list = _list->next;
    delete(node);
  }
  
  // reinicialiar contadores
  _available = _index = 0;
  
}

// Devuelve la cantidad de elementos cargaddos en el buffer
int NbBuffer::availables(void){
  return _available;
}

// Indica si existe tantos elementos disponibles como lo indica el parametro "size"
bool NbBuffer::available(int size){
  return _available>=size;
}

// Leer un byte del buffer
int NbBuffer::read(void){

  // Data invalida
  int data = -1;
  
  // Si existe el elemento actual
  if(_next){
    _available--;         // Disminuimos los disponibles
    _index++;             // Aumentamos los leitos
    data = _next->data;   // Tomamos la data
    _next = _next->next;  // Desplazamos el puntero de actual
  }
  
  // Retornar data
  return data;
  
}

// Leer una cantidad de bytes (maximo 4)
long NbBuffer::read(int size){

  // Limitar la cantidad de bytes a leer al tamaño de un long
  if(size>LONG_BYTES) size = LONG_BYTES;
  
  // Si existen la cantidad de elementos disponibles
  if(available(size)){
    
    // Leer los bytes solicitados
    char buffer[LONG_BYTES];
    for(int i=0; i<size; i++)
      buffer[i] = read();
    
    // Recorrer los bytes de forma inversa para ir concatenando los bytes
    long ret = 0;
    for(int count = size; count>0; count--)
      ret = ret << BYTE_BITS | BYTEOF(buffer[count-1]);
    
    // Retornar resultado
    return ret;
    
  }
  
  // Retornar lectura invalida
  return -1;
  
}

// Lee un int
int NbBuffer::readInt(void){
  return read(sizeof(int));
}

// Lee un long
long NbBuffer::readLong(void){
  return read(sizeof(long));
}

// Lectura de una cantidad de bytes y guardarlas en buffer
int NbBuffer::readBytes(char* buffer, int len){
  
  int count=0;
  
  // Leer los bytes en el array pasado por parametro
  while(count<len)
    if(available(1))
      buffer[count++] = read();
    else
      break;
  
  // Retornar la cantidad de bytes leidos
  return count;
    
}

// Lectura de una cantidad de bytes. El siguiente byte en el buffer indica la cantidad de bytes subsiguientes a leer
int NbBuffer::readBytes(char* buffer){

  // Si existen al menos un byte disponible
  if(available(1)){

    // Lectura de la cantidad de bytes a leer
    int n = read();

    // Leer los bytes en el array pasado por parametro
    if(n == readBytes(buffer, n))
      return n;
      
  }
  
  // Retornar una lectura invalida
  return -1;
  
}

