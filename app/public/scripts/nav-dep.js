// Este archivo carga un dialog de BS con un mensaje para los navegadores descontinuados
// Necesita Jquery

(function($){
  'use strict';

  // Variable que indica que esta libreria esta cargada.
  // Se puede asignar una funcion a esta variable para realizar una accion cuando se cierre el dialogo
  window.__waitNavDepModal = true;

  var view = [
    '<div class="modal fade">',
      '<div style="display: table; width: 100%; height: 100%;">',
        '<div style="display: table-cell;vertical-align:middle;">',
          '<div class="modal-content" style="width: 500px; margin: 0 auto;">',
            '<div class="modal-body">',
              '<p>',
                'Su navegador está <strong>descontinuado</strong>, por motivos de <strong>seguridad</strong>',
                'y para disfrutar de una experiencia completa en la aplicación, es recomendable actualizarlo.',
              '</p>',
              '<p>',
                'Por favor <a a-there href="http://browsehappy.com/" target="_blank">actualice su navegador</a> para',
                'mejorar su experiencia en la web, o haga clic en el botón para cerrar éste diálogo.',
              '</p>',
            '</div>',
            '<div class="modal-footer">',
              '<a a-close type="button" class="btn btn-danger">',
                'Continuar',
              '</a>',
            '</div>',
          '</div>',
        '</div>',
      '</div>',
    '</div>',
  ].join('');

  $(function(){

    var modal = $(view);      // convertir a jQuery
    $('body').append(modal);  // Agregar al cuerpo

    modal.find('a[a-close]').click(function(){
      modal.modal('hide');
    });

    // Cuando ce cierre la ventana se llamara la 
    // window.__waitNavDepModal si esta es una funcion
    modal.on('hidden.bs.modal', function(){
      if(typeof(window.__waitNavDepModal)==="function"){
        window.__waitNavDepModal();
      }
    });

    // Mostrar modal
    modal.modal('show');

  });

})($);