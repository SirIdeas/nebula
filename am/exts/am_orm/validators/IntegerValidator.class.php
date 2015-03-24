<?php

/**
 * Validacion de valores enteros
 */

AmORM::validator('regex');

class IntegerValidator extends RegexValidator{

  public function __construct($options = array()){
    $options['regex'] = '/^[\-0-9]+$/';
    parent::__construct($options);
  }

}
