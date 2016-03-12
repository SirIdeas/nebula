
(:: parent:views/content.php :)
(:: set:title="Estructura general" :)
(:: set:pagina="documentacion" :)
(:: set:paso="estructura" :)


<h1 id="estructura-general">Estructura general</h1>
<p>
  Nébula esta conformado por librerías o bibliotecas de software para reutilizar. Una librería esta destinada a la creación del código Arduino que se ejecuta en el hardware con el que interactúa la Aplicación, mientras que la otra parte esta conformada por un proyecto librería para aplicaciones Android desarrolladas en ADT.
</p>


<h4 id="libreria-arduino">Librería Arduino: <code>Nb</code></h4>
<p>
  La librería Arduino para Nébula consta de una clase principal llamada <?php docEnlace("Nb") ?> que representa la abstracción principal para la comunicación con dispositivos Android. De esta clases se especializan las clases <?php docEnlace("NbAdk") ?> para comunicación por ADK, <?php docEnlace("NbSPP") ?> para comunicación Serial por Bluetooth y <?php docEnlace("NbSerial") ?> para comunicación por los puerto UART.
</p>
<p>
  Las clases <?php docEnlace("NbAdk") ?> y <?php docEnlace("NbSPP") ?> pueden usarse en placas dispongan puertos USB anfitrión compatibles con la librería <?php enlace("USB_Host_Shield_2.0") ?> como por ejemplo: las placas de Arduino compatibles con las <?php enlace("shield_usb_host") ?>, <?php enlace("Arduino Mega ADK") ?>, <?php enlace("Seediuno") ?>, <?php enlace("Freeduino") ?>, entre otros.
</p>
<p>
  Por otro lado, la clase <?php docEnlace("NbSerial") ?> está destinada a usarse con placas que se comuniquen con el dispositivo Android por medio de los puertos UART, sin importar si la conexión se realiza de forma alámbrica (conexión directa mediante un <?php enlace("cable usb OTG") ?>) o inalámbria (con módulos como <?php enlace("HC-05") ?> o <?php enlace("BlueSMiRF") ?>).
</p>

<h4 id="libreria-android">Librería Android: <code>NbLibAndroid</code></h4>
<p>
  La librería de Nébula para Android contine muchas mas utilidades, debido a que el objetivo de Nébula es controlar el hardware desde la aplicación en Android utilizando la placa de Arduino como <i>esclavo</i>.
</p>
<p>
  La estructura general de la Librería Nébula para Android es la siguiente:
</p>

<div class="row">
  <div class="col-md-6">
    <ul>
      <li>
        <p>
          <strong>Comunicación:</strong> Posee todo relacionado a establecer la conexión el hardware separados en los dos método sopoados: Bluetooth y ADK. De igual forma, posee clases que apoyan esta tarea, como lo son los manejadores de eventos de conexión, algunos helpers, constantes de estados y errores entre otros.
        </p>
      </li>
      <li>
        <p>
          <strong>Sketch:</strong> Son el conjunto de clases que poseen todo el comportamiento que procesa los mensajes intercambiados entre la placa de Arduino y la aplicación Android (Dialect). Además posee las clases que representan los componentes físicos comúnmente usados en el hardware separados en dos grupos: Entradas (Interruptores, finales de carreras y sensores entre otros) y Salidas (Led, Motores DC y Motores PaP entre otros).
        </p>
      </li>
      <li>
        <p>
          <strong>Utilidades:</strong> Conjunto de clases que sirven a apoyo para la implementacion de la comunicación de Nébula. Básicamente tiene tres clases: <?php docEnlace("NbTrace") ?>, <?php docEnlace("NbBytes") ?> y <?php docEnlace("NbBuffer") ?>.
        </p>
      </li>
    </ul>
  </div>
  <div class="col-md-6">
    <div class="center"><i><small>Estructura principal Librería Android</small></i></div>
    (:: place:pages/documentacion/NbLibAndroid-blocks.html :)
  </div>
</div>