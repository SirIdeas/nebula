<?php

/**
 * Validacion de valores flotantes
 */

AmORM::validator("regex");

class FloatValidator extends RegexValidator{

  public function __construct($options = array()){
    $options["regex"] = "/^[\-0-9]*\.?[0-9]*$/";
    parent::__construct($options);
  }
  
}
