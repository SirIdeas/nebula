<?php

/**
 * Validacion de capos tipos telefono
 */

AmORM::validator("regex");

class PhoneValidator extends RegexValidator{

  public function __construct($options = array()){
    $options["regex"] = "/^(\(?[0-9]{3,3}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/";
    parent::__construct($options);
  }
  
}
