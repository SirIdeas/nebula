<?php

/**
 * Clase para formato personalizado de fechas
 */

class AmDateTime extends DateTime{

  public static

    // Los indices a seran del formaro {i}. ejemplo: {d}/{M}/{Y} => "29/11/2014"
    $indexes = "/(\{([dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]?)\})/",

    // Nombre de los meses
    $monthsName = array(
      "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio",
      "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre",
    ),

    // Nombre de los meses abreviados
    $monthsAbr = array(
      "ene", "feb", "mar", "abr", "may", "jun",
      "jul", "ago", "sep", "oct", "nov", "dic",
    ),

    // Nombre de los días de la semana comenzado en el domingo
    $weekDaysName = array(
      "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado",
    ),

    // Nombre de los dias de la semana abreviados comanzando en el domingo
    $weekDaysAbr = array(
      "dom", "lun", "mar", "mie", "jue", "vie", "sab",
    );

  protected static
    // Indices a truncar:
    // $indice => "$indice_ref:$arrayTranf[:ajuste]"
    $truncateIndexes = array(
      "l" => "w:weekDaysName",  // Nombre de días de semana
      "D" => "w:weekDaysAbr",   // Nombre de días de semana abreviado
      "F" => "n:monthsName:-1", // Nombre de meses
      "M" => "n:monthsAbr:-1",  // Nombre de meses abreviados
    ); 

  // Obtiene el valor truncado de para in indice y un $time determinado
  protected static function getTruncateValue($i, $time){
    // Si el indice no está en la lista de indices truncados
    // se retorna el valor normal con date.
    if(!isset(self::$truncateIndexes[$i])) return date($i, $time);

    // Separar indice
    list($j, $var, $sum) = explode(":", self::$truncateIndexes[$i].":0");

    // Determinar indice de referencia
    $jValue = date($j, $time) + $sum;

    // Obtener la array con valores
    $arr = self::$$var;

    // Retornar valor
    return isset($arr[$jValue])? $arr[$jValue] : "err$jValue";

  }

  // Funcion sobreescrita de formato.
  public function format($format){
    
    // Obtener el tiempo de la fecha actual
    $time   = $this->getTimestamp();

    // Mientras exista un indice
    while(preg_match(self::$indexes, $format, $m)){
      $i = $m[2]; // Indice a evaluar
      // Se intercambia el valor obtenido de la evaluacion con
      // en todas las posiciones donde exista el indice evaluado
      $format = str_replace($m[1], self::getTruncateValue($i, $time), $format);
    }
    
    return $format;

  }

}
