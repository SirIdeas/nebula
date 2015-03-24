<?php

/**
 * Validacion del tamano minimo de un campo
 */

class MinLengthValidator extends AmValidator{
  
  protected
      $min = null;  // Tamanio minimo del campo
  
  public function __construct($options = array()){
    $this->setSustitutions("min", "min");
    parent::__construct($options);
  }
  
  protected function validate(AmModel &$model){
    $min = $this->getMin();
    return $min != null && strlen(trim($this->value($model))) >= $min;
  }

  // Tamanio minimo
  public function getMin(){ return $this->min; }
  public function setMin($min){ $this->min; return $this; }

}
