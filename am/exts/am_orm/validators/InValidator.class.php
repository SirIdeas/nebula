<?php

/**
 * Validacion del posibles valores para un campo
 */

class InValidator extends AmValidator{
  
  protected
      $values = null; // Lista de valores validos para el campos

  public function __construct($options = array()){
    $this->setSustitutions('values', 'values');
    parent::__construct($options);
  }
    
  protected function validate(AmModel &$model){
    return in_array($this->value($model), $this->values());
  }

  // Posibles valores validos para el campo
  public function getValues(){ return $this->values; }
  public function setValues($value){ $this->values = $value; return $this; }
  

}
