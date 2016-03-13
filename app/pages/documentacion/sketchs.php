(:: parent:views/content.php :)
(:: set:title="Sketchs" :)
(:: set:pagina="documentacion" :)
(:: set:paso="sketchs" :)

<?php  $dialecto = Am::getProperty("dialecto"); ?>

<h1 id="sketch">Sketch</h1>
<p>
  Las clases de comunicación permiten manejar los mensajes a conveniencia, sin embargo el apartado de skecth posee todo lo necesario para que las partes se entiendan sin necesidad de implementar un dialecto diferente. Esto se realiza mediante la clase <?php docEnlace("NbSketch") ?> y los componentes especializados de la clase <?php docEnlace("NbCmp") ?> como por ejemplo: <?php docEnlace("NbLedDigital") ?>, <?php docEnlace("NbLedAnalog") ?>, <?php docEnlace("NbButton") ?>, <?php docEnlace("NbInterruptor") ?>, <?php docEnlace("NbMotorDC") ?> y <?php docEnlace("NbLM35") ?>, entre otros.
</p>
<p>
  La clase <?php docEnlace("NbSketch") ?> representa un conjunto de componentes electrónicos de entrada y salidas que serán manejados por la aplicación, mientras que las clases que heredan <?php docEnlace("NbCmp") ?> representan las los componentes en si.
</p>
<p>
  Pueden existir diferentes tipos de componentes: entradas y salidas, analógicos y digitales, simples y compuestos. 
</p>

<h2 id="proceso-de-comunicacion">Proceso de comunicación</h2>
<div class="row">
  <div class="col-sm-6">
    <p>
      El proceso de comunicación está basado en un algoritmo de sincronización activa y bidireccional, donde cada parte lee los datos de entrada, interpreta las acciones y responde; luego entra en un estado de espera para la siguiente entrada.
    </p>
    <p>
      Por lo general, la comunicación es establecida desde el dispositivo móvil o desde la aplicación. En el momento en el que el hardware externo detecta la conexión envía un primer mensaje de verificación y cae en espera de mensajes de entrada. Luego, la aplicación envía los bytes de configuración al hardware externo y cae en espera. El hardware externo interpreta la configuración recibida y envía los bytes pertinentes. Por último, comienza un ciclo infinito en el que la aplicación y hardware externo envía bytes de información entre si, siempre esperando la respuesta de su contraparte. Todo el proceso de comunicación termina en el momento que se rompa la conexión entre el hardware externo y el dispositivo inteligente.
    </p>
  </div>
  <div class="col-sm-6 text-center">
    <table class="table">
      <thead>
        <tr><th>Paso</th><th>Aplicación Android</th><th>Hardware Arduino</th></tr>
      </thead>
      <tbody>
        <tr><th class="text-center">1</th><td>Iniciar conexión</td><td></td></tr>
        <tr><th class="text-center">2</th><td>Esperar primer mensaje</td><td>Establacer conexión</td></tr>
        <tr><th class="text-center">3</th><td></td><td>Enviar verificación</td></tr>
        <tr><th class="text-center">4</th><td>Recibir verificación</td><td>Esperar tareas</td></tr>
        <tr><th class="text-center">5</th><td>Enviar configuración</td><td></td></tr>
        <tr><th class="text-center">6</th><td>Esperar mensajes</td><td>Leer tareas</td></tr>
        <tr><th class="text-center">7</th><td></td><td>Ejecutar tareas</td></tr>
        <tr><th class="text-center">8</th><td>Recibir mensajes</td><td>Enviar mensajes</td></tr>
        <tr><th class="text-center">9</th><td>Interpretar información</td><td>Ir al #4</td></tr>
        <tr><th class="text-center">10</th><td>Ir al paso #6</td><td></td></tr>
      </tbody>
    </table>
    <span><i><small>Proceso de comunicación</small></i></span>
  </div>
</div>


<h2 id="estructura-de-mensajes">Estructura de mensajes</h2>
<p>
  Los mensajes enviados entre la aplicación Android y el sketch de Arduino consiste en una lista de bytes que representan un grupo de <i>instrucciones</i>, <i>argumentos</i> y <i>datos</i>. Las <i>instrucciones</i> están conformadas en un set de acciones llamado <i>Dialecto</i> definidas que pueden realizar la librería de Nébula en la aplicación Android y/o en el sketch de Arduino, mientras que los <i>argumentos</i> parametrizan las acciones a realizar y por último los <i>datos</i> representan los datos enviados de una parte a la otra.
</p>
<p>
  Cada acción del <i>Dialecto</i> tienen un valor número único que lo diferencia del resto. El dialecto está definido tanto en la parte librería de Android (clase <?php docEnlace("NbDialect") ?>) como en la libería de Arduino (constantes <code>NB_MSG_*</code>).
</p>

<table class="table">
  <thead>
    <tr><th>Valor</th><th>Nombre</th><th>Acción</th></tr>
  </thead>
  <tbody>
    <?php foreach ($dialecto as $valor => $msg): ?>
      <tr>
        <th><code><?php echo $valor ?></code></th>
        <td><code><?php echo $msg["nombre"] ?></code></td>
        <td><?php echo $msg["accion"] ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<h2 id="ejemplo">Ejemplo</h2>
<p>
  Imagine que desea encender y apagar un primer led con un botón y controlar la intensidad de un segundo led con un potenciómetro. Una implementación acertada de una solucíón en Nébula podría ser:
</p>
<pre><code class="language-java">
// com es una instancia de NbBt o NbAdk.
NbCom com = getCom();

// Instancia sketch
NbSketch sketch = new NbSkecth();
sketch.setCom(com); // asignar sketch

// Instanciar los componentes a utilizar
NbLedDigital ledDig = new NbLedDigital(13); // Led digital
NbLedAnalog ledAlg = new NbLedAnalog(12);   // Led analógico
NbButton button = new NbButton(10);         // Boton
NbTrimmer trimmer = new NbTrimmer(1);       // Potenciómetro

button.setOnValueChangeListener(new NbCmp.OnValueChangeListener() {
  @Override
  public void onValueChange(NbCmp component, int newValue, int oldValue) {
    ledDig.toggle();
  }
});

trimmer.setOnValueChangeListener(new NbCmp.OnValueChangeListener() {
  @Override
  public void onValueChange(NbCmp component, int newValue, int oldValue) {
    ledAlg.setValue(component.getValueMap(0,255,0,1023));
  }
});
</code></pre>

<p>
  Los componentes electrónicos que no se frecuentes tambien pueden manejarse desde Nébula, aunque se requiere una codificación especial. Consulte el apartado <a href="" style="color:red">PENDIENTE</a> para mas información.
</p>

<h2 id="eventos-en-los-sketchs">Eventos en los Sketchs</h2>

<p>
  De igual forma que en las comunicaciones, la clase <?php docEnlace("NbSkecth") ?> posee ciertos eventos que pueden ser detectados y manejados a conveniencia. Es se realiza mediante la clases manejadora de eventos <?php docEnlace("NbSketchHandler") ?>. Los posibles eventos están definidos en enumeración <?php docEnlace("NbSketchMessageEnum") ?> y se describen a continuación:
</p>

<table class="table">
  <thead>
    <tr>
      <th>Estado</th>
      <th>Método que atiende</th>
      <th>Descripción</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>CHANGED_DIGITAL_STATE</code></td><td><code>changedDigitalState</code></td>
      <td>Evento generado cuando un pin digital cambia de estado.</td>
    </tr>
    <tr>
      <td><code>CHANGED_ANALOG_STATE</code></td><td><code>changedAnalogState</code></td>
      <td>Evento generado cuando un pin analógico cambia de estado.</td>
    </tr>
    <tr>
      <td><code>CHANGED_OBJECT_STATE</code></td><td><code>changedObjectState</code></td>
      <td>Evento generado cuando un objeto cambia de estado.</td>
    </tr>
    <tr>
      <td><code>CHANGED_DIGITAL_VALUE</code></td><td><code>changedDigitalValue</code></td>
      <td>Evento generado cuando un pin digital cambia de valor.</td>
    </tr>
    <tr>
      <td><code>CHANGED_ANALOG_VALUE</code></td><td><code>changedAnalogValue</code></td>
      <td>Evento generado cuando un pin analógico camba de valor.</td>
    </tr>
    <tr>
      <td><code>CHANGED_OBJECT_VALUE</code></td><td><code>changedObjectValue</code></td>
      <td>Evento generado cuando un objeto camba de valor.</td>
    </tr>
    <tr>
      <td><code>OBJECT_NO_FOUND</code></td><td><code>objectNotFount</code></td>
      <td>Evento generado cuando no se encuentra un objeto.</td>
    </tr>
  </tbody>
</table>

<p>
  Se puede utilizar el método <?php docEnlace("NbSkecth.addHandler") ?> de la clase <?php docEnlace("NbSkecth") ?> para agregar un manejador de eventos:
</p>
<pre><code class="language-java"><?php echo getCodeFile("android-uso-nb-sketch-handler") ?></code></pre>


<h2 id="eventos-en-los-sketchs">Método Loop</h2>
<p>
  Despues de procesar la cola de mensajes enviadas desde el microcontrolador a la aplicación, y antes de enviar la respuesta, se llama el método <?php docEnlace("NbSkecth.loop") ?>. De giual forma dispone de un método <?php docEnlace("NbSkecth.getUserBytes") ?> permite definir un grupo de bytes enviados al final de la cola de mensaje con comandos personalizados. Finalmente el método <?php docEnlace("NbSkecth.addSetupByte") ?> permite agregar bytes al final de la cola de mensaje de configuración. Estos métodos pueden ser reescritos para enviar comandos personalizados como los tratados en el apartado <a href="<?php Am::eUrl() ?>/documentacion/personalizacion-en-arduino">Personalización en Arduino</a>.
</p>
<pre><code class="language-java">

// instanciar 
NbSketch sketch = new NbSketch(){

  public void loop(){
    // Hacer tareas personalizadas
  }

  public NbBytes getUserBytes(){
    NbBytes data = new NbBytes();

    data.add(2);    // Agregar un byte.
    data.addInt(2200); // Agregar un entero.
    data.addLong(18736813); // Agregar un entero largo.

    // Agrega un array de bytes.
    data.addBytes(arrayBytes);

    return data;

  }
}

// Agregar un byte a la cola de mensaje
sketch.addSetupByte(cmd1);
</code></pre>