(# parent:views/_content.php #)
(# set:title="{$_env->name} | Comunicación" #)
(# set:pagina="documentacion" #)
(# set:paso="comunicacion" #)

<?php 

$dialecto = Am::getConfig("usr/dialecto");

?>

<h1 id="comunicacion">Comunicación</h1>

<h4 id="tipos">Tipos de comunicación</h4>
<p>
  Nébula permite la comunicación entre dispositivos Android y accesorios principalmente por dos métodos: ADK y Bluetooth.
</p>

<h5 id="adk">ADK</h5>
<p>
  ADK es el acrónimo de <?php enlace("Accessory Development Kit") ?>, que es un referencia destinada a empresas manufactureras y para la fabricación de hardware. Nébula permite conectarse a hardware haciendo uso del ADK por medio USB conectando el dispositivo móvil al hardware en modo Accessory.
</p>
<h5 id="bluetooth">Bluetooth</h5>
<p>
  Nébula facilita conexiones entre dispositivos Android y hardware compatible con Arduino que pueda comunicarse vía Bluetooth. Esto puede ser mediante alguna placa con un puerto USB Host a la que se le pueda conextar un llavero Bluetooth o por medio de los módulos Bluetooth como el <?php enlace("HC-05") ?> o <?php enlace("BlueSMiRF") ?>.
</p>



<h4 id="uso-en-arduino">Uso en Arduino</h4>

<i><strong>Incluir librerías</strong></i>
<p>
  La librería Nebúla debe ser incluía despues de incluír los elementos de comunicación utilizados de otras librerías (en el caso de <?php docEnlace("NbAdk") ?> y <?php docEnlace("NbSPP") ?>). Sin embargo, para implementar una comunicación por puertos UART se debe incluir explicitamente la librería correspondiente:
</p>
<div class="row">
  <div class="col m4">
    <div class="center"><i><small>Para comunicación Android ADK.</small></i></div>
    <pre><?php echo getCodeFile("sketch-adk-include") ?></pre>
  </div>
  <div class="col m4">
    <div class="center"><i><small>Para comunicación SPP.</small></i></div>
    <pre><?php echo getCodeFile("sketch-spp-include") ?></pre>
  </div>
  <div class="col m4">
    <div class="center"><i><small>Para comunicación puerto UART.</small></i></div>
    <pre><?php echo getCodeFile("sketch-serial-include") ?></pre>
  </div>
</div>



<i><strong>Instanciando objetos</strong></i>
<p>
  En cada caso es necesario la instanciación de ciertos objetos necesarios para realizar la conexión.
</p>

<div class="row">
  <div class="col m4">
    <div class="center"><i><small>Para comunicación Android ADK.</small></i></div>
    <p>
      La comunicación por ADK requiere los objetos <code>Usb Usb</code> (manejo del puerto USB anfitrión) y <code>ADK adk</code>(asbtracación del protocolo de comunicación ADK). Por último se instancia el objeto <code>NbAdk com</code> que representa la abstracción de la comunicación con la estructura de mensajes de Nébula por ADK.
    </p>
    <pre><?php echo getCodeFile("sketch-adk-objetos") ?></pre>
  </div>
  <div class="col m4">
    <div class="center"><i><small>Para comunicación SPP.</small></i></div>
    <p>
      En la comunicación por SPP se requiere el objeto <code>Usb Usb</code> (manejo del puerto USB anfitrión), un objeto <code>BTD Btd</code> (manejo del Bluetooth) y <code>SPP SerialBt</code> (asbtracción de la comunicación Serial vía Bluetooth). Por último se requiere el obejto <code>NbSPP com</code> que representa la abstracción de la comunicación con la estructura de mensajes de Nébula por SPP.
    </p>
    <pre><?php echo getCodeFile("sketch-spp-objetos") ?></pre>
  </div>
  <div class="col m4">
    <div class="center"><i><small>Para comunicación puerto UART.</small></i></div>
    <p>
      Para implementar una comunicación mendiante un puerto UART de la placa Arduino en uso se utiliza del objeto <code>NbSerial com</code>. Esta clase recibe el objeto HardwareSerial que utilizará para la comunicación.
    </p>
    <pre><?php echo getCodeFile("sketch-serial-objetos") ?></pre>
  </div>
</div>
<i><strong>Funciones <code>setup</code> y <code>loop</code></strong></i>
<p>
  Para una implementación sencilla de la comunicación Nébula las funciones loop y setup varía solo en el caso del uso de <?php docEnlace("NbAdk") ?> y <?php docEnlace("NbSPP") ?>, las cuales requieren la la existencia del puerto USB Anfitrion y la llamada <span style="color:red">task</span>.
</p>
<p>
  Sea cual sea el caso para la implentación de la comunicación mediante Nébula sencilla solo hace falta llamar el método <code>task</code> del objeto <code>com</code>.
</p>
<div class="row">
  <div class="col m8">
    <div class="center"><i><small>Para comunicación ADK y SPP.</small></i></div>
    <pre><?php echo getCodeFile("sketch-simple") ?></pre>
  </div>
  <div class="col m4">
    <div class="center"><i><small>Para comunicación UART.</small></i></div>
    <pre><?php echo getCodeFile("sketch-serial-setup-loop") ?></pre>
  </div>
</div>

<i><strong>Descargar de GitHub</strong></i>
<p>
  La librería Nébula para Android puede ser encontrada en GitHub: <?php enlace("Nébula para Arduino", "Nb") ?>
</p>

<h4 id="uso-en-android">Uso en Android</h4>

<i><strong>Estableciendo conexión</strong></i>
<p>
  Para establecer la conexión hace falta un objeto de algunas de las clases especializadas de <?php docEnlace("NbCom") ?>: <?php docEnlace("NbAdk") ?> o <?php docEnlace("NbBt") ?>. Cada una requiere de trato diferente.
</p>
<p>
  En una implementación normal de una comunicación solo requiere la instanciación de la clase <?php docEnlace("NbAdk") ?>, el llamado de su método <?php docEnlace("NbAdk.connect") ?> para establecer conexión con el hardware conectado y el llamado <?php docEnlace("NbAdk.disconnect") ?> para cuando se quiere terminala:
</p>
<pre>
// Instanciar objeto
NdAdk com = new NbAdk(context);

// Establecer conexión
com.connect();

// Desconectar del hardware
com.disconnect();
</pre>

<p>
  Por otro lado, para establecer conexión vía Bluetooth se utiliza la clase <?php docEnlace("NbBt") ?>, cuyo uso es idéntico con la diferencia que al momento de conectarse se debe indicar la dirección del dispositivo al que se conectará.
</p>
<pre>
// Instancia objeto
NbBt com = new NbBt(context);

// Conectar a dispositivo. addresBtDevice: representa un string
// con la dirección del dispositivo al que se conectará.
com.connect(addresBtDevice);

// Para culminar conexión
com.disconnect();

</pre>

<h4 id="estado-de-la-comunicacion">Estado de la comunicación</h4>

<div class="row">

  <div class="col m7">

    <p>
      Las conexión solo pueden tener tres (3) posibles estados definidos en la librería Android en la enumeración <?php docEnlace("NbComMsgEnum") ?>:
    </p>

    <table class="striped proceso-com">
      <thead>
        <tr><th>Estado</th><th>Descripción</th></tr>
      </thead>
      <tbody>
        <tr><td>STATE_DISCONNECTED</td><td>Conexión no establecida</td></tr>
        <tr><td>STATE_CONNECTING</td><td>Intentando establecer conexión</td></tr>
        <tr><td>STATE_CONNECTED</td><td>Conexión establacida</td></tr>
      </tbody>
    </table>
  </div>

  <div class="col m5 center">
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/diagrama-estados-conexion.png" alt="Diagrama de estados de las conexiones">
    <span><i><small>Diagrama de estados de las conexiones</small></i></span>
    <br>
  </div>

</div>

<h4 id="eventos-de-comunicacion">Eventos de comunicación</h4>

<p>
  Durante la comunicación se generan diferentes eventos que pueden ser manejados a conveniencia desde la aplicación librería Android. Estos eventos son estan definidos en la enumeración <?php docEnlace("NbComMsgEnum") ?> y pueden ser manejados mediante el manejador de mensajes <?php docEnlace("NbComHandler") ?>. A continuación se explican los diferentes eventos genéricos de la comunicación:
</p>

<table class="striped proceso-com">
  <thead>
    <tr>
      <th>Estado</th>
      <th>Método que atiende</th>
      <th>Descripción</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>STATE_CHANGED</code></td><td><code>stateChanged</code></td>
      <td>Evento generado cuando ocurre un cambio de estado de la conexión.</td>
    </tr>
    <tr>
      <td><code>CONNECTING</td><td><code>connecting</code></td>
      <td>Evento generado cuando la aplicación inica el intento de conectarse al accesorio.</td>
    </tr>
    <tr>
      <td><code>CONNECTED</code></td><td><code>connecting</code></td>
      <td>Evento generado cuando se establece conexión satisfactoriamente entre la aplicación y al accesorio.</td>
    </tr>
    <tr>
      <td><code>INIT_CONNECTION</code></td><td><code>initConnection</code></td>
      <td>Señala la primera interación de la comunicación.</td>
    </tr>
    <tr>
      <td><code>DATA_WRITED</code></td><td><code>dataWrite</code></td>
      <td>Evento generado cuando se envian datos de la aplicación al accesorio.</td>
    </tr>
    <tr>
      <td><code>DATA_RECEIVED</code></td><td><code>dataWrite</code></td>
      <td>Evento generado cuando se reciben datos del accesorio en la aplicación.</td>
    </tr>
    <tr>
      <td><code>DISCONNECT</code></td><td><code>disconnect</code></td>
      <td>Evento generado cuando se desconectan las partes.</td>
    </tr>
    <tr>
      <td><code>CONNECTION_FAILED</code></td><td><code>connectionFailed</code></td>
      <td>Evento generado cuando falla el intento de conexión.</td>
    </tr>
    <tr>
      <td><code>CONNECTION_LOST</code></td><td><code>connectionLost</code></td>
      <td>Evento generado cuando se pierde la conexión establecida.</td>
    </tr>
    <tr>
      <td><code>ERROR</code></td><td><code>error</code></td>
      <td>EVento generado cuando se genera un error en cualquier momento de la comunicación.</td>
    </tr>
  </tbody>
</table>

<p>
  Se puede utilizar el método <?php docEnlace("NbCom.addHandler") ?> de la clase <?php docEnlace("NbCom") ?> para agregar un manejador de eventos a una conexión:
</p>
<pre><?php echo getCodeFile("android-uso-nb-com-handler") ?></pre>

<h5 id="eventos-de-comunicacio-bluetooth">Eventos de comunicación Bluetooth</h5>
<p>
  Adicionalmente, la comunicación Bluetooth dispone de ciertos eventos especiales, uno para apoyar las rutinas de comunicación, el resto para ser utilizados en las acciones de listado de dispositivos. Estos eventos tambien están definidos en la enumeración <?php docEnlace("NbComMsgEnum") ?>, sin embargo pueden ser atendidos por la clase <?php docEnlace("NbBtHandler") ?> que es una especialización de <?php docEnlace("NbComHandler") ?>. A continuación de listan los eventos espcíficos para conexiones Bluetooth manejados:
</p>
<table class="striped proceso-com">
  <thead>
    <tr>
      <th>Estado</th>
      <th>Método que atiende</th>
      <th>Descripción</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><code>BT_FIRST_MESSAGE</code></td><td><code>firstMessage</code></td>
      <td>Para poder sincronizar satisfactoriamente la aplicación con el accesorio es necesario que el accesorio sea el primero en enviar información.</td>
    </tr>
    <tr>
      <td><code>BT_ANY_PAIRED_DEVICE</code></td><td><code>anyPairedDevices</code></td>
      <td>Evento que indica que existe al menos un dispositivo pareado al dispositivo Android.</td>
    </tr>
    <tr>
      <td><code>BT_NO_PAIRED_DEVICES</code></td><td><code>noPairedDevices</code></td>
      <td>Evento que indic aque no existe algún dispositivo pareado al dispositivo Android.</td>
    </tr>
    <tr>
      <td><code>BT_DO_DISCOVERY_START</code></td><td><code>doDiscoveryStart</code></td>
      <td>Evento generado cuando inicia el escaneo de dispositivos Bluetooth.</td>
    </tr>
    <tr>
      <td><code>BT_DO_DISCOVERY_FINISH</code></td><td><code>doDiscoveryFinished</code></td>
      <td>Evento generado cuando culmina el escaneo de dispositivos Bluetooth.</td>
    </tr>
    <tr>
      <td><code>BT_NO_NEW_DEVICES_FOUND</code></td><td><code>noNewDevicesFound</code></td>
      <td>Evento generado cuando no se encuentra algún dispositivo durante el escaneo.</td>
    </tr>
    <tr>
      <td><code>BT_ADD_PAIRED_DEVICE</code></td><td><code>addPairedDevices</code></td>
      <td>Evento que se genera por cada dispositivo pareado.</td>
    </tr>
    <tr>
      <td><code>BT_ADD_NEW_DEVICE</code></td><td><code>addNewDevices</code></td>
      <td>Evento que se genera por cada dispositivo encontrado en el escaneo.</td>
    </tr>
    <tr>
      <td><code>BT_SELECTED_DEVICE</code></td><td><code>selectedDevice</code></td>
      <td>Evento que se genera cuando se selecciona un dispositivo.</td>
    </tr>
  </tbody>
</table>

<p>
  Al igual de con los eventos genéricos, se puede utilizar el método <?php docEnlace("NbCom.addHandler") ?> de la clase <?php docEnlace("NbCom") ?> para agregar un manejador de eventos de Bluetooth a una conexión Bluetooth:
</p>
<pre><?php echo getCodeFile("android-uso-nb-bt-handler") ?></pre>

<h4 id="errores-en-la-comunicacion">Errores en la comunicación</h4>

<p>
  En las rutinas de conexión se pueden generar ciertos conexiones, que aunque no es importante detectarlos, pueden llegar a ser de interés del programador diferenciarlos en proyectos avanzados. Estos errores estan definidos en la enumeración <?php docEnlace("NbComErrorEnumEstos") ?>, y pueden ser detectados en el método <?php docEnlace("NbComHandler.error") ?> de los manejadores <?php docEnlace("NbComHandler") ?> implementados. A continuación se presentan los diferentes errores que se pueden presentar.
</p>

<table class="striped proceso-com">
  <thead>
    <tr><th>Error</th><th>Tipo de conexión</th><th>Descripción</th></tr>
  </thead>
  <tbody>
    <tr>
      <td><code>CANT_CLOSE_INPUT_STREAM</code></td><td>Todas</td>
      <td>No se pudo cerrar el flujo de entrada.</td>
    </tr>
    <tr>
      <td><code>CANT_CLOSE_OUTPUT_STREAM</code></td><td>Todas</td>
      <td>No se pudo cerrar el flujo de salida.</td>
    </tr>
    <tr>
      <td><code>CANT_CREATE_SOCKET</code></td><td>NbBt</td>
      <td>No se pudo crear el socket de conexión con el dispositovo Bluetooth.</td>
    </tr>
    <tr>
      <td><code>CANT_CREATE_STREAM</code></td><td>NbBt</td>
      <td>No se pudo crear los Stream del desde el socket de conexión con el dispositivo Bluetooth.</td>
    </tr>
    <tr>
      <td><code>CANT_CLOSE_SOCKET</code></td><td>NbBt</td>
      <td>No se pudo cerrar el socket de conexión con el dispositovo Bluetooth.</td>
    </tr>
    <tr>
      <td><code>CANT_GET_PARCEL_FILE_DESCRIPTOR</code></td><td>NbAdk</td>
      <td>No se pudo obtener el descriptor ni los Stream de entrada y salida de archivo del accesorio al que se intenta conectar.</td>
    </tr>
    <tr>
      <td><code>CANT_CLOSE_PARCEL_FILE_DESCRIPTOR</code></td><td>NbAdk</td>
      <td>No se pudo cerrar el descriptor de archivos del accesorio conectado.</td>
    </tr>
  </tbody>
</table>