<?php

/**
 * Clase para objetos en Amathista
 */

class AmObject implements Iterator, Countable, ArrayAccess{ //Reflector, 
  
  /**
   * Lista de propiedades creados dinamicamente
   **/
  private $_f = array();

  /**
   * Llamada del constructor. Inicializa los atributos indicados en el array data
   **/
  public function __construct($data = null){
    
    // Transformar atributos a array
    $data = self::parse($data);
  
    // Asignar atributos    
    foreach($data as $attr => $value){
      $this->$attr = $value;
    }
    
  }
  
  /**
   * Funciones Get y Set. Si "value!=null" de retorna la propiedad "key",
   * sino se asignara "value" a la propiedad "key"
   **/
  protected function attr($key, $value = null){

    if(isset($value)){
      
      // como set
      $this->$key = $value;
      return $this;
      
    }
    
    // como get
    return $this->$key;
    
  }
  
  /**
   * Llamada de la consulta a una propiedad del objeto
   * $this->name
   **/
  public function __get($name){
    
    // Si existe entre los atributos no definidos devolver el valor
    if(in_array($name, $this->_f) && property_exists($this, $name)){
      return $this->$name;
    }
    
    // Devolver nul si no existe
    return null;

  }

  /**
   * Llamada de la asignacion a una propiedad
   * $this->name = $value
   **/
  public function __set($name, $value){
    
    // Si no existe una propiedad de clase en el objeto agregar
    // atributo a lista de atributos dinamicos
    if(!empty($name) && !property_exists($this, $name)){
      $this->_f[] = $name;
    }
    
    // Si propiedad es un atributo dinamico asignar valor a atributo
    if(in_array($name, $this->_f)){
      $this->$name = $value;
    }
    
  }

  /**
   * Llamda de "isset" a una propiedad
   * isset($this->name)
   **/
  public function __isset($name){
    return isset($this->$name);
  }

  /**
   * Llamada de "unset" a una propiedad
   * unset($this->name)
   **/
  public function __unset($name){
    
    // Si es un atributo dinamico
    if(in_array($key, $this->_f)){
      
      // Eliminar atributo dinamico
      $this->_f = array_diff($this->_f, array($key));
      unset($this->$key);
      
    }
    
    return $this;
    
  }
  
  /**
   * Llamada de la asignacion de valores en el objeto como un array
   * $this[$name] = $value
   **/
  public function offsetSet($name, $value){ $this->$name = $value; }

  /**
   * Llamada de la funcion isset sobre el objeto utilizado como un array
   * isset($this[$name])
   **/
  public function offsetExists($name){ return isset($this->$name); }

  /**
   * Llamada de la funcion unset sobre el objeto como un array.
   * unset($this[$name])
   **/
  public function offsetUnset($name){ unset($this->$name); }
  
  /**
   * Llamada de consulta del objeto como un array.
   * $this[$name]
   **/
  public function offsetGet($name){ return $this->$name; }

  /**
   * Mueve el puntero al primer elemento de la collecion
   * rewind($this)
   **/
  public function rewind() {
    reset($this->_f);
    return $this;
  }
  
  /**
   * Obtiene el valor actual de la coleccion
   * current($this)
   **/
  public function current() {
    $name = $this->key();
    return $this->$name;
  }

  /**
   * Mueve el puntero de la collecion al ultimo elemento
   * end($this)
   **/
  public function end() {
    end($this->_f);
    return $this;
  }

  /**
   * Obtiene el indice del la posicion actual de la coleccion
   * key($this)
   **/
  public function key() {
    return current($this->_f);
  }

  /**
   * Mueve el puntero de la coleccion al siguiente elemento
   * next($this)
   **/
  public function next() {
    next($this->_f);
    return $this;
  }

  /**
   * Mueve el puntero de la coleccion al elemento previo
   * prev($this)
   **/
  public function prev() {
    prev($this->_f);
    return $this;
   }

  /**
   * Indica si el elemento actual de la collecion es valido
   * isValid($this)
   **/
  public function valid() {
    $field = $this->key();
    return isset($this->$field);
  }

  /**
   * Devuelve el numero de objetos de la coleccion
   * count($this)
   **/
  public function count() {
    return count($this->_f);
  }
  
  /**
   * Devuelve un array asociativo con los valores de los atributos dinamicos
   */
  public function toArray(){
    $ret = array();
    foreach($this->_f as $field){
      if(property_exists($this, $field)){
        $ret[$field] = $this->$field;
      }
    }
    return $ret;
  }
  
  /**
   * Convierte un AmObject a un array
  */
  public static function parse($collection){

    if(is_array($collection)){
      return $collection;
    }

    if($collection instanceof stdClass){
      return (array)$collection;
    }
    
    if($collection instanceof AmObject){
      return $collection->toArray();
    }
    
    return array();
    
  }
  
}
