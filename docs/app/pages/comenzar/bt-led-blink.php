(:: parent:views/content.php :)
(:: set:title="BT Led Blink" :)
(:: set:pagina="comenzar" :)
(:: set:paso="bt-led-blink" :)

<h1>Ejemplo 2 - BT Led Blink</h1>
<p>
  Para el presente ejemplo se describirá una aplicación Android que enciende y apaga un LED controlado a un Arduino por medio de comunicación Bluetooth. Para esto utilizaremos un Arduino Mega ADK, un llavero Bluetooth, teléfono Android (en este caso un Nexus 4).
</p>
<div class="nota card yellow">
  <p class="card-content">
    <i>Nota</i>: Para un mayor entendimiento del ejercicio se recomienda leer sobre la placa <?php enlace("Arduino Mega ADK") ?>.
  </p>
</div>

<h4>Sketch de Arduino</h4>
<p>
  Se debe cargar en la placa de Arduino un sketch que pueda comunicarse con el dispositivo Android vía Bluetooth. Para esto, en el Arduino IDE se debe abrir el sketch ejemplo de Nébula para SPP (<i>File->Examples->Nb->spp</i>) y posteriormente cargarlo en la placa a usar.
</p>
<p>
  Este sketch posee el código mínimo para la comunicación Serial Bluetooth con el dispositivo Android. Este se explica a continuación:
</p>


<div class="row">
  <div class="col-md-7">
    <i><strong>Incluir librerías</strong></i>
    <p>
      El primer paso es incluir la librería a utilizar de <?php enlace("USB_Host_Shield_2.0") ?>. En este caso se utilizará la librería <code>SPP</code> que permite la comunicación Serial Bluetooth.
    </p>
    <p>
      El siguiente paso es incluir la librería de <code>Nb.h</code>, La cual verificará que tipo de comunicación se utilizará para entonces incluir lo correspondiente.
    </p>
  </div>
  <div class="col-md-5">
    <pre><?php echo getCodeFile("sketch-spp-include") ?></pre>
  </div>
</div>

<div class="row">
  <div class="col-md-7">
    <i><strong>Instanciar clases</strong></i>
    <p>
      Se crean las instancias de las clases a utilizar. <code>USB Usb</code>, <code>BTD Btd</code> y <code>SPP SerialBt</code> son las instancias requeridas por librería <?php enlace("USB_Host_Shield_2.0") ?> y utilizadas por el objeto <code>NbSPP com</code> para la comunicación SPP.
    </p>
  </div>
  <div class="col-md-5">
    <pre><?php echo getCodeFile("sketch-spp-objetos") ?></pre>
  </div>
</div>

<div class="row">
  <div class="col-md-7">
    <i><strong>Configuración inicial</strong></i>
    <p>
      En la función <code>setup</code> de Arduino se coloca la inicialización del objeto <code>Usb</code> la cual es requerida según la documentación de <?php enlace("USB_Host_Shield_2.0") ?>.
    </p>
  </div>
  <div class="col-md-5">
    <pre><?php echo getCodeFile("sketch-simple-setup") ?></pre>
  </div>
</div>


<div class="row">
  <div class="col-md-7">
    <i><strong>Tareas</strong></i>
    <p>
      Las tareas a ejecutar consisten en llamar el método <code>Task</code> del objeto <code>Usb</code> (requerida según la documentación de <?php enlace("USB_Host_Shield_2.0") ?>) y el método <code>task</code> del objeto <code>com</code>. Este último es el encargado de interpretar y enviar los mensajes por defecto entre el accesorio y el dispositivos Android.
    </p>
  </div>
  <div class="col-md-5">
    <pre><?php echo getCodeFile("sketch-simple-loop") ?></pre>
  </div>
</div>

<div class="nota card yellow">
  <p class="card-content">
    <i>Nota</i>: Es importante resaltar que la librería <code>Nb.h</code> siempre debe incluirse después de dependencias de la librería <?php enlace("USB_Host_Shield_2.0") ?>, debido a que la primera verifica los tipos de comunicación incluidos.
  </p>
</div>

<h4>Proyecto Android</h4>
<p>
  Ahora en el workspace de Eclipse donde se tenga abierto el proyecto librería de Nébula se debe crear un proyecto Android (<i>File->New->Android Application Proyect</i>) con una actividad vacía. Se debe utilizar como mínimo la API 16: Android 4.1 (Jelly Bean).
</p>
<p>
  Asímismo, se debe agregar la librería de Nébula al proyecto creado. Esto podemos hacerlo desde las propiedades del proyecto (<i>Project->Properties</i>) en la pestaña <i>Android</i>.
</p>



<i><strong>Permisos necesarios</strong></i>
<p>
  La aplicación Android requiere los permisos para encender y utilizar el Bluetooth. Para esto se deberá agregar los mismos en el manifest (<code>AndroidManifest.xml</code>) del proyecto:
</p>
<pre><?php echo getCodeFile("manifest-bt-permissions") ?></pre>



<div class="row">
  <div class="col-md-8">
    <i><strong>Selección de dispositivo</strong></i>
    <p>
      Debido a que la aplicación se comunicará por Bluetooth con el accesorio, se deberá contar con una actividad con la cual realizar el escaneo de dispositivos Bluetooth y seleccionar el correspondiente para establecer conexión.
    </p>
    <p>
      Para esto se deberá crear una actividad nueva. Esto se puede hacer desde el menú contextual del proyecto <code>New</code> (<i>New->Other...</i>) en la opción <i>Android/Android Activity</i>.
    </p>
    <p>
      Esta actividad solo debe extender del helper <?php docEnlace("NbBtDeviceListActivityHelper") ?> Este helper es una clase que extiende de <code>Activity</code> y ofrece toda la funcionalidad básica para escanear, listar y seleccionar dispositivos Bluetooth. Para el caso presente la actividad es llamada <code>BtDevicesListActivity</code>.
    </p>
    <pre><?php echo getCodeFile("android-bt-simple-list-acivity-class") ?></pre>

    <p>
      Por cuestiones de aspectos sería adecuado utilizar un tema de tipo diálogo para esta actividad. Esto lo podemos lograr asignar el parámetro <code>android:theme</code> de la configuración de la actividad en el archivo manifest un estilo adecuado como <code>@android:style/Theme.Dialog</code> o alguno parecido:
    </p>
    <pre><?php echo getCodeFile("android-bt-simple-list-acivity-manifest") ?></pre>

  </div>

  <div class="col-md-4 center">
    <span><i><small>BtDevicesListActivity</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/NbBtLedBlink.BtDevicesListActivity.png" alt="Arduino IDE">
    <br>
  </div>

</div>

<i><strong>Actividad principal</strong></i>
<p>
  Debe existir una actividad que permita seleccionar el accesorio, conectarse y manejarlo. En el presente ejemplo se utilizará la actividad creada junto al proyecto: <code>MainActivity</code>. Esta hereda del helper <?php docEnlace("NbBtMainActivityHelper") ?> ofrecido por la libería de Nébula. Esta clase contiene todo lo necesario para conectarse y desconectarse a un accesorio por comunicación Bluetooth mediante un menú sencillo.
</p>
<p>
  El layout de esta actividad solo consta de un <code>ToggleButton</code> que encenderá y apagará el LED:
</p>

<div class="row">
  <div class="col-md-8">
    <pre><?php echo getCodeFile("led-blink-main-activity-layout") ?></pre>

  </div>

  <div class="col-md-4 center">
    <span><i><small>MainActivity</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/NbBtLedBlink.MainActivity.png" alt="Arduino IDE">
  </div>

</div>

<p>
  Se debe indicar la actividad a utilizar para listar y seleccionar el accesorio Bluetooth. Esta asignación se logra mediante el método <?php docEnlace("NbBtMainActivityHelper.setBtDeviceListActivityClass") ?> de la clase <?php docEnlace("NbBtMainActivityHelper") ?>:
</p>
<pre>setBtDeviceListActivityClass(BtDevicesListActivity.class);</pre>

<p>
  En el código de la clase <code>MainActivity</code> se posee un atributo privado con una instancia de la clase <?php docEnlace("NbLedDigital") ?> que representa la abstracción del LED físico a manejar. Se utilizará el LED del pin 13 del Arduino.
</p>
<pre>private NbLedDigital led = new NbLedDigital(13);</pre>

<p>
  Nébula posee una clase <?php docEnlace("NbSketch") ?> que representa todos los componentes conectados. La clase <?php docEnlace("NbAdkMainActivityHelper") ?> posee una instancia de este objeto para conectar los componentes a manejar. Esta instancia puede ser obtenida mediante el método <?php docEnlace("NbAdkMainActivityHelper.getSketch") ?> para conectar el objeto <code>NbLedDigital led</code> al sketch.
</p>
<pre>getSketch().connect(led);</pre>

<p>
  Por último solo se debe cambiar el valor del LED cada vez que cambie el estado del <code>ToogleButton</code> de la actividad:
</p>
<pre>
((ToggleButton)findViewById(R.id.toggleButton1)).setOnCheckedChangeListener(new OnCheckedChangeListener(){

  @Override
  public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
    led.setValue(isChecked);
  }

});
</pre>

<p>
  El código de la clase <code>MainActivity</code> deberá resultar algo parecido a lo siguiente:
</p>
<pre><?php echo getCodeFile("bt-led-blink-main-activity-class") ?></pre>



<h4>Resultados</h4>
<p>
  Para probar la aplicación la ejecutamos en el dispositivo Android que estemos usando. Al iniciar la aplicación esta se mostrará la actividad <code>MainActivity</code> la cual consta de un <code>ToggleButton</code> destinado a apagar y encender el LED 13 del Arduino. El título de esta actividad indica el estado de conexión.
</p>
<div class="row">
  <div class="col-md-8">
    <p>
      Al hacer tap en el menú <i>Conectar</i> se abrirá la aplicación verifica si el Bluetooth principal del dispositivo está encendido. De no estar encendido mostrará un diálogo para confirmar si desea encender el Bluetooth. Una vez encendido se mostrará la actividad <code>BtDevicesListActivity</code> la cual listará los dispositivos bluetooth pareados y una opción para escanear. Al hacer tap en <i>Escanear</i> se buscarán los dispositivos alrededor. Al aparecer el accesorio y ser seleccionado la aplicación intentará establecer conexión. En el caso actual el accesorio fue nombrado <code>NebulaBoard</code> en el skecth grabado en la placa <?php enlace("Arduino Mega ADK") ?>).
    </p>
  </div>
  <div class="col-md-4 center">
    <span><i><small>Solicitud de permiso para encender el Bluetooth</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/NbBtLedBlink.msg-turnon-bluetooth.png" alt="Arduino IDE">
  </div>
</div>
<p>
  Al establecer conexión el título de la actividad principal indicará que se estableción conexión, el menú <i>Conectar</i> cambiará <i>Desconectar</i> y el <code>ToogleButton</code> podrá encender y apagar el LED 13 del Arduino.
</p>
<p>
  Puede descargar el proyecto Android para Eclipse desde el siguiente enlace: <a href="" style="color:red">Descargar NbLedBlinkBt</a>
</p>
