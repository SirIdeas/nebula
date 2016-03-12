(# parent:views/_content.php #)
(# set:title="{$_env->name} | Componentes" #)
(# set:pagina="documentacion" #)
(# set:paso="componentes" #)

<h1 id="componentes">Componentes</h1>

<p>
  Los componentes son abstracciones de los elementos que se pueden encontrar en nuestros proyectos, por ejemplo: LEDs, interruptores, botones, motores, sensores de temperatura y distancia entre otros. Todos los objetos sin implementaciones de la clase <?php docEnlace("NbCmp") ?>
</p>



<h4 id="estado">Estado</h4>
<p>
  Por motivos de redimiento, puede ser necesario que ciertos elementos esten desactivados para que no se realice la lectura de su entrada o no escribir su salida en el microcontrolador. Para manejar el estado de los componentes se puede utilizar los siguientes métodos:
</p>
<pre>
// Instancia del componente
NbCmp cmp = new NbCmp();

// Indica si el componentes esta activo o no.
cmp.isActive();

// Activa el componente
cmp.activate();

// Activa el componente
cmp.desactivate();
</pre>



<h4 id="valor">Valor</h4>
<p>
  El valor representa la magnitud de la entrada o salida del componente si este es aplicable. En el caso de las entradas representa el valor leído, mientras que en las salidas representa el valor a escribir.
</p>
<p>
  El valor puede ser manejado por los siguiente métodos:
</p>
<pre>
// Instancia del componente
NbCmp cmp = new NbCmp();

// Devuelve el valor actual del componente.
cmp.getValue();

// Asigna un valor al componente.
cmp.setValue(10);

// Devuelve el valor actual del componente proyectado en una escala de 0 a 255.
cmp.getValue(0,255,0,1023);
</pre>



<h4 id="eventos">Eventos</h4>
<p>
  Los componentes poseen dos eventos: cambio de estado y cambio de valor. Estos pueden ser detectados y manejados mediantes las clases <?php docEnlace("NbCmp.OnStateChangeListener") ?> y <?php docEnlace("NbCmp.OnValueChangeListener") ?> correspondientemente como se muestra a continuación:
</p>
<pre>
// Instancia del componente
NbCmp cmp = new NbCmp();

// Detectar cambio de estado.
cmp.setOnStateChangeListener(new NbCmp.OnStateChangeListener() {
  @Override
  public void onStateChange(NbCmp component, boolean newState) {
    // component: Componente que cambió de estado. En este caso se trata de cmp
    // newState: Nuevo estado del componente.
  }
});

// Detectar cambios de valor.
cmp.setOnValueChangeListener(new NbCmp.OnValueChangeListener() {
  @Override
  public void onValueChange(NbCmp component, int newValue, int oldValue) {
    // component: Componente que cambió de valor. En este caso se trata de cmp
    // newValue: Nuevo valor.
    // oldValue: Valor anterior.
  }
});
</pre>



<h4 id="componentes-primarios">Componentes primarios</h4>
<p>
  Estos pueden ser clasificados según su tipo de señal: Digital o Analógica; según su función: Entrada o Salida; o según si complejidad: Simples o Compuestos. A continuación se presentan Todos lo componentes ya implementados
</p>



<h5 id="led-digital">LED Digital (<?php docEnlace("NbLedDigital") ?>)</h5>
<p>
  <i>Salida/Digital/Simple</i>. No requiere identificador. Require una salida digital. Tiene dos posibles salidas: 0 (LOW) o 1 (HIGH).
</p>
<pre>NbLedAnalog led = new NbLedAnalog(5);</pre>



<h5 led="led-analogico">LED Analógico (<?php docEnlace("NbLedAnalog") ?>)</h5>
<p>
  <i>Salida/Analógica/Simple</i>. No requiere identificador. Require una salida PWM. Su rango de valor va de 0 a 255.
</p>
<pre>NbLedAnalog led = new NbLedAnalog(5);</pre>



<h5 id="boton">Botón (<?php docEnlace("NbButton") ?>)</h5>
<p>
  <i>Entrada/Digital/Simple</i>. No requiere identificador. Require una entrada digital. Su posibles valores son 0 (LOW) o 1 (HIGH).
</p>
<pre>NbButton button = new NbButton(4);</pre>



<h5 id="switch">Interruptor (<?php docEnlace("NbSwitch") ?>)</h5>
<p>
  <i>Entrada/Digital/Simple</i>. No requiere identificador. Require una entrada digital. Su posibles valores son 0 (LOW) o 1 (HIGH).
</p>
<pre>NbSwitch switch = new NbSwitch(4);</pre>



<h5 id="potenciometro">Potenciómetro (<?php docEnlace("NbTrimmer") ?>)</h5>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre>NbTrimmer trimmer = new NbTrimmer(4);</pre>



<h5 id="cny70">Sensor de línea CNY70 (<?php docEnlace("NbCNY70") ?>)</h5>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre>NbCNY70 cny = new NbCNY70(4);</pre>



<h5 id="ldr">Fotoresistencia o LDR (<?php docEnlace("NbLDR") ?>)</h5>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre>NbLDR ldr = new NbLDR(4);</pre>



<h5 id="lm35">Sensor de temperatura LM35 (<?php docEnlace("NbLM35") ?>)</h5>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre>
NbLM35 ldr = new NbLM35(1);

// Devuelve la lectura en grados Celcius.
ldr.getValueAtCelcius();
</pre>



<h5 id="sharp">Sensor de distancia reflectivo SHARP (<?php docEnlace("NbSHARP") ?>)</h5>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre>
NbSHARP sharp = new NbSHARP(1);

// Devuelve la lectura en CM.
ldr.getValueAtCm();
</pre>



<h5 id="step-to-step">Motor Bipolar Paso a Paso (<?php docEnlace("NbStepToStep") ?>)</h5>
<p>
  <i>Salida/Especial/Compuesta</i>. No requiere identificador. Require 4 salidas digitales.
</p>
<pre>
// Instancia con pines A=1, B=2, C=3 y D=4
NbStepToStep motor = new NbStepToStep(1,2,3,4);

// Mueve el motor 150 pasos con pausas de 40ms.
motor.move(150, 40, 0);

// Mueve el motor 300 pasos con pausas de 20ms en direción contraria.
motor.move(-300, 20);
</pre>



<h4 id="motores">Motores</h4>
<p>
  Los motores representan los principales actuadores utilizados en los proyectos de automatización.Estos pueden ser motores de corriente continua (Motores CC) los cuales suelen ser controlados a través de puentes H; o Servomotores que ya disponen de todo lo necesario para ser controlador por salidas PWM. Los motores tratados en este apartado no incluyen los motores paso a paso a que son tratados diferente. Independiente del tipo de motor tratado, siempre dispondrán de una salida PWM para controlar su velocidad. A continuación se presenta como utilizar la meta clase <?php docEnlace("NbMotor") ?>.
</p>
<pre>
// Instanciar un motor simple.
NbMotor motor = new NbMotor(10, 0, 100);

// Devuelve la velocidad actual del motor.
motor.getVel();

// Asigna la velocidad al motor.
// Si la velocidad esta fuera del rango se
// tomará el valor válido mas cercano.
motor.setVel(40);

// Detiene el motor.
motor.stop().
</pre>

<p>
  En el caso de los motores CC dependiendo de la configuración de conexión el puente H utilizado para su control, estos motores pueden ser unidireccionales, o bidireccionales. 
</p>



<h5 id="motor-one-dir">Motores CC Unidireccionales (<?php docEnlace("NbMotorDCOneDir") ?>)</h5>
<p>
  Requiere una salida PWM para indicar la velocidad y una salida digital para la dirección (aunque solo varía su salida en caso de que el motor este detenido). A continuación de indica un ejemplo de como usarlo:
</p>
<pre>
NbMotorDCOneDir motor;

// Instanciae un motor con una sola dirección. El puente H utiliza los pines
enable = 12 y in1 = 11
motor = new NbMotorDCOneDir(12, 11);

// Instancia un motor con una sola dirección. El puente H utiliza los pines
enable = 12, in1 = 11 y con una velocidad máxima de 100.
motor = new NbMotorDCOneDir(12, 11, 100);
</pre>



<h5 id="motor-two-dir">Motores CC Bidireccionales (<?php docEnlace("NbMotorDCTwoDir") ?>)</h5>
<p>
  Requiere una salida PWM para indicar la velocidad y dos salidas digitales para la dirección. Este tipo de motores pueden girar en dos sentidos. La A continuación de indica un ejemplo de como usarlo:
</p>
<pre>
NbMotorDCTwoDir motor;

// Instancias un motor con dos direcciones dirección. El puente H utiliza los pines
enable = 12, in1 = 11 y in2 = 10.
motor = new NbMotorDCTwoDir(12, 11, 10);

// Instancias un motor con dos direcciones dirección. El puente H utiliza los pines
enable = 12, in1 = 11, in2 = 10 y máxima velocidad de 100.
motor = new NbMotorDCTwoDir(12, 11, 100);

// Devuelve el sentido de giro. Los posibles valores
// que puede retornar son: NbDoubleDir.DIR_RIGHT, 
// NbDoubleDir.DIR_LEFT o NbDoubleDir.DIR_NONE
motor.getDir();

// Asigna la dirección de giro del motor.
motor.setDir(NbDoubleDir.DIR_LEFT);
</pre>

<h4 id="componentes-objetos">Componentes Objetos</h4>
<p>
  Los componentes objetos son elementos cuya uso no depende solo de un pin, o requiere de uso de código extra en el Arduino para su uso, como por ejemplo el uso de librerías, o algoritmos especializados. El utilizar estos componentes requieren 
</p>



<h5 id="servomotor">Servomotor (<?php docEnlace("NbServo") ?>)</h5>
<p>
  Los servomotores son implementaciones de los <?php docEnlace("NbMotor") ?>, sin embargo, debido a que requieren de la librería Servo de Arduino para su correcto funcionamiento en Nébula son tratados como Componentes Objetos, por lo que requieren codificación extra en Arduino.
</p>
<h6>Código en Arduino</h6>
<pre><?php echo getCodeFile("arduino-NbServo") ?></pre>
<p>
  Por otro lado, para usar los servos en la aplicación Android podemos se realiza similar a como se hace con la clase <?php docEnlace("NbMotorDCTwoDir") ?>, con la salvedad de que, por definición, la velocidad del servo varía entre 0 y 180 teniendo el punto de detención en 90.
</p>
<pre>
// Identificador del objeto
int ID_SERVO = 1;

// Instanciar el servo;
NbServo servo = new NbServo(ID_SERVO);

// Devuelve el sentido de giro. Los posibles valores
// que puede retornar son: NbDoubleDir.DIR_RIGHT, 
// NbDoubleDir.DIR_LEFT o NbDoubleDir.DIR_NONE
motor.getDir();

// Asigna la dirección de giro del motor.
motor.setDir(NbDoubleDir.DIR_LEFT);
</pre>



<h5 id="pantalla-lcd">Pantallas LCD (<?php docEnlace("NbLiquidCrystal") ?>)</h5>
<p>
  Representa las pantallas LCD que puede ser manejadas mediante la librería LiquiqCrystal para Arduino. Requiere <a href="<?php Am::eUrl() ?>/personalizacion-en-arduino">Personalización en Arduino</a> ya que es imposible automatizar el proceso de compilado con esta utilidad en Arduino.
</p>
<h6>Código en Arduino</h6>
<pre><?php echo getCodeFile("arduino-NbLiquidCrystal") ?></pre>
<p>
  Por otro lado en la aplicación Android se puede utilizad de la siguiente forma:
</p>
<pre>
// Identificador del objeto
int ID_LCD = 1;

// Instanciar una pantalla con 16 columnas
NbLiquidCrystal lcd = new NbLiquidCrystal(ID_LCD, 16);

// Devuelve la columna actual del cursor.
lcd.getCursorX();

// Devuelve la fila actual del cursor.
lcd.getCursorY();

// Devuelve la cantidad de columnas de la pantalla.
lcd.getCols();

// Limpia la pantalla.
lcd.clear();

// Mueve el cursor de la pantalla.
lcd.setCursor(3,1);

// Imprime una cadena en la pantalla.
lcd.print("Hola Mundo!");
</pre>



<h5 id="hcsr04">Sensor de distancia HC-SR04 (<?php docEnlace("NbHCSR04") ?>)</h5>
<p>
  Los sensores de distancia HC-SR04 permiten determinar la distancia de un objeto de forma indirecta midiendo el tiempo que tarda en regresar las ondas de ultrasonido emitidas por el mismo. Debido a que la lectura de este tiempo depende de funciones especiales de Arduino es tratado como un Componente Objeto.
</p>
<p>Código en Arduino</p>
<pre><?php echo getCodeFile("arduino-NbHCSR04") ?></pre>
