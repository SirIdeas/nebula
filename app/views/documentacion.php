(# parent:views/_content.php #)
(# set:title="{$_env->name} | Documentación" #)
(# set:pagina="documentacion" #)
(# set:paso="documentacion" #)

<h1>Documentación</h1>

<h4><a href="<?php Am::eUrl() ?>/documentacion/estructura">Estructura general</a></h4>
<p>
  Se explica la estructura de las librerías de Nébula para Arduino y para Android.
</p>

<h4><a href="<?php Am::eUrl() ?>/documentacion/comunicacion">Comunicación</a></h4>
<p>
  Nébula permite la comunicación entre dispositivos Android y accesorios principalmente por dos métodos: ADK y Bluetooth. En este apartado se explica con cierto detalle los tipos de comunicación y como hacer uso de estas tanto en Arduino como en Android.
</p>

<h4><a href="<?php Am::eUrl() ?>/documentacion/sketchs">Sketchs</a></h4>
<p>
  Los Sketchs representan un conjunto de componentes que funcionarán con un proyecto. Además de estos, dentro de los skecths se implementa una forma predeterminada de comunicación. En este apartado se explica los aspectos generales de los sketchs, el proceso de comunicación que implementa y se da un introducción a los componentes.
</p>

<h4><a href="<?php Am::eUrl() ?>/documentacion/componentes">Componentes</a></h4>
<p>
  Los componentes son abstracciones de software de los elementos electrónicos utilizados en los proyectos de automatizacion, tales como interruptores, led, motores y sensores. En este apartado se detalla todos los componentes disponibles en Nébula y como usarlos.
</p>

<h4><a href="<?php Am::eUrl() ?>/documentacion/helpers">Helpers</a></h4>
<p>
  Nébula ofrece un conjunto de clases que pueden ser utilizadas para comenzar a programar nuestra aplicación fácilmente. En este apartado se explica como utilizar estos helpers.
</p>

<h4><a href="<?php Am::eUrl() ?>/documentacion/personalizacion-en-arduino">Personalización en Arduino</a></h4>
<p>
  El objetivo principal de nébula es evitar la programación en Arduino, sin embargo, permite la personalización de la comunicación en muchos aspectos, mediante la codificación extra en Arduino. En este apartado se explica como realizar ciertos trucos avanzados para personalizar y extender las funcionalidades que ofrece Nébula.
</p>
