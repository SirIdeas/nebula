# Nébula Librería Arduino
Librería Arduino del proyecto Nébula basada en la librería en [USB Host Library 2.0](https://github.com/felis/USB_Host_Shield_2.0).
Contiene clases de conexión para ADK, Bluetooth y cominucación Serial.

## Requerimientos
-Arduino IDE.
-Librearía [USB Host Library 2.0](https://github.com/felis/USB_Host_Shield_2.0).

## Instalación
Descargar desde [GitHub](https://github.com/SirIdeas/nebula/archive/arduino.zip) y descomprimir en la carpeta ArduinoIDE/libraries/.

## Uso Básico
### Comunicación BT
```cpp
// Incluir Librerías
#include <SPP.h>
#include <Nb.h>

// Instanciar objetos necesarios
USB Usb;
BTD Btd(&Usb);
SPP SerialBT(&Btd, "NebulaBoard", "1234");

// Instanciación de objeto a utilizar
NbSPP com(&SerialBT);

// Configuración
void setup(){
  if (Usb.Init() == -1) {
    while(1); //halt
  }
}

// Procesamiento
void loop(){
  Usb.Task();
  com.task();
}
```

### Comunicación por ADK
```cpp
// Incluir Librerías
#include <adk.h>
#include <Nb.h>

// Instanciar objetos necesarios
USB Usb;
ADK adk(&Usb, "UNEG", "NebulaBoard", "Nebula Board", "1.0", "http://nebula.sirideas.com/", "0000000012345678");

// Instanciación de objeto a utilizar
NbAdk com(&adk);

// Configuración
void setup() {
  if (Usb.Init() == -1) {
    while (1);
  }
}

// Procesamiento
void loop() {
  Usb.Task();
  com.task();
}
```

## Comunicación Serial
```cpp
// Incluir Librerías
#include <Nb.h>
#include <NbSerial.h>

// Instanciación de objeto a utilizar
NbSerial com(&Serial);

// Configuración
void setup(){
  Serial.begin(115200);
}

// Procesamiento
void loop(){
  com.task();
}
```

## Uso Avanzado

### Tareas de objectos
Asignación de callback que permite procesar las tareas enviadas a objetos. recibe el ID del objeto al que se le envía la tarea. Debe retornar true si logra realizar una tarea satisfactoriamente.
```cpp
bool callback_object(int id){
  return true;
}

// Dentro de la función void setup()
com.setCallbackObject(callback_object);
```

### Comandos desconocidos
Asignación del callback llamado en el caso de que el comando no sea reconocido. Debe retornar true si logra realizar una tarea satisfactoriamente.
```cpp
bool callback_unk(int cmd){
  return true;
}

// Dentro de la función void setup()
com.setCallbackUnk(callback_unk);
```

### Envío de datos extra
Aignación de callback para enviar datos personalizados. 
```cpp
void callback_send(void){
}

// Dentro de la función void setup()
com.setCallbackSend(callback_send);
```

### Preprocesamiento de comandos
Asignación de callback que permite procesar los comando recibidos de forma personalizada antes de ser manejados por la biblioteca. Recibe el próximo comando a evaluar y un segundo párametro booleano por referencia al cual se le debe asigna true cuando el comando recibido no debe ser procesado por el callback.
Debe retornar true si reconoce el comando fué procesado satisfactoriamente, o si fué un comando desconocido.
```cpp
bool callback_pre(char cmd, bool& unk){
  return true;
}

// Dentro de la función void setup()
com.setCallbackPre(callback_pre);
```

## Mas información
Visita el website con la documentación del [Proyecto Nébula](http://nebula.sirideas.com/).

##Licencia
Liberado bajo licencia [MIT](https://github.com/SirIdeas/nebula/blob/master/LICENSE).