<?php

/**
 * Validacion del limite superior de un campo
 */

AmORM::validator("float");

class MaxValueValidator extends FloatValidator{
  
  protected
      $max = null;  // Limite superior del campo

  public function __construct($options = array()){
    $this->setSustitutions("max", "max");  // Agregar sustitucion
    parent::__construct($options);
  }

  protected function validate(AmModel &$model){
    $max = $this->getMax();
    return $max != null && $this->value($model) <= $max;
  }

  // Limite superior
  public function getMax(){ return $this->max; }
  public function setMax($max){ $this->max; return $this; }

}
