// Este archivo carga mensaja de alerta sobre el suo de cookies
// Necesita Jquery

(function($){
  'use strict';

  $.cookieMsg = function(pos, key, aboutHref){

    if(localStorage[key]!=='true'){

      var view = [
        '<div style="position:fixed; '+pos+':0; left:0; right:0; background-color:#000; z-index:9999; color:#fff; text-align:center; filter:alpha(opacity=80); opacity: 0.8">',
          '<div style="position:absolute; right:0; top:0; bottom:0;">',
            '<div style="display:table; height:100%;">',
              '<div style="display:table-cell; vertical-align:middle; padding-right:10px;">',
                '<a a-close style="font-size:12px; float:right; color:#fff; text-decoration:none; cursor:pointer;">Cerrar</a>',
              '</div>',
            '</div>',
          '</div>',
          '<p style="margin:1em; color: white">',
            'Utilizamos cookies propias y de terceros para realizar análisis de uso y de medición',
            'de nuestra webpara mejorar nuestros servicios.<br>Si continua navegando, consideramos',
            'que acepta su uso. Puede cambiar la configuración u obtener más información ',
            '<a a-there href="#">aquí</a>.',
          '</p>',
        '</div>'
      ].join('');

      $(function(){
        
        var modal = $(view);      // convertir a jQuery
        $('body').append(modal);  // Agregar al cuerpo

        modal.find('a[a-close]').click(function(){
          modal.fadeOut();
          localStorage[key]='true';
        });

        // Asignar enlace de acerca de
        modal.find('a[a-there]').attr('href', aboutHref);

        // Mostrar modal
        modal.fadeIn();

      });

    }

  };

})($);