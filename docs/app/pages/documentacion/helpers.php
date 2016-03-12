(# parent:views/_content.php #)
(# set:title="{$_env->name} | Helpers" #)
(# set:pagina="documentacion" #)
(# set:paso="helpers" #)

<h1 id="helpers">Helpers</h1>
<p>
  La libreía de Nébula para Android ofrece algunas clases con elementos básicos que permiten comenzar rápidamente la condificación de aplicaciones. Existe un helper para conexiones por ADK (NbAdkMainActivityHelper) y dos helpers para Bluetooth un(NbBtMainActivityHelper)
</p>

<h4 id="adk-helper">Actividad ADK (<?php docEnlace("NbAdkMainActivityHelper") ?>)</h4>
<p>
  Es una clase para implementar actividades que utiliza un conexión ADK y un sketch. El estado de esta conexión siempre es visible en la barra de título. Tiene implementadas las opciones de conectar y desconectar en un menú de opciones.
</p>
<p>
  Una forma de implementar una actividad apartir de este helper es:
</p>
<pre>
public class MainActivity extends NbAdkMainActivityHelper {  
  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
  }
}
</pre>

<p>
  Algunos de los métodos útiles de este helper son:
</p>
<pre>
// Devuelve la instancia de la comunicacion actual.
NbAdk adk = getCom();

// Devuelve la instancia del Sketch actual
NbSketch sketch = getSketch();
</pre>

<h4 id="bt-helper">Actividad Bluetooth (<?php docEnlace("NbBtMainActivityHelper") ?>)</h4>
<p>
  Es una clase para implementar actividades que utiliza un conexión Bluetooth y un sketch. El estado de esta conexión siempre es visible en la barra de título. Tiene implementadas las opciones de conectar y desconectar en un menú de opciones.
</p>
<p>
  A diferencia de su contraparte <?php docEnlace("NbAdkMainActivityHelper") ?>, este helpers implementa las funcionalidades básicas para verificar si el dispositivo posee bluetooth, encenderlo y obtener la dirección del dispositivo al que se desea conectar a través de clases
  implementadas con el helper <?php docEnlace("NbBtDeviceListActivityHelper") ?>.
</p>
<p>
  Una forma de implementar una actividad apartir de este helper es:
</p>
<pre>
public class MainActivity extends NbBtMainActivityHelper{
  @Override
  protected void onCreate(Bundle savedInstanceState) {
    super.onCreate(savedInstanceState);
    setContentView(R.layout.activity_main);
    
    // Se debe indicar la clase de la actividad que se utilizará
    // obtener direción del dispositivo bluetooth al que se conectará.
    setBtDeviceListActivityClass(BtDevicesListActivity.class);

  }
}
</pre>
<p>
  Algunos de los métodos útiles de este helper son:
</p>
<pre>
// Devuelve la instancia de la comunicacion actual.
NbBt bt = getCom();

// Devuelve la instancia del Sketch actual
NbSketch sketch = getSketch();

// Verifica si el dispositivo tiene bluetooth.
// Intenta encederlo el bluetooth si esta apagado.
// Intenta obtener la dirección del dispositivo al
// que se desea conectar.
connect();

// Conectarse en forma segura
connect(true);

// Intentar conectarse al dispositivo con la
// dirección indicada.
connectToDevice(direccion);

// Asignar si se conectará de forma segura o insegura
setSecure(true);
</pre>
<p>
  La conexión Bluetooth hace un UUID por defecto. Se desedea cambiar la utilizada por defecto se puede utilizar las siguientes funciones:
</p>
<pre>
// Cambia UUID para conexion segura
setBtUuidSecure(uuid);

// Cambia UUID para conexion insegura
setBtUuidInsecure(uuid);
</pre>
<p>
  De igual forma, esta actividad hace uso de ciertos Intents con valores personalizados. Puede que el alguna ocasión los identificadores de las peticiones intents <i>choquen</i> con las de otros llamados. Si se desea cambiar los identificadores de las peticiones intent implementadas se puede utilizas los siguientes métodos:
</p>
<pre>
// Cambiar el calor del identificador para peticion
// de habilitar el Bluetooth y conexión segura
setRequestEnabledBtSecure(valor);

// Cambiar el calor del identificador para peticion
// de habilitar el Bluetooth y conexión insegura
setRequestEnabledBtInsecure(valor);

// Cambiar el calor del identificador para peticion
// selecionar dispositivo a conectar y conexión segura
setRequestConnectDeviceSecure(valor);

// Cambiar el calor del identificador para peticion
// selecionar dispositivo a conectar y conexión insegura
setRequestConnectDeviceInsecure(valor);
</pre>

<h4 id="bt-helper-devices">Dispositivos Bluetooth (<?php docEnlace("NbBtDeviceListActivityHelper") ?>)</h4>
<p>
  Este helper tiene como obejtivo final obtener la dirección de un dispositivos bluetooth.
  Para esto, esta clase implementa el listado de dispositivos pareados, realiza el escaneo Bluetooth, lista los dispositivos encontrados en el escaneo.
</p>
<p>
  Para implementar una actividada con este helper, basta solo con heredar una clase.
</p>
<pre>
public class BtDevicesListActivity extends NbBtDeviceListActivityHelper {
}
</pre>