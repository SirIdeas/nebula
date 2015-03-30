(# parent:views/-viewport.php #)
(# set:title="{$_env->name} | Inicio" #)
(# set:navCls="home" #)

<div class="home-bg">
  <div class="home-inner">
    <div class="container center">
      <br>
      <br>
      <br>
      <img src="<?php Am::eUrl() ?>/images/nb-m.png" alt="Logo">
      <h1><?php echo $_env->name ?></h1>
      <h5 class="light">
        Librería para comunicación de Aplicaciones Android con placas Arduino
      </h5>
      <div class="center">
        <a href="<?php Am::eUrl() ?>/comenzar" class="btn-large waves-effect waves-light blanco-fade">
          Comenzar
        </a>
      </div>
      <br>

      <div class="row">
        <div class="col m2">&nbsp;</div>
        <div class="col m4">
          <i class="flaticon-android"></i>
          <h5>ADK</h5>
          <p>
            ADK son las siglas de <?php enlace("Accessory Development Kit") ?>, que es un referencia destinada a empresas manufactureras y para la fabricación de accesorios para Android. Nébula permite conectarse a hardware haciendo uso del ADK por medio USB conectando el dispositivo móvil al hardware en modo Accessory.
          </p>
        </div>
        <div class="col m4">
          <i class="flaticon-bt"></i>
          <h5>Bluetooth</h5>
          <p>
            Nébula facilita conexiones entre dispositivos Android y hardware compatible con Arduino que pueda comunicarse vía Bluetooth. Esto puede ser mediante alguna placa con un puerto USB Host a la que se le pueda conectar un llavero Bluetooth o por medio de los módulos Bluetooth como el <?php enlace("HC-05") ?> o <?php enlace("BlueSMiRF") ?>.
          </p>
        </div>
      </div>

    </div>

  </div>

</div>
