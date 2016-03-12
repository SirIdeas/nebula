<?php

return array(
  "0x01" => array(
    "nombre" => "MSG_FINISH",
    "accion" => "Endica el final del mensaje.",
  ),
  "0x02" => array(
    "nombre" => "MSG_DIGITAL_IN_CONF",
    "accion" => "Configurar un pin como entrada digital. Utiliza un byte para indica el pin a configurar.",
  ),
  "0x03" => array(
    "nombre" => "MSG_PIN_MODE_OUT",
    "accion" => "Configurar un pin como salida. Utiliza un byte para indica el pin a configurar.",
  ),
  "0x04" => array(
    "nombre" => "MSG_ANALOG_WRITE",
    "accion" => "Escribe en una salida analógica. Utiliza un byte para el pin a escribir y otro para valor a escribir.",
  ),
  "0x05" => array(
    "nombre" => "MSG_ANALOG_IN_CONF",
    "accion" => "Configurar un pin como entrada analógica. Utiliza un byte para indica el pin a configurar.",
  ),
  "0x06" => array(
    "nombre" => "MSG_ANALOG_IN_READ",
    "accion" => "Indica el cambio de una entrada analógica. Utiliza un byte para indicar la entrada que cambió, y un entero para indicar el valor leído.",
  ),
  "0x07" => array(
    "nombre" => "MSG_DIGITAL_WRITE",
    "accion" => "Escribe una salida digital. Utiliza un byte para indicar el pin a escribir y otro para indicar el valor a escribir.",
  ),
  "0x08" => array(
    "nombre" => "MSG_OBJECT_CMD",
    "accion" => "Mensaje para objeto personalizado. Utiliza un entero para el ID del objeto seguido de los bytes pertinentes para el mensaje enviado.",
  ),
  "0x09" => array(
    "nombre" => "MSG_DIGITAL_IN_READ",
    "accion" => "Indica el cambio de una entrada digital. Utiliza un byte para indicar la entrada que cambió, y un byte para indicar el valor leído.",
  ),
  "0x0A" => array(
    "nombre" => "MSG_MOTORDC_WRITE",
    "accion" => "Regula la velocidad de un motor por medio de un puente H. Utiliza 3 bytes para indicar los pines utilizados (EN, IN1, IN2) y otros 3 para indicar los valores a escribir.",
  ),
  "0x0B" => array(
    "nombre" => "MSG_STEPTOSTEP_MOVE",
    "accion" => "Mueve un motor bipolar paso a paso. Utiliza 4 bytes para indicar los pines del motor, un entero para indicar la cantidad de pasos, un entero para indicar la velocidad, un byte para la dirección y otro para indicar el paso actual.",
  ),
  "0x0C" => array(
    "nombre" => "MSG_SET_STATE_DIGITAL",
    "accion" => "Cambia el estado de activación del chequeo de una entrada digital. Utiliza un byte para indicar el pin de la entrada a cambiar y otro para indicar se activa o desactiva el chequeo.",
  ),
  "0x0D" => array(
    "nombre" => "MSG_SET_STATE_ANALOG",
    "accion" => "Cambia el estado de activación del chequeo de una entrada analógica. Utiliza un byte para indicar el pin de la entrada a cambiar y otro para indicar se activa o desactiva el chequeo.",
  ),
);