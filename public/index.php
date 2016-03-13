<?php
/**
 * Amathista - PHP Framework
 *
 * @author   Alex J. Rondón <arondn2@gmail.com>
 */

/**
 * -----------------------------------------------------------------------------
 * Inclusión de núcleo de Amathista.
 * -----------------------------------------------------------------------------
 * Inclusión del núcleo de Amathista. Esto incluye la clase principal Am,
 * helpers escenciales, manejo de errores personalizado.
 * 
 */
if(is_file('../../am/autoload.php'))
  include '../../am/autoload.php';
elseif(is_file('../../../am/am/autoload.php'))
  include '../../../am/am/autoload.php';
else
  include '../../amathista/am/autoload.php';

/**
 * -----------------------------------------------------------------------------
 * Despachar la petición con la aplicación
 * -----------------------------------------------------------------------------
 * Si se esta ejecutando Amathista desde la línea de comandos, entonces se
 * entrará el interprete PHP con todas las dependecias de la aplicación
 * configurada, de lo contrario se despachará como una petición HTTP.
 * 
 */
Am::run();
