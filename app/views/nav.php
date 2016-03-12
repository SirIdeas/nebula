<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand vertical-middle" href="(:/:)">
        <div><span class="logo"></span></div>
        <div>&nbsp;&nbsp;Nébula</div>
      </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav"></ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown (:= $pagina=='comenzar'? 'active' : '' :)">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Comenzar <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="(:= $paso=='comenzar'? 'active' : '' :)"><a href="(:/:)/comenzar">Índice</a></li>
            <li role="separator" class="divider"></li>
            <li class="(:= $paso=='instalacion'? 'active' : '' :)"><a href="(:/:)/comenzar/instalacion">Instalación</a></li>
            <li class="(:= $paso=='adk-led-blink'? 'active' : '' :)"><a href="(:/:)/comenzar/adk-led-blink">ADK Led Blink</a></li>
            <li class="(:= $paso=='bt-led-blink'? 'active' : '' :)"><a href="(:/:)/comenzar/bt-led-blink">BT Led Blink</a></li>
          </ul>
        </li>
        <li class="dropdown (:= $pagina=='documentacion'? 'active' : '' :)">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Documentación <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li class="(:= $paso=='documentacion'? 'active' : '' :)"><a href="(:/:)/documentacion">Índice</a></li>
            <li role="separator" class="divider"></li>
            <li class="(:= $paso=='estructura'? 'active' : '' :)"><a href="(:/:)/documentacion/estructura">Estructura general</a></li>
            <li class="(:= $paso=='comunicacion'? 'active' : '' :)"><a href="(:/:)/documentacion/comunicacion">Comunicación</a></li>
            <li class="(:= $paso=='sketchs'? 'active' : '' :)"><a href="(:/:)/documentacion/sketchs">Sketchs</a></li>
            <li class="(:= $paso=='componentes'? 'active' : '' :)"><a href="(:/:)/documentacion/componentes">Componentes</a></li>
            <li class="(:= $paso=='helpers'? 'active' : '' :)"><a href="(:/:)/documentacion/helpers">Helpers</a></li>
            <li class="(:= $paso=='personalizacion-en-arduino'? 'active' : '' :)"><a href="(:/:)/documentacion/personalizacion-en-arduino">Personalización en Arduino</a></li>
          </ul>
        </li>
        <li class="(:= $pagina=='acerca-de'? 'active' : '' :)"><a href="(:/:)/acerca-de">Acerca de</a></li>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</nav>