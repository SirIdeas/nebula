<?php

/**
 * Validacion del rango de un campo
 */

AmORM::validator("min_value");
AmORM::validator("max_value");

class RangeValidator extends AmValidator{
  
  protected
      $minValidator = null, // Instancia del validador inferior
      $maxValidator = null, // Instancia del validador superior
      $min = null,
      $max = nulll;
  
  public function __construct($options = array()){
    
    // Preparar validador del limite inferior
    $min = isset($options["min"])? $options["min"] : null;
    unset($options["min"]);
    $this->minValidator = new MinValueValidator($min, $options);
    $this->min($min);
    $this->setSustitutions("min", "min");

    // Preparar validador del limite superior
    $min = isset($options["max"])? $options["max"] : null;
    unset($options["max"]);
    $this->maxValidator =new MaxValueValidator($max, $options);
    $this->max($max);
    $this->setSustitutions("max", "max");

    parent::__construct($options);
  }
  
  // La validacion consiste en cumplir con los dos validadores
  protected function validate(AmModel &$model){
    return $this->getMinValidator()->validate($model) && $this->getMaxValidator()->validate($model);
  }
  
  // Al setear el valor de validador se debe cambiar tambien a los validadores internos
  public function setFieldName($value = null){
    $this->getMinValidator()->setFieldName($value);
    $this->getMaxValidator()->setFieldName($value);
    return parent::setFieldName($value);
  }
  
  // Limite inferior
  public function getMinValidator(){ return $this->minValidator; }
  public function getMin(){ return $this->getMinValidator()->getMin(); }
  public function setMin($value){ return $this->getMinValidator()->setMin($value); }

  // Limite superior
  public function getMaxValidator(){ return $this->maxValidator; }
  public function getMax(){ return $this->getMaxValidator()->getMax(); }
  public function setMax($value){ return $this->getMaxValidator()->setMax($value); }

}
