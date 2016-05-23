(: parent:'views/content.php'
(: $title = "ADK Led Blink"
(: $pagina = "comenzar"
(: $paso = "adk-led-blink"

<h1 id="ejemplo1">Ejemplo 1 - ADK Led Blink</h1>
<p>
  En este ejemplo de Nébula se describirá una aplicación Android que enciende y apaga un LED controlado a una placa Arduino por medio de conexión USB en modo Accessory. Para esto utilizaremos un Arduino Mega ADK y teléfono Android (en este caso un Nexus 4).
</p>
<div class="panel panel-info">
  <div class="panel-heading">
    <strong>Nota</strong>: Para un mayor entendimiento del ejercicio se recomienda leer sobre la placa (: enlace("Arduino Mega ADK") :).
  </div>
</div>
<p>
  No se debe confundir un <i>accesorio</i> con un dispositivo Android conectado en <i>modo Accessory</i>. Un <i>accesorio</i> es un hardware externo construído para comunicarse con un dispositivo Android, mientras que un dispositivo Android conectado en <i>modo Accessory</i> se refiere al modo de conexión USB establecida entre el dispositivo Android y el accesorio.
</p>
<div class="panel panel-info">
  <div class="panel-heading">
    <strong>Nota</strong>: Para un mayor endendimiento visitar (: enlace("Accessory Development Kit") :)
  </div>
</div>


<h2 id="sketch-arduino">Sketch de Arduino</h2>
<p>
  Se debe cargar en la placa de Arduino un sketch que interpreta los comandos del dispositivo Android conectado en modo Accessory. Para esto, en el Arduino IDE se debe abrir el sketch ejemplo de Nébula para ADK (<i>File->Examples->nb->adk</i>) y posteriormente cargarlo en la placa a usar.
</p>
<p>
  Este sketch posee el código mínimo para comunicarse el dispositivo Android conectado en modo Accessory. Este se explica a continuación:
</p>


<div class="row">
  <div class="col-md-7">

    <h3>Incluir librerías</h3>
    <p>
      El primer paso es incluir la librería a utilizar de (: enlace("USB_Host_Shield_2.0") :). En este caso se utilizará la librería <code>adk.h</code> que permite la comunicación dispositivos Android conectados en modo Accessory.
    </p>
    <p>
      El siguiente paso es incluir la librería de <code>Nb.h</code>, La cual verificará que tipo de comunicación se utilizará para entonces incluir lo correspondiente.
    </p>

  </div>
  <div class="col-md-5">
    <pre><code class="language-cpp">(:= getCodeFile("sketch-adk-include") :)</code></pre>
  </div>
</div>

<div class="row">
  <div class="col-md-7">

    <h3>Instanciar clases</h3>
    <p>
      Se crean las instancias de los objetos a utilizar. <code>USB Usb</code> y <code>ADK adk</code> son las instancias requeridas por librería (: enlace("USB_Host_Shield_2.0") :) y utilizadas por el objeto <code>NbAdk com</code> para la comunicación con el dispositivo Android conectado en modo Accessory.
    </p>

  </div>
  <div class="col-md-5">
    <pre><code class="language-cpp">(:= getCodeFile("sketch-adk-objetos") :)</code></pre>
  </div>
</div>

<div class="row">
  <div class="col-md-7">

    <h3>Configuración inicial</h3>
    <p>
      En la función <code>setup</code> de Arduino se coloca la inicialización del objeto <code>Usb</code> el cual es requerido según los ejemplos de (: enlace("USB_Host_Shield_2.0") :)
    </p>

  </div>
  <div class="col-md-5">
    <pre><code class="language-cpp">(:= getCodeFile("sketch-simple-setup") :)</code></pre>
  </div>
</div>

<div class="row">
  <div class="col-md-7">

    <h3>Tareas</h3>
    <p>
      Las tareas a ejecutar consisten en llamar el método <code>Task</code> del objeto <code>USB Usb</code> (requerida según los ejemplos de (: enlace("USB_Host_Shield_2.0") :)) y el método <code>task</code> del objeto <code>NbAdk com</code>. Este último es el encargado de interpretar y enviar los mensajes por defecto entre la placa Arduino utilizada y el dispositivos Android.
    </p>

  </div>
  <div class="col-md-5">
    <pre><code class="language-cpp">(:= getCodeFile("sketch-simple-loop") :)</code></pre>
  </div>
</div>

<div class="nota card yellow">
  <p class="card-content">
    <i>Nota</i>: Es importante resaltar que la librería <code>Nb.h</code> siempre debe incluirse después de dependencias de la librería (: enlace("USB_Host_Shield_2.0") :), debido a que la primera verifica los tipos de comunicación incluidos.
  </p>
</div>

<h2 id="proyecto-android">Proyecto Android</h2>
<p>
  Ahora en el workspace de Eclipse donde se tenga abierto el proyecto librería de Nébula se debe crear un proyecto Android (<i>File->New->Android Application Proyect</i>) con una actividad vacía. Se debe utilizar como mínimo la API 16: Android 4.1 (Jelly Bean).
</p>
<p>
  Asímismo, se debe agregar la librería de Nébula al proyecto creado. Esto podemos hacerlo desde las propiedades del proyecto (<i>Project->Properties</i>) en la pestaña <i>Android</i>.
</p>


<h3>Actividad principal</h3>
<p>
  En la actividad principal se establecerá conexión con el accesorio y se controlará el LED. Se utilizará la actividad <code>MainActivity</code> creada junto al proyecto. Esta heredará del helper (: docEnlace("NbAdkMainActivityHelper") :) ofrecido por Nébula. Este helper tiene todo lo necesario para conectarse y desconectarse a un hardware en modo Accessory por medio de opciones de menú.
</p>
<p>
  El layout de esta actividad solo consta de un <code>ToggleButton</code> que encenderá y apagará el LED:
</p>

<div class="row">
  <div class="col-md-8">
    <pre><code class="language-html">(:= getCodeFile("led-blink-main-activity-layout") :)</code></pre>
  </div>

  <div class="col-md-4 text-center">
    <span><i><small>MainActivity</small></i></span>
    <img class="ajustar" src="(:/:)/images/NbAdkLedBlink.MainActivity.png" alt="NbAdkLedBlink.MainActivity">
    <br>
  </div>

</div>

<p>
  En el código de la clase <code>MainActivity</code> se posee un atributo privado con una instancia de la clase (: docEnlace("NbLedDigital") :) que representa la abstracción del LED físico a manejar. Se utilizará el LED del pin 13 del Arduino.
</p>
<pre><code class="language-java">private NbLedDigital led = new NbLedDigital(13);</code></pre>

<p>
  Nébula posee una clase (: docEnlace("NbSketch") :) que representa todos los componentes conectados. La clase (: docEnlace("NbAdkMainActivityHelper") :) posee una instancia de este objeto para conectar los componentes a manejar. Esta instancia puede ser obtenida mediante el método (: docEnlace("NbAdkMainActivityHelper.getSketch") :) para conectar el objeto <code>NbLedDigital led</code> al sketch.
</p>
<pre><code class="language-java">getSketch().connect(led);</code></pre>

<p>
  Por último solo se debe cambiar el valor del LED cada vez que cambie el estado del <code>ToogleButton</code> de la actividad:
</p>
<pre><code class="language-java">
((ToggleButton)findViewById(R.id.toggleButton1)).setOnCheckedChangeListener(new OnCheckedChangeListener(){

  @Override
  public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
    led.setValue(isChecked);
  }

});
</code></pre>

<p>
  El código de la clase <code>MainActivity</code> deberá resultar algo parecido a lo siguiente:
</p>
<pre><code class="language-java">(:= getCodeFile("adk-led-blink-main-activity-class") :)</code></pre>

<h2 id="resultados">Resultados</h2>
<p>
  Para probar la aplicación la ejecutamos en el dispositivo Android que estemos usando. Al iniciar la aplicación esta se mostrará la actividad <code>MainActivity</code> la cual consta de un ToggleButton destinado a apagar y encender el LED 13 del Arduino. El título de esta actividad indica el estado de conexión.
</p>
<div class="row">
  <div class="col-md-8">
    <p>
      El dispositivo Android debe ser conectado al puerto USB Host de la placa Arduino Mega. Al momento de realizar la conexión, el dispositivo Android mostrará un mensaje de advertencia indicando que no se ha encontrado un aplicación compatible con el hardware conectado. <!-- En el apartado <a href="" style="color:red">Pendiente</a> se señala como indicar a la aplicación los hardware compatibles. Por los momentos bastará con cerrar el diálogo de incompatibilidad. -->
    </p>
  </div>
  <div class="col-md-4 text-center">
    <span><i><small>Mensaje de incompatibilidad</small></i></span>
    <img class="ajustar" src="(:/:)/images/NbAdkLedBlink.msg-incompatibilidad.png" alt="Mensaje de incompatibilidad.png">
  </div>
</div>
<div class="row">
  <div class="col-md-8">
    <p>
      Una vez conectado y teniendo la aplicación abierta se debe hacer tap en el menú <i>Conectar</i>. En este momento la aplicación verificará si tiene los permisos necesarios para utilizar el hardware. En el caso de no poseerlos se solicitarán mediante un diálogo correspondiente. Al establecer conexión el título de la actividad principal indicará que se estableció conexión, el menú <i>Conectar</i> cambiará <i>Desconectar</i> y el <code>ToogleButton</code> podrá encender y apagar el LED 13 del Arduino.
    </p>
  </div>
  <div class="col-md-4 text-center">
    <span><i><small>Solicitud de permiso</small></i></span>
    <img class="ajustar" src="(:/:)/images/NbAdkLedBlink.msg-permiso.png" alt="Solicitud de permisos.png">
  </div>
</div>

<p>
  Puede descargar el proyecto Android para Eclipse desde el siguiente página: (: enlace("Descargar NbLedBlinkAdk") :).
</p>
