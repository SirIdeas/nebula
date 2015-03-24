<?php

/**
 * Clase base para los modelos
 */

class AmModel extends AmObject{
  
  // Propiedades
  protected
    $table = null,        // Instancia de la tabla
    $isNew = true,        // Indica si es un registro nuevo
    $errors = array(),    // Listados de errores
    $realValues =array(), // Valores reales
    $errorsCount = 0;     // Cantidad de errores

  // El constructor se encarga de asignar la instancia de la tabla correspondiente al model
  public function __construct($params = array()) {
    
    $params = AmObject::parse($params);
    
    // Obtener el nombre de la fuente
    $source = is_string($params["source"]) ? $params["source"] : $params["source"]->getName();

    // Obtener el nombre de la tabla
    $this->table = AmORM::table($params["tableName"], $source);
    
    // Eliminar parametros de la configuracion
    unset($params["source"]);
    unset($params["tableName"]);
    
    // Obtener los campos
    $fields = (array)$this->getTable()->getFields();
    
    // Por cada campo
    foreach($fields as $fieldName => $field){
      
      // Obtener nombre del campo
      $fieldNameBD = $field->getName();
      
      // Obtener nombre del metodo set para el campo en el model
      $methodFieldName = "set_$fieldName";
      
      $value = null;
      
      // Si el campo existe en los parametros
      if(isset($params[$fieldNameBD])){
        // Obtener el valor
        $value = $params[$fieldNameBD];
        // Eliminar de los parametros
        unset($params[$fieldNameBD]);
      }else{
        // Si no existe en los parametros se toma el valor
        // por defecto del campo
        $value = $field->getDefaultValue();
      }

      // Asignar valor mediante el metodo set 
      $this->$methodFieldName($field->parseValue($value));
      
    }
    
    // Limpiar errores
    $this->clearErrors();
    
    // Llamar al constructor
    parent::__construct($params);
    
    // Tomar valores reales
    $this->realValues = $this->toArray();
    
    // Llamar el metodo init del model
    $this->init();
    
  }
  
  // Método redefinido el usuario para inicializaciones customizadas
  public function init(){}
  
  // Métodos GET para algunas pripiedades
  public function getTable(){ return $this->table; }
  public function isNew(){ return $this->isNew; }
  public function errorsCount(){ return $this->errorsCount; }
  public function getRealValues(){ return $this->realValues; }
  public function getRealValue($name){ return isset($this->realValues[$name]) ? $this->realValues[$name] : null; }

  // Devuelve el valor de un campo. Si existe un metodo get para dicho campo
  // se obtiene el valor mediante este. De lo contrario se obtiene
  // directamente.
  public function getFieldValue($field){

    // Obtener el valor del campo
    if(method_exists($this, $methodName = "get_{$field}"))
      return $this->$methodName();
    return $this->$field;

  }
  
  // Devuelve todos los errores del model, los errores de un campo, o un error especifico
  public function getErrors($field = null, $errorName = null){
    
    // Se retorna todo los errores
    if(!isset($field))
      return $this->errors;

    // Si no existe se crea el array
    if(!isset($this->errors[$field]))
      $this->errors[$field] = array();

    // Se devuelve el hash de errores del campo consultado
    if(!isset($errorName))
      return $this->errors[$field];

    // Se devuelve el error especifico del campo consultado
    if(!isset($errorMsg))
      return $this->errors[$field][$errorName];

    // Devolver todos los errores
    return $this->errors;

  }

  // Agregar error
  public function addError($field, $errorName, $errorMsg){
    // Se asigna el error
    $this->errors[$field][$errorName] = $errorMsg;
    $this->errorsCount++;
    return $this;
  }

  // Método get para asignar si es o no un registro nuevo
  public function setIsNew($value){ return $this->isNew = $value; }
  
  // Funcion para signar valores a los atributos en lote
  public function setValues($values, array $fields = array()){
    
    // Si no se recibió la lista de campos a asignar, se tomarán
    // todos los campos de la tabla
    if(empty($fields))
      $fields = array_keys((array)$this->getTable()->getFields());
    
    // Recorrer cada columan de cada referencia
    $references = $this->getTable()->getReferencesTo();
    foreach($references as $rel){
      $cols = array_keys($rel->getColumns());
      foreach($cols as $from){
        
        // Las referencias si es un valor vacío se debe setear a null 
        $value = trim(isset($values[$from])? $values[$from] : "");
        $values[$from] = empty($value) ? null : $value;
        
      }
    }
    
    // Obtener la tabla
    $table = $this->getTable();
    foreach($fields as $fieldName){
      $field = $table->getField($fieldName);  // Obtener el campos
      // Si exist el campo y es no es un campo autoincrementable
      if((!$field || !$field->getAutoIncrement()) && isset($values[$fieldName]))
        // Se asigna el valor
        $this->$fieldName = $values[$fieldName];
    }
    
  }
  
  // Limpiar los errores
  public function clearErrors(){
    $this->errors = array(); // Resetear los errores
    $this->errorsCount = 0;  // Resetear la cantidad de errores
  }

  // Devuelve el indice correspondiente al registro
  public function index(){
    
    $ret = array(); // Para el retorno
    $pks = $this->getTable()->getPks(); // Obtener PKs

    // Agregar los IDs
    foreach($pks as $pk)
      $ret[$pk] = $this->getRealValue($pk);

    return $ret;

  }
  
  // Método que indica si un campo ha cambiado o no de valor desde
  // su inicialización
  public function hasChanged($name){
    return $this->getRealValue($name) != $this->$name;
  }

  // Obtener los campos los cambios de los campos que se ha realizado
  public function getChanges(){
    $changes = array(); // Para el retorno

    // Recorrer los valores reales
    foreach($this->realValues as $name => $value){
      // Si el campo cambió se agrega al listado de cambios
      if($this->hasChanged($name)){
        $changes[$name] = array(
          "from" => $value,
          "to" => $this->$name,
        );
      }
    }
    return $changes;
  }
  
  // Devuelve un array con los valores de los campos de la tabla
  public function dataToArray($withAI = true){

    $ret = array(); // Para el retorno
    // Obtener los campos
    $fields = $this->getTable()->getFields();
    foreach($fields as $fieldName => $field){
      // Si se pidió incorporar los valores autoincrementados
      // o si el campo no es autoincrementado
      if($withAI || !$field->getAutoIncrement())
        // Se agrega el campo al array de retorno
        $ret[$fieldName] = $this->$fieldName;
    }

    return $ret;

  }
  
  // Devuelve una consulta que selecciona el registro actual
  public function getQuerySelectItem(){
    return $this->getTable()->findById($this->index());
  }
  
  // Devuelve una consulta para realizar los campos realizados en el modelo
  protected function getQueryUpdate(){
    
    // Obtener los campos
    $fields = $this->getTable()->getFields();

    // Obtener una consulta para selecionar el registro
    $q = $this->getQuerySelectItem();
    
    // Recorrer los campos para agregar los sets
    // de los campos que cambiaron
    foreach($fields as $fieldName => $field)
      // Si el campo cambió
      if($this->hasChanged($fieldName))
        // Agregar set a la consulta
        $q->set($field->getName(), $this->$fieldName);
        
    // Devolver consulta generada
    return $q;

  }
  
  // Validar todo el modelo
  public function validate(){

    // Limpiar los errores
    $this->clearErrors();

    // Obtener nombre de validator definidos
    $validatorNames = array_keys((array)$this->getTable()->getValidators());
    
    // Validar todos los campos
    foreach($validatorNames as $field)
      $this->validateField($field);
  }

  // Ejecuta las validaciones para un campo
  public function validateField($field){
    // Obtener validator del campo
    $validators = $this->getTable()->getValidators($field);

    foreach($validators as $nameValidator => $validator){
      // Si el modelo no cumple con la validacion
      if(!$validator->isValid($this))
        // Se agrega el error
        $this->addError($field, $nameValidator, $validator->getMessage($this));
    }

  }
  
  public function isValid($field = null){

    // Si se indico un campo
    if(isset($field)){
      // Limpiar los errores
      $this->clearErrors();
      // Validar solo el campo
      $this->validateField($field);
    }else{

      // Validar todos los campos
      $this->validate();
    }

    // Es valido si no se generaron errores
    return $this->errorsCount() === 0;

  }
  
  // Métodos para obtener el SQL para insert, update y delete
  public function sqlInsertInto(){  return $this->getTable()->sqlInsertInto(array($this)); }
  public function sqlUpdate(){      return $this->getQueryUpdate()->sqlUpdate(); }
  public function sqlDelete(){      return $this->getQuerySelectItem()->sqlDelete(); }

  // Métodos para Insertar, Actualizar y eliminar el registro
  public function insertInto(){     return $this->getTable()->insertInto(array($this)); }
  public function update(){         return $this->getQueryUpdate()->update(); }
  public function delete(){         return $this->getQuerySelectItem()->delete() !== false; }

  // Guarda los cambios del registro
  // Si es un registro nuevo entonces el registro
  // se intentará insertar en la tabla.
  // Si no es un registro nuevo entonces
  // Se intentará actualziar los datos del registro
  // imagen en la tabla
  public function save(){
    
    // Si todos los campos del registro son válidos
    if($this->isValid()){
      
      // Si es un registro nuevo se insertará
      if($this->isNew()){
        
        // Insetar en la BD. Ret será igual a de generado
        // del registro en el caso de tener como PK un campo
        // autoincrementable o false si se generá un error
        if(false !== ($ret = $this->insertInto())){
          // Obtener todos los campos de la tabla del modelo
          $fields = $this->getTable()->getFields();

          // Recorrer campos
          foreach($fields as $f){
            
            // Agregar el valor que retorno el insert
            // si se trata de un campo autoincrementable
            if($f->getAutoIncrement()){
            
              // Obtener el nombre del método SET
              // para asigar el valor autoincrementado
              $method = "set_" . $f->getName();
              $this->$method($ret);

            }
            
          }

          // Si el valor es diferente de false
          // Indicar que ya no es registro nuevo
          $this->setIsNew(false);
        
          // Los nuevo valores reales del registro serán
          // los que se tiene desdpue de insertar 
          $this->realValues = $this->toArray();
        
          // Si ret == 0 es xq se interte correctamenre
          // pero la tabla no tiene una columna autoincrement
          // Se retorna verdadero o el valor del ID generado
          // para el registro si se agregó correctamenre
          // de lo contrario se retorna falso
          return $ret === 0 ? true : $ret;
            
        }
        
      }else{

        // Se intenta actualizar los datos del registro en la BD
        if($this->update()){
            
          // Si se actualiza correctamente entonces
          // los datos reales nuevos seran los que
          // tiene el registro
          $this->realValues = $this->toArray();
          
          // retornar true indicando el exito de la operacion
          return true;
          
        }

      }

      // Si se llega a este punto es porque se generó un error
      // en la insercion o actualizacion, por lo que se agrega un
      // error global con el ultimo error generado en  el Gestor
      $this->addError("__global__",
        $this->getTable()->getSource()->getErrNo(),
        $this->getTable()->getSource()->getError());
      
    }
    
    return false;
    
    
  }

  // // Convirte el ID del registro en un string con cada uno de sus valores
  // // separados por "/"
  // public function pkToString($encode = false){
  //   $ret = array();
  //   foreach($this->index() as $index)
  //     $ret[] = ($encode===true)? urlencode($index) : $index;
  //   return implode("/", $ret);
  // }
  
  // // Convertir el registro en string implica obtener una cadena de su ID
  // public function __toString() {
  //   return $this->pkToString();
  // }

  // // PENDIENTE DESARROLLAR
  // public static function export(){}
  
}
