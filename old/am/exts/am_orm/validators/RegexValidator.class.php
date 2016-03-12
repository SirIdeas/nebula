<?php

/**
 * Validacion con regex
 */

class RegexValidator extends AmValidator{
  
  // Propiedades
  protected
      $regex = null,      // Regex con la que se evaluará la validez
      $canBlank = false;  // Si permite valores vacios

  // Constructor
  public function __construct($options = array()){
    
    // Obtner si permiterá valores blancos
    $blank = isset($options["blank"])? $options["blank"] : false;
    unset($options["blank"]);
    $this->setCanBlank($blank === true);

    parent::__construct($options);
  }
  
  // Condicion de validacion
  protected function validate(AmModel &$model){
    return (preg_match($this->getRegex(), $this->value($model)) ||
        ($this->getCanBlank() && trim($this->value($model) == "")));
  }

  // Regex
  public function getRegex(){     return $this->regex; }
  public function setRegex($value){ $this->regex = $value; return $this; }

  // Can Blank
  public function getCanBlank(){  return $this->canBlank; }
  public function setCanBlank($value){ $this->canBlank = $value; return $this; }
  
}
