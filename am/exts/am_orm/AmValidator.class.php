<?php

/**
 * Clase para los validadores de los campos de las tablas de las BD
 */

class AmValidator extends AmObject{
  
  protected static
    $conf = null;
  
  // Propiedades del balidator
  protected
    $name = null,     // nombre
    $message = null,  // Mensaje
    $if = null,       // Condicion de aplicacion
    $force = false,   // Forzar 

    // Sustituciones de atributos
    $sustitutions = array("value" => "value", "fieldname" => "name");
  
  // Constructor de validador
  public function __construct($data = null) {
    
    if(!isset($data["message"])){
      
      // Obtener la configuraciones de los validators
      if(!isset(self::$conf))
        self::$conf = Am::getAttribute("validators", array());

      // Obtener el nombre del validator
      $validatorName = strtolower($this->getValidatorName());
      
      // Obtener el mensaje de la configuracion
      if(isset(self::$conf["message"][$validatorName]))
        $data["message"] = self::$conf["message"][$validatorName];

    }
    
    // Llamar constructor
    parent::__construct($data);
    
  }
  
  // Devuelve el nombre del validador por el tipo  
  protected function getValidatorName(){
    return preg_replace("/(.*)Validator$/", "$1", get_class($this));
  }
  
  // Valida el modelo
  public function isValid(AmModel &$model){
    
    // Obtener propiedades necesarias
    $fnIf = $this->getFnIf();
    $field = $model->getTable()->getField($this->getFieldName());
    $notNull = $field? $field->getNotNull() : false;
    
    // Condiciones para no validar
    if((!$notNull && null === $this->value($model) && false === $this->getForce()) ||
        (method_exists($model, $fnIf) && !$model->$fnIf($this)))
      return true;
    
    // Realizar validacion
    $this->value = $this->value($model);
    return $this->validate($model);
    
  }
  
  // Indica si el modelo cumple con el validador actual
  protected function validate(AmModel &$model){
    return true;
  }
  
  // Métodos GET para algunas propiedades
  public function getFnIf(){                return $this->if; }
  public function getForce(){               return $this->force; }
  public function getFieldName(){           return $this->name; }
  public function getSustitutions(){        return $this->sustitutions; }
  public function getSustitution($substr){  return isset($this->sustitutions[$substr])? $this->sustitutions[$substr] : null; }
  public function getMessage(AmModel $model){

    $ret = $this->message;  // Obtener mensaje

    // Obtener las sustituciones a realizar
    $substitutions = $this->getSustitutions();

    // Sustituir valores de las propiedades en el mensaje
    foreach($substitutions as $substr => $for){
      $value = $this->$for;
      if(is_array($value)){
        $value = implode(",", $value);
      }
      $ret = str_replace("[$substr]", $value, $ret);
    }
    
    return $ret;

  }

  // Métodos SET para algunas propiedades
  public function setFnIf($value){      $this->if = $value;       return $this; }
  public function setForce($value){     $this->force = $value;    return $this; }
  public function setFieldName($value){ $this->name = $value;     return $this; }
  public function setMessage($value){   $this->message = $value;  return $this; }

  public function setSustitutions($substr, $for){
    $this->sustitutions[$substr] = $for;
    return $this;
  }
  
  // Obtener el valor de un modelo dependiendo el campos vigilado por el validator
  protected function value(AmModel $model){
    return $model->getFieldValue($this->getFieldName());
  }

}
