<?php

/**
 * Validacion del tamano minimo y maximo
 */

AmORM::validator('min_length');
AmORM::validator('max_length');

class LengthValidator extends AmValidator{
  
  protected
      $maxValidator = null,
      $minValidator = null,
      $max = null,
      $min = null;
  
  public function __construct($options = array()){
    
    // Preparar validador del tamanio minimo
    $min = isset($options["min"])? $options["min"] : null;
    unset($options["min"]);
    $this->minValidator = new MinLengthValidator($min, $options);
    $this->min($min);
    $this->setSustitutions("min", "min");

    // Preparar validador del tamanio maximo
    $min = isset($options["max"])? $options["max"] : null;
    unset($options["max"]);
    $this->maxValidator =new MaxLengthValidator($max, $options);
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
  
  // Tamanio minimo
  public function getMinValidator(){ return $this->minValidator; }
  public function getMin(){ return $this->getMinValidator()->getMin(); }
  public function setMin($value){ return $this->getMinValidator()->setMin($value); }

  // Tamanio maximo
  public function getMaxValidator(){ return $this->maxValidator; }
  public function getMax(){ return $this->getMaxValidator()->getMax(); }
  public function setMax($value){ return $this->getMaxValidator()->setMax($value); }

}
