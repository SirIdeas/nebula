(: parent:'views/content.php'
(: $title = 'Componentes'
(: $pagina = 'documentacion'
(: $paso = 'componentes'

<h1 id="componentes">Componentes</h1>

<p>
  Los componentes son abstracciones de los elementos que se pueden encontrar en nuestros proyectos, por ejemplo: LEDs, interruptores, botones, motores, sensores de temperatura y distancia entre otros. Todos los objetos sin implementaciones de la clase (: docEnlace("NbCmp") :).
</p>



<h2 id="estado">Estado</h2>
<p>
  Por motivos de redimiento, puede ser necesario que ciertos elementos esten desactivados para que no se realice la lectura de su entrada o no escribir su salida en el microcontrolador. Para manejar el estado de los componentes se puede utilizar los siguientes métodos:
</p>
<pre><code class="language-java">
// Instancia del componente
NbCmp cmp = new NbCmp();

// Indica si el componentes esta activo o no.
cmp.isActive();

// Activa el componente
cmp.activate();

// Activa el componente
cmp.desactivate();
</code></pre>



<h2 id="valor">Valor</h2>
<p>
  El valor representa la magnitud de la entrada o salida del componente si este es aplicable. En el caso de las entradas representa el valor leído, mientras que en las salidas representa el valor a escribir.
</p>
<p>
  El valor puede ser manejado por los siguiente métodos:
</p>
<pre><code class="language-java">
// Instancia del componente
NbCmp cmp = new NbCmp();

// Devuelve el valor actual del componente.
cmp.getValue();

// Asigna un valor al componente.
cmp.setValue(10);

// Devuelve el valor actual del componente proyectado en una escala de 0 a 255.
cmp.getValue(0,255,0,1023);
</code></pre>



<h2 id="eventos">Eventos</h2>
<p>
  Los componentes poseen dos eventos: cambio de estado y cambio de valor. Estos pueden ser detectados y manejados mediantes las clases (: docEnlace("NbCmp.OnStateChangeListener") :) y (: docEnlace("NbCmp.OnValueChangeListener") :) correspondientemente como se muestra a continuación:
</p>
<pre><code class="language-java">
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
</code></pre>



<h2 id="componentes-primarios">Componentes primarios</h2>
<p>
  Estos pueden ser clasificados según su tipo de señal: Digital o Analógica; según su función: Entrada o Salida; o según si complejidad: Simples o Compuestos. A continuación se presentan Todos lo componentes ya implementados
</p>



<h3 id="led-digital">LED Digital ((: docEnlace("NbLedDigital") :))</h3>
<p>
  <i>Salida/Digital/Simple</i>. No requiere identificador. Require una salida digital. Tiene dos posibles salidas: 0 (LOW) o 1 (HIGH).
</p>
<pre><code class="language-java">NbLedAnalog led = new NbLedAnalog(5);</code></pre>



<h3 led="led-analogico">LED Analógico ((: docEnlace("NbLedAnalog") :))</h3>
<p>
  <i>Salida/Analógica/Simple</i>. No requiere identificador. Require una salida PWM. Su rango de valor va de 0 a 255.
</p>
<pre><code class="language-java">NbLedAnalog led = new NbLedAnalog(5);</code></pre>



<h3 id="boton">Botón ((: docEnlace("NbButton") :))</h3>
<p>
  <i>Entrada/Digital/Simple</i>. No requiere identificador. Require una entrada digital. Su posibles valores son 0 (LOW) o 1 (HIGH).
</p>
<pre><code class="language-java">NbButton button = new NbButton(4);</code></pre>



<h3 id="switch">Interruptor ((: docEnlace("NbSwitch") :))</h3>
<p>
  <i>Entrada/Digital/Simple</i>. No requiere identificador. Require una entrada digital. Su posibles valores son 0 (LOW) o 1 (HIGH).
</p>
<pre><code class="language-java">NbSwitch switch = new NbSwitch(4);</code></pre>



<h3 id="potenciometro">Potenciómetro ((: docEnlace("NbTrimmer") :))</h3>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre><code class="language-java">NbTrimmer trimmer = new NbTrimmer(4);</code></pre>



<h3 id="cny70">Sensor de línea CNY70 ((: docEnlace("NbCNY70") :))</h3>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre><code class="language-java">NbCNY70 cny = new NbCNY70(4);</code></pre>



<h3 id="ldr">Fotoresistencia o LDR ((: docEnlace("NbLDR") :))</h3>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre><code class="language-java">NbLDR ldr = new NbLDR(4);</code></pre>



<h3 id="lm35">Sensor de temperatura LM35 ((: docEnlace("NbLM35") :))</h3>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre><code class="language-java">
NbLM35 ldr = new NbLM35(1);

// Devuelve la lectura en grados Celcius.
ldr.getValueAtCelcius();
</code></pre>



<h3 id="sharp">Sensor de distancia reflectivo SHARP ((: docEnlace("NbSHARP") :))</h3>
<p>
  <i>Entrada/Analógica/Simple</i>. No requiere identificador. Require una entrada analógica. Su rango de valor es de 0 a 1023 (lectura sin procesar).
</p>
<pre><code class="language-java">
NbSHARP sharp = new NbSHARP(1);

// Devuelve la lectura en CM.
ldr.getValueAtCm();
</code></pre>



<h3 id="step-to-step">Motor Bipolar Paso a Paso ((: docEnlace("NbStepToStep") :))</h3>
<p>
  <i>Salida/Especial/Compuesta</i>. No requiere identificador. Require 4 salidas digitales.
</p>
<pre><code class="language-java">
// Instancia con pines A=1, B=2, C=3 y D=4
NbStepToStep motor = new NbStepToStep(1,2,3,4);

// Mueve el motor 150 pasos con pausas de 40ms.
motor.move(150, 40, 0);

// Mueve el motor 300 pasos con pausas de 20ms en direción contraria.
motor.move(-300, 20);
</code></pre>



<h2 id="motores">Motores</h2>
<p>
  Los motores representan los principales actuadores utilizados en los proyectos de automatización.Estos pueden ser motores de corriente continua (Motores CC) los cuales suelen ser controlados a través de puentes H; o Servomotores que ya disponen de todo lo necesario para ser controlador por salidas PWM. Los motores tratados en este apartado no incluyen los motores paso a paso a que son tratados diferente. Independiente del tipo de motor tratado, siempre dispondrán de una salida PWM para controlar su velocidad. A continuación se presenta como utilizar la meta clase (: docEnlace("NbMotor") :).
</p>
<pre><code class="language-java">
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
</code></pre>

<p>
  En el caso de los motores CC dependiendo de la configuración de conexión el puente H utilizado para su control, estos motores pueden ser unidireccionales, o bidireccionales. 
</p>



<h3 id="motor-one-dir">Motores CC Unidireccionales ((: docEnlace("NbMotorDCOneDir") :))</h3>
<p>
  Requiere una salida PWM para indicar la velocidad y una salida digital para la dirección (aunque solo varía su salida en caso de que el motor este detenido). A continuación de indica un ejemplo de como usarlo:
</p>
<pre><code class="language-java">
NbMotorDCOneDir motor;

// Instanciae un motor con una sola dirección. El puente H utiliza los pines
enable = 12 y in1 = 11
motor = new NbMotorDCOneDir(12, 11);

// Instancia un motor con una sola dirección. El puente H utiliza los pines
enable = 12, in1 = 11 y con una velocidad máxima de 100.
motor = new NbMotorDCOneDir(12, 11, 100);
</code></pre>



<h3 id="motor-two-dir">Motores CC Bidireccionales ((: docEnlace("NbMotorDCTwoDir") :))</h3>
<p>
  Requiere una salida PWM para indicar la velocidad y dos salidas digitales para la dirección. Este tipo de motores pueden girar en dos sentidos. La A continuación de indica un ejemplo de como usarlo:
</p>
<pre><code class="language-java">
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
</code></pre>

<h2 id="componentes-objetos">Componentes Objetos</h2>
<p>
  Los componentes objetos son elementos cuya uso no depende solo de un pin, o requiere de uso de código extra en el Arduino para su uso, como por ejemplo el uso de librerías, o algoritmos especializados. El utilizar estos componentes requieren 
</p>



<h3 id="servomotor">Servomotor ((: docEnlace("NbServo") :))</h3>
<p>
  Los servomotores son implementaciones de los (: docEnlace("NbMotor") :), sin embargo, debido a que requieren de la librería Servo de Arduino para su correcto funcionamiento en Nébula son tratados como Componentes Objetos, por lo que requieren codificación extra en Arduino.
</p>
<h6>Código en Arduino</h6>
<pre><code class="language-java">(:= getCodeFile("arduino-NbServo") :)</code></pre>
<p>
  Por otro lado, para usar los servos en la aplicación Android podemos se realiza similar a como se hace con la clase (: docEnlace("NbMotorDCTwoDir") :), con la salvedad de que, por definición, la velocidad del servo varía entre 0 y 180 teniendo el punto de detención en 90.
</p>
<pre><code class="language-java">
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
</code></pre>



<h3 id="pantalla-lcd">Pantallas LCD ((: docEnlace("NbLiquidCrystal") :))</h3>
<p>
  Representa las pantallas LCD que puede ser manejadas mediante la librería LiquiqCrystal para Arduino. Requiere <a href="(:/:)/personalizacion-en-arduino">Personalización en Arduino</a> ya que es imposible automatizar el proceso de compilado con esta utilidad en Arduino.
</p>
<h6>Código en Arduino</h6>
<pre><code class="language-java">(:= getCodeFile("arduino-NbLiquidCrystal") :)</code></pre>
<p>
  Por otro lado en la aplicación Android se puede utilizad de la siguiente forma:
</p>
<pre><code class="language-java">
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
</code></pre>



<h3 id="hcsr04">Sensor de distancia HC-SR04 ((: docEnlace("NbHCSR04") :))</h3>
<p>
  Los sensores de distancia HC-SR04 permiten determinar la distancia de un objeto de forma indirecta midiendo el tiempo que tarda en regresar las ondas de ultrasonido emitidas por el mismo. Debido a que la lectura de este tiempo depende de funciones especiales de Arduino es tratado como un Componente Objeto.
</p>
<p>Código en Arduino</p>
<pre><code class="language-java">(:= getCodeFile("arduino-NbHCSR04") :)</code></pre>
