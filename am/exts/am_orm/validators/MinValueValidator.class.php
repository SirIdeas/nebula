<?php

/**
 * Validacion del limite inferior de un campo
 */

AmORM::validator("float");

class MinValueValidator extends FloatValidator{
  
  protected
      $min = null;  // Limite inferior del campo

  public function __construct($options = array()){
    $this->setSustitutions("min", "min");  // Agregar sustitucion
    parent::__construct($options);
  }

  protected function validate(AmModel &$model){
    $min = $this->getMin();
    return $min != null && $this->value($model) >= $min;
  }

  // Limite superior
  public function getMin(){ return $this->min; }
  public function setMin($min){ $this->min; return $this; }

}
