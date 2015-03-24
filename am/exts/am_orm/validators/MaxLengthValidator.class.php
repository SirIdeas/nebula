<?php

/**
 * Validacion del tamano maximo de un campo
 */

class MaxLengthValidator extends AmValidator{
  
  protected
      $max = null;  // Tamanio maximo del campo
  
  public function __construct($options = array()){
    $this->setSustitutions("max", "max");
    parent::__construct($options);
  }
  
  protected function validate(AmModel &$model){
    $max = $this->getMax();
    return $max != null && strlen(trim($this->value($model))) <= $max;
  }

  // Tamanio maximo
  public function getMax(){ return $this->max; }
  public function setMax($max){ $this->max; return $this; }

}
