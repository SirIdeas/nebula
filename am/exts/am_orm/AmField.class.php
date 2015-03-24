<?php

/**
 * Clase para los campos de las tablas de las BD
 */

class AmField extends AmObject{

  protected static
    $PARSE_FUNCS = array(
      // Enteros
      "tinyint"    => "intval",
      "smallint"   => "intval",
      "mediumint"  => "intval",
      "int"        => "intval",
      "bigint"     => "intval",
      // Flotantes
      "decimal"    => "floatval",
      "float"      => "floatval",
      "double"     => "floatval",
      "real"       => "floatval",
      // Cadenas de caracteres
      "char"       => "strval",
      "varchar"    => "strval",
      "tinytext"   => "strval",
      "text"       => "strval",
      "mediumtext" => "strval",
      "longtext"   => "strval",
      // Fechas
      "date"       => "strval",
      "datetime"   => "strval",
      "timestamp"  => "strval",
      "time"       => "strval",
      "year"       => "strval",
    );

  // Propiedades del campo
  protected
    $name = null,             // Nombre
    $type = null,             // Tipo de datos
    $charLenght = null,       // Tamanio máximo para campos tipo cadena
    $floatPrecision = null,   // Presición para el caso de campos tipo punto flotantes
    $defaultValue = null,     // Valor por defecto
    $notNull = false,         // Indica si se admite o no valores nulos
    $autoIncrement = false,   // Indica si es un campo autoincrementable
    $extra = null,            // Atributos extras
    $charset = null,          // Set de caracteres
    $collage = null,          // Coleccion de caracteres
    $primaryKey = false;      // Indica si es o no una clave primaria

    // Constructor de la calse
    public function __construct($params = null) {
      parent::__construct($params);

      $params = array_merge(array(
        "notNull" => null,
        "extra" => null,
        "primaryKey" => null,
      ), AmObject::parse($params));

      // Verdadera asignación para la propiedad notNull
      $this->notNull = in_array($params["notNull"], array(true, 1, "true", "1"));

      // Verdadera asiignación para la propiedad autoIncrement
      $this->autoIncrement = $this->getAutoIncrement() || preg_match("/auto_increment/", $params["extra"]) != 0;

      // Verdadera asignación para la propiedad extra
      $this->extra = str_replace("auto_increment", "", $params["extra"]);
      
      // Verdadera asignación para la propiedad primaryKey
      $this->setPrimaryKey($params["primaryKey"] === true || $params["primaryKey"] == 1 || $params["primaryKey"] == "true");
        
    }

    // Métodos get para las propiedades del campo
    public function getName(){ return $this->name;}
    public function getType(){ return $this->type; }
    public function getCharLenght(){ return $this->charLenght; }
    public function getFloatPrecision(){ return $this->floatPrecision; }
    public function getDefaultValue(){ return $this->defaultValue; }
    public function getNotNull(){ return $this->notNull; }
    public function getAutoIncrement(){ return $this->autoIncrement; }
    public function getExtra(){ return $this->extra; }
    public function getCharset(){ return $this->charset; }
    public function getCollage(){ return $this->collage; }
    public function getPrimaryKey(){ return $this->primaryKey; }
    
    // Métodos set para algunas propiedades
    public function setPrimaryKey($value){ $this->primaryKey = $value; return $this; }

    // Convertir campo en array
    public function toArray(){
        
      return array(
        "name" => $this->getName(),
        "type" => $this->getType(),
        "charLenght" => $this->getCharLenght(),
        "floatPrecision" => $this->getFloatPrecision(),
        "defaultValue" => $this->getDefaultValue(),
        "notNull" => $this->getNotNull(),
        "autoIncrement" => $this->getAutoIncrement(),
        "extra" => $this->getExtra(),
        "charset" => $this->getCharset(),
        "collage" => $this->getCollage(),
        "primaryKey" => $this->getPrimaryKey(),
      );
        
    }

    // Realizar casting a un valor por el tipo de datos del campo
    public function parseValue($value){
      $fn = self::$PARSE_FUNCS[$this->getType()];
      return $fn($value);
    }

}
