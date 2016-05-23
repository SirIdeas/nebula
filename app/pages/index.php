(: parent:'views/tpl.php'
(: $title = 'Inicio'
(: $pagina = 'home'

<div class="home-inner text-center">
  <div class="container">

    <img src="(:/:)/images/nb-m.png" alt="Logo">

    <h1>Proyecto Nébula</h1>
    <h3>
      Librería para comunicación de Aplicaciones Android con placas Arduino
    </h3>
    <div>
      <a href="(:/:)/comenzar" class="btn btn-primary btn-lg" role="button">
        Comenzar
      </a>
    </div>
    <br>

    <div class="row">
      <div class="col-sm-4 col-sm-offset-2">
        <i class="flaticon-android"></i>
        <h5>ADK</h5>
        <p>
          ADK son las siglas de (: enlace("Accessory Development Kit") :), que es un referencia destinada a empresas manufactureras y para la fabricación de accesorios para Android. Nébula permite conectarse a hardware haciendo uso del ADK por medio USB conectando el dispositivo móvil al hardware en modo Accessory.
        </p>
      </div>
      <div class="col-sm-4">
        <i class="flaticon-bt"></i>
        <h5>Bluetooth</h5>
        <p>
          Nébula facilita conexiones entre dispositivos Android y hardware compatible con Arduino que pueda comunicarse vía Bluetooth. Esto puede ser mediante alguna placa con un puerto USB Host a la que se le pueda conectar un llavero Bluetooth o por medio de los módulos Bluetooth como el (: enlace("HC-05") :) o (: enlace("BlueSMiRF") :).
        </p>
      </div>
    </div>

  </div>
</div>
