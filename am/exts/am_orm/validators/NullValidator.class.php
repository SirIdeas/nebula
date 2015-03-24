<?php

/**
 * Validacion para valores nulos
 */

class NullValidator extends AmValidator{
    
  protected function validate(AmModel &$model){
    return $this->value($model) !== null;
  }
  
}
