(# parent:views/_content.php #)
(# set:title="{$_env->name} | Instalación" #)
(# set:pagina="comenzar" #)
(# set:paso="instalacion" #)

<h1 id="instalacion">Instalación</h1>
<h4 id="arduino-ide">Arduino IDE</h4>
<div class="row">
  <div class="col m8">
    <p>
      Aunque Nébula tiene como fin evitar la programación directa de los accesorios, es necesario la carga inicial de un programa mínimo en los microcontroladores. Para esto se debe contar con un entorno de desarrollo para Arduino. Para instalar el entorno de Arduino podemos seguir el siguiente enlace: <?php enlace("Download the Arduino Software") ?>.
    </p>
    <p>
      Nébula hace uso de la librería <?php enlace("USB_Host_Shield_2.0") ?> mantenida por <?php enlace("Kristian Sloth Lauszus") ?> y creada inicialmente por <?php enlace("Oleg Mazurov") ?>. Para descargar directamente la última versión de esta librería puede utilizar el siguiente enlace: <?php enlace("Descargar USB_Host_Shield_2.0 desde GitHub") ?>.
    </p>
    <p>
      De igual forma se debe descargar la librería propia de <?php enlace("Nébula para Arduino") ?>. Para descargar directamente la última versión de esta librería  puede utilizar el siguiente enlace: <?php enlace("Descargar Nb desde GitHub") ?>.
    </p>

    <p>
      Estas librerías deben ser descomprimidas en la directorio <i>libraries/</i> de nuestra carpeta Arduino IDE. 
    </p>

    <div class="nota card yellow">
      <p class="card-content">
        <i>Nota</i>: Los nombres de las carpetas de librerías debe contener solo caracteres alfanuméricos y/o "_", por lo que es posible que se requiera renombrar la carpeta descomprimida de USB_Host_Shield_2.0.
      </p>
    </div>

    <div class="nota card yellow">
      <p class="card-content">
        <i>Nota</i>: Para el fucionamiento de la librería <?php enlace("USB_Host_Shield_2.0") ?> con la placa <?php enlace("Arduino Mega ADK") ?> debe cambiar el valor del macro <code>USE_UHS_MEGA_ADK</code> a 1. <strong>(Línea #42 del archivo <i>&lt;usb_host_shield_library_folder&gt;)/settings.h</i></strong>
      </p>
    </div>
  </div>
  <div class="col m4 center">
    <span><i><small>Arduino IDE</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/arduino-ide.png" alt="Arduino IDE">
    <br>
    <br>
    <span><i><small>Librerías requeridas</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/arduino-libraries.png" alt="Arduino IDE">
  </div>
</div>


<h4 id="eclipse-ide">Eclipse IDE</h4>
<div class="row">
  <div class="col m7">
    <p>
      Nébula es utilizado en el desarrollo de aplicaciones Android, por lo que se deberá disponer de un entorno de desarrollo para el mismo. Para preparar el entorno de desarrollo de Android con Eclipse ADT puede consultar el siguiente enlace: <?php enlace("Installing the Eclipse Plugin") ?>.
    </p>
    <div class="nota card yellow">
      <p class="card-content">
        <i>Nota</i>: Nébula es compatible la API 16: Android 4.1 (Jelly Bean) o posterior. 
      </p>
    </div>
    <p>
      Es necesario descargar la librería de Nébula Android. La misma está disponible para la descargar en el siguiente enlace: <?php enlace("Descargar NbLibAndroid desde GitHub") ?>.
    </p>
    <p>
      Una vez descargada y descomprimida debe abrirse en Eclipse ADT como un proyecto Android desde código existente en la carpeta descomprimida (<i>File->New->Project...->Android Project from Existing Code</i>).
    </p>
  </div>
  <div class="col m5 center">
    <span><i><small>Eclipse IDE</small></i></span>
    <img class="ajustar" src="<?php Am::eUrl() ?>/images/eclipse-ide.png" alt="Eclipse IDE">
  </div>
</div>