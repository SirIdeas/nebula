<?php

/**
 * Clase para generar clases de los modeles del ORM
 */

final class AmGenerator{

  // Generar clase base para una tabla de la BD
  public final static function classTableBase(AmTable $table){

    $nullValidators = array();
    $emptyValidators = array();
    $uniqueValidators = array();
    $strlenValidators = array();
    $integerValidators = array();
    $floatValidators = array();
    $dateValidators = array();
    $timeValidators = array();
    $datetimeValidators = array();
    $relationsValidators = array();

    // Agregar validators
    foreach($table->getFields() as $f){

      if(!$f->getAutoIncrement()){

        // Agregar validador de campo unico para los primary keys
        if(count($table->getPks()) == 1 && $f->getPrimaryKey()){
          $uniqueValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"unique\", \"unique\");";
        }
        
        $type = $f->getType();
        
        // Agregar validator para el tipo de datos seleccionado
        switch ($type){

          case "string": 
            $len = $f->getCharLenght();
            if(isset($len)){
              $strlenValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"max_length\", \"max_length\", array(\"max\" => $len));";
            }
            // $emptyValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"empty\", \"empty\");";
            break;

          case "integer": 
          case "biginteger": 
            $integerValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"integer\", \"integer\");";
            break;

          case "float": 
            $floatValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"float\", \"float\");";
            break;

          case "date": 
            $dateValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"date\", \"date\");";
            break;

          case "time": 
    //        $timeValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"time\", \"time\");";
    //        break;

          case "datetime": 
    //        $datetimeValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"datetime\", \"datetime\");";
    //        break;

          default:
            if($f->getNotNull()){
              $nullValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"null\", \"null\");";
            }

        }
      }

    }

    // Agregar validators de de referencias
    foreach($table->getReferencesTo() as $r){

      $cols = $r->getColumns();

      if(count($cols) == 1){

        $colName = array_keys($cols);
        $f = $table->getField($colName[0]);

        if($f->getNotNull()){

          $relationsValidators[] = "\$this->setValidator(\"{$f->getName()}\", \"fk_{$r->getTable()}\", \"in_query\", array(\"query\" => AmORM::table(\"{$r->getTable()}\", \"{$table->getSource()->getName()}\")->all(), \"field\" => \"{$cols[$colName[0]]}\"));";

        }

      }

    }

    // Impresion de la clase
    ob_start();

    echo "abstract class {$table->getClassNameTableBase()} extends AmTable{";

    echo "\n\n  final public function __construct(){";
    echo "\n\n    parent::__construct(array(\"source\" => \"{$table->getSource()->getName()}\", \"tableName\" => \"{$table->getTableName()}\"));";
    echo "\n\n  }";

    echo "\n\n  public function initialize(){";

    echo "\n\n    // UNIQUE\n    ";
    echo implode("\n    ", $uniqueValidators);

    echo "\n\n    // NOT NULLS\n    ";
    echo implode("\n    ", $nullValidators);

    echo "\n\n    // EMPTYS\n    ";
    echo implode("\n    ", $emptyValidators);

    echo "\n\n    // INTEGERS\n    ";
    echo implode("\n    ", $integerValidators);

    echo "\n\n    // FLOATS\n    ";
    echo implode("\n    ", $floatValidators);

    echo "\n\n    // DATE\n    ";
    echo implode("\n    ", $dateValidators);

    echo "\n\n    // TIME\n    ";
    echo implode("\n    ", $timeValidators);

    echo "\n\n    // DATE TIME\n    ";
    echo implode("\n    ", $datetimeValidators);

    echo "\n\n    // STRING LENGTH\n    ";
    echo implode("\n    ", $strlenValidators);

    echo "\n\n    // RELATIONS\n    ";
    echo implode("\n    ", $relationsValidators);

    echo "\n\n  }";

    echo "\n\n  public static function me(){";
    echo "\n\n    return AmORM::table(\"{$table->getTableName()}\", \"{$table->getSource()->getName()}\");";
    echo "\n\n  }";

    echo "\n\n}\n";

    return ob_get_clean();

  }

  // Generar clase base para un model
  public final static function classModelBase(AmTable $table){

    $newMethods = get_class_methods("AmModel");

    $fields = array_keys((array)$table->getFields());

    $fieldMethods = array();
    $getFieldMethods = array();
    $setFieldMethods = array();
    $hasManyMethods = array();
    $hasOneMethods = array();

    // Agregar métodos GET para cada campos
    foreach($fields as $attr){

      $methodName = "get_{$attr}";

      $prefix = in_array($methodName, $newMethods)? "//" : "";
      $newMethods[] = $methodName;
      $getFieldMethods[] = "{$prefix}public function get_$attr(){ return \$this->$attr; }";

    }

    // Agregar métodos SET para cada campo
    foreach($fields as $attr){

      $methodName = "set_{$attr}";

      $prefix = in_array($methodName, $newMethods)? "//" : "";
      $newMethods[] = $methodName;
      $setFieldMethods[] = "{$prefix}public function set_$attr(\$value){ \$this->$attr = \$value; return \$this; }";

    }

    // Agregar metodos para referencias de este modelo
    foreach(array_keys((array)$table->getReferencesBy()) as $relation){

      $prefix = in_array($relation, $newMethods)? "//" : "";
      $newMethods[] = $relation;
      $hasManyMethods[] = "{$prefix}public function $relation(){ return \$this->getTable()->getReferencesTo()->{$relation}->getQuery(\$this); }";

    }

    // Agregar metodos para referencias a este modelo
    foreach(array_keys((array)$table->getReferencesTo()) as $relation){

      $prefix = in_array($relation, $newMethods)? "//" : "";
      $newMethods[] = $relation;
      $hasOneMethods[] = "{$prefix}public function $relation(){ return \$this->getTable()->getReferencesTo()->{$relation}->getQuery(\$this)->getRow(); }";

    }

    // Generar clase Base
    ob_start();

    echo "abstract class {$table->getClassNameModelBase()} extends AmModel{";

    echo "\n\n    final public function __construct(\$params = array()){\n";
    echo "\n        parent::__construct(array_merge(\$params, array(";
    echo "\n          \"source\" => \"{$table->getSource()->getName()}\",";
    echo "\n          \"tableName\" => \"{$table->getTableName()}\",";
    echo "\n        )));";
    echo "\n\n    }";

    // Preparacion de los metodos
    echo "\n\n    // FIELDS\n    ";
    echo implode("\n    ", $fieldMethods);

    // Preparacion de los metodos Get
    echo "\n\n    // GETTERS\n    ";
    echo implode("\n    ", $getFieldMethods);

    // Preparacion de los metodos Set
    echo "\n\n    // GETTERS\n    ";
    echo implode("\n    ", $setFieldMethods);

    // Has Many
    echo "\n\n    // HAS MANY\n    ";
    echo implode("\n    ", $hasManyMethods);

    // Has One
    echo "\n\n    // HAS ONE\n    ";
    echo implode("\n    ", $hasOneMethods);

    echo "\n\n}\n";

    return ob_get_clean();

  }

}