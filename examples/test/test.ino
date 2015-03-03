/* ========================================================================
 * Nebula Arduino Lib: Sample Bluetooth Single Comunication v0.0.1-beta
 * http://sirideas.github.io/nebula/
 * ========================================================================
 * Copyright 2014-2015 UNEG
 * Licensed under MIT (https://github.com/SirIdeas/nebula/blob/master/LICENSE)
 * ========================================================================
 */

#include <SPP.h>
#include <Nb.h>

/* ========================================================================
 * IDS de objetos
 * ========================================================================
 */
#define ID_HCSR04_0 1
#define ID_HCSR04_1 2
#define ID_LCD_1 3
#define ID_SERVO_0 4
#define ID_SERVO_1 5
#define ID_SERVO_2 6

/* ========================================================================
 * Comunicación basica
 * ========================================================================
 */

USB Usb;
BTD Btd(&Usb);
SPP SerialBT(&Btd, "NebulaBoard", "1234");
NbSPP com(&SerialBT);

/* ========================================================================
 * HCSR04
 * ========================================================================
 */

#define TRIG_PIN_1 33
#define ECHO_PIN_1 32

#define TRIG_PIN_2 40
#define ECHO_PIN_2 39

bool is_active_hcsr04 = false;

// ultimas lecturas de los sensores
long last_hcsr04_lect_1 = 0;
long last_hcsr04_lect_2 = 1;

// Read HCSR04
long read_hcsr04(int trig, int echo){
  digitalWrite(trig, LOW); 
  delayMicroseconds(2);
  digitalWrite(trig, HIGH);
  delayMicroseconds(10);
  digitalWrite(trig, LOW);
  return pulseIn(echo, HIGH);
}

// Init HCSR04
void init_hcsr04(int trig, int echo){
  
  pinMode(trig, OUTPUT);
  pinMode(echo, INPUT);

  NB_TRACE_IN_NL("init_hcsr04(");
  NB_TRACE2(trig, ",");
  NB_TRACE2(echo, ")");

}

// Init all HCSR
void active_hcsr04(){
  is_active_hcsr04 = true;
  init_hcsr04(TRIG_PIN_1, ECHO_PIN_1);
  init_hcsr04(TRIG_PIN_2, ECHO_PIN_2);
}

// Enviar lectura del hcsr04
void cmd_send_hcsr04(int id, int trig, int echo, long &last_lect){
  long lect = read_hcsr04(trig, echo);
  if(last_lect == lect) return;
  com.setObjectValue(id);  // ID del componente
  com.addLong(lect);
  last_lect = lect;

  NB_TRACE_IN_NL("cmd_send_hcsr04(");
  NB_TRACE2(id, ",");
  NB_TRACE2(lect, ")");

}

// Enviar todoas las lecturas de los hcsr04
void send_hcsr04_lects(){
  if(!is_active_hcsr04) return;
  cmd_send_hcsr04(ID_HCSR04_0, TRIG_PIN_1, ECHO_PIN_1, last_hcsr04_lect_1);
  cmd_send_hcsr04(ID_HCSR04_1, TRIG_PIN_2, ECHO_PIN_2, last_hcsr04_lect_2);
}

/* ========================================================================
 * LiquidCrystal
 * ========================================================================
 */
#include <LiquidCrystal.h>

LiquidCrystal lcd1(31, 30, 25, 24, 23, 22);

// Inicializar LCD
void init_lcd(){

  lcd1.begin(16,2);

  NB_TRACE_IN_NL("init_lcd()");

}

// Procesar comando recibido para una LCD
bool lcd_cmd(int id){

  LiquidCrystal* lcd = NULL;

  // Para varias LCDs
  if(id == ID_LCD_1) lcd = &lcd1;
  else{

    // ID desconocido
    NB_TRACE_IN_NL("ID UNKNOWN IN lcd_cmd(");
    NB_TRACE2(id, ") ");
    return false;
    
  }
  
  if(com.available(1)){

    char cmd = com.read();

    // Limpiar pantalla
    if(cmd == NB_CMD_LCD_CLEAR){
      lcd->clear();
      NB_TRACE_IN_NL("NB_CMD_LCD_CLEAR()");
      return true;
    }

    // Mover cursor
    if(cmd == NB_CMD_LCD_SET_CURSOR){
      if(com.available(2)){

        char col = com.read();
        char row = com.read();
        lcd->setCursor(col, row);
        
        NB_TRACE_IN_NL("NB_CMD_LCD_SET_CURSOR(");
        NB_TRACE2(col, ",");
        NB_TRACE2(row, ")");

        return true;

      }

      NB_TRACE_BAD_CMD("NB_CMD_LCD_SET_CURSOR", com.availables());

      return false; // Si faltan bytes salir

    }

    // Imprimir por pantalla
    if(cmd == NB_CMD_LCD_PRINT){

      if(com.available(1)){

        char len = com.read();  // Leer el tamano de la cadena

        if(com.available(len)){

          char bytes[17];
          com.readBytes(bytes, len);  // leer cadena
          bytes[len] = '\0';
          lcd->print(bytes);
          
          NB_TRACE_IN_NL("NB_CMD_LCD_PRINT(");
          NB_TRACE(bytes);
          NB_TRACE(")");

          return true;

        }

      }

      NB_TRACE_BAD_CMD("NB_CMD_LCD_PRINT", com.availables());

      return false; // Si faltan bytes salir

    }

  }

  NB_TRACE_BAD_CMD("lcd_cmd", com.availables());
  return false; // Si faltan bytes salir

}

/* ========================================================================
 * Manejadores Servomotores
 * ========================================================================
 */
#include <Servo.h>
#define SERVO_0 10
#define SERVO_1 11
#define SERVO_2 12

Servo s0,s1,s2;

void init_servos(){

  s0.attach(SERVO_0);
  s1.attach(SERVO_1);
  s2.attach(SERVO_2);

  NB_TRACE_IN_NL("init_servos()");

}

// Escribir en los servos
bool write_servo(int id){
  if(com.available(1)){

    Servo *servo;

    // seleccionar servo
       if(id == ID_SERVO_0) servo = &s0;
    else if(id == ID_SERVO_1) servo = &s1;
    else if(id == ID_SERVO_2) servo = &s2;
    else{

      // ID Desconcido
      NB_TRACE_IN_NL("ID UNKNOWN IN write_servo(");
      NB_TRACE2(id, ")");
      return false;

    }

    byte vel = com.read();

    servo->write(vel);

    NB_TRACE_IN_NL("write_servo(");
    NB_TRACE2(id, ", ");
    NB_TRACE2(vel, ") ");

    return true;

  }

  NB_TRACE_BAD_CMD("write_servo", com.availables());
  return false;

}

/* ========================================================================
 * Manejadores para la comunicacion
 * ========================================================================
 */
#define ACTIVE_HCSR04 (__NB_LAST_MSG_CODE + 0x1)
#define INIT_LCD      (__NB_LAST_MSG_CODE + 0x2)
#define INIT_SERVOS   (__NB_LAST_MSG_CODE + 0x3)

// Procesar la inicializacion
bool cmd_else(char cmd){

  switch(cmd){
    case ACTIVE_HCSR04: active_hcsr04();  return true;
    case INIT_LCD:      init_lcd();       return true;
    case INIT_SERVOS:   init_servos();    return true;
  }
  return false;

}

// procesar envios
void cmd_send(void){
  send_hcsr04_lects();
}

// procesar mensaje a objetos
bool cmd_object(int id){
  
  if(id == ID_LCD_1)
    return lcd_cmd(id);
  if(id == ID_SERVO_0 || id == ID_SERVO_1 || id == ID_SERVO_2)
    return write_servo(id);

  return true;

}

/* ========================================================================
 * Inicializacion
 * ========================================================================
 */
void setup(){

  Serial.begin(9600);
  pinMode(13, OUTPUT);

  // Init COM funtions
  com.setCallbackUnk(cmd_else);
  com.setCallbackObject(cmd_object);
  com.setCallbackSend(cmd_send);

  if (Usb.Init() == -1) {
    while(1); //halt
  }
}

/* ========================================================================
 * Loop principal
 * ========================================================================
 */
void loop(){
  Usb.Task();
  com.task();
}
