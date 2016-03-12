<?php

/**
 * Validacion de campos tipos Email
 */

AmORM::validator("regex");

class EmailValidator extends RegexValidator{

  public function __construct($options = array()){
    $options["regex"] = "/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/";
    parent::__construct($options);
  }
  
}
