<?php

/**
 * Fuente de datos para MySQL
 */

class MysqlSource extends AmSource{
  
  // Puerto por defecto para la conexion
  const DEFAULT_PORT = 3306;

  // Equivalencias entre los tipos de datos del Gesto de BD y el Lenguaje de programacion
  protected static

    $TYPES = array(
      // Enteros
      "tinyint"    => "tinyint",     //                -128, 127
      "smallint"   => "smallint",    //              -32768, 32767
      "mediumint"  => "mediumint",   //            -8388608, 8388607
      "int"        => "int",         //         -2147483648, 2147483647
      "bigint"     => "bigint",      //-9223372036854775808, 9223372036854775807
      // Flotantes
      "decimal"    => "decimal",
      "float"      => "float",
      "double"     => "double",
      "real"       => "double",
      // Cadenas de caracteres
      "char"       => "char",       // Longuitud exacta
      "varchar"    => "varchar",    // Longuitud maxima parametrizada
      "tinytext"   => "tinytext",   // Longuitud maxima 255 
      "text"       => "text",       // Longuitud maxima 65535
      "mediumtext" => "mediumtext", // Longuitud maxima 16777215
      "longtext"   => "longtext",   // Longuitud maxima 4294967295
      // Fechas
      "date"       => "date",
      "datetime"   => "datetime",
      "timestamp"  => "timestamp",
      "time"       => "time",
      "year"       => "year",
    );

  // Propiedades propias para el Driver
  protected
    $handle = null; // Identificador de la conexion

  // Obtener el puerto por defecto
  public function getDefaultPort(){
    return self::DEFAULT_PORT;
  }

  // Crear una conexión
  protected function initConnect(){
    return $this->handle = mysql_connect(
      $this->getServerString(),
      $this->getUser(),
      $this->getPass(),
      true
    );
  }
  
  // Cerrar una conexion
  public function close() {
    if($this->handle)
      return mysql_close($this->handle);
    return false;
  }

  // Obtener el número del último error generado en la conexión
  public function getErrNo(){
    if($this->handle)
      return mysql_errno($this->handle);
    return false;
  }
  
  // Obtener la descripcion del último error generado en la conexión
  public function getError(){
    if($this->handle)
      return mysql_error($this->handle);
    return false;
  }

  // Devuelve el tipo de datos del gestor para un tipo de datos en el lenguaje
  public function getTypeOf($type){
    // Si no se encuentra el tipo se retorna el tipo recibido
    return isset(self::$TYPES[$type])? self::$TYPES[$type] : $type;
  }

  // Obtener el siguiente registro de un resultado
  public function getFetchAssoc($result){
    return mysql_fetch_assoc($result);
  }
  
  // Obtener el ID del ultimo registro insertado
  public function getLastInsertedId(){
    return mysql_insert_id();
  }

  // Realizar una consulta SQL
  protected function query($sql){

    if($this->handle){
      return mysql_query($sql, $this->handle);
    }
    return false;
  }

  // Devuelve un nombre entre comillas simples entendibles por el gesto
  public function getParseName($identifier){
    if(preg_match("/[`\\.]/", $identifier))
      return $identifier;
    return "`$identifier`";
  }

  // Set de Caracteres
  public function sqlCharset($charset = null){
    
    // Si no recibió argumentos obtener el charset de la BD
    if(!count(func_get_args())>0)
      $charset = $this->getCharset();
    
    // El el argumento esta vacío retornar cadena vacia
    if(empty($charset))
      return "";

    $charset = empty($charset) ? "" : " CHARACTER SET {$charset}";

    return $charset;

  }

  // Coleccion de caracteres
  public function sqlCollage(){

    // Si no recibió argumentos obtener el college de la BD
    if(!count(func_get_args())>0)
      $collage = $this->getCollage();
    
    // El el argumento esta vacío retornar cadena vacia
    if(empty($collage))
      return "";

    $collage = empty($collage) ? "" : " COLLATE {$collage}";

    return $collage;

  }

  // Setear un valor a una variable de servidor
  public function sqlSetServerVar($varName, $value){
    return "set {$varName}={$value}";
  }

  // SQL para crear la BD
  public function sqlCreate(){
    $database = $this->getParseNameDatabase();
    $charset = $this->sqlCharset();
    $collage = $this->sqlCollage();
    $sql = "CREATE DATABASE IF NOT EXISTS {$database}{$charset}{$collage}";
    return $sql;
  }

  // SQL para eliminar la BD
  public function sqlDrop(){
    $database = $this->getParseNameDatabase();
    $sql = "DROP DATABASE {$database}";
    return $sql;
  }
  
  // SQL para seleccionar la BD
  public function sqlSelectDatabase(){
    $database = $this->getParseNameDatabase();
    $sql = "USE {$database}";
    return $sql;
  }

  //OSQL par aobtener la informacion de la BD
  public function sqlGetInfo(){

    $sql = $this
      ->newQuery("information_schema.SCHEMATA", "s")
      ->selectAs("s.DEFAULT_CHARACTER_SET_NAME", "charset")
      ->selectAS("s.DEFAULT_COLLATION_NAME", "collage")
      ->where("SCHEMA_NAME='{$this->getDatabase()}'")
      ->sql();

    return $sql;
    
  }

  // SQL para obtener el listado de tablas
  public function sqlGetTables(){

    $sql = $this
      ->newQuery("information_schema.TABLES", "t")
      ->innerJoin("information_schema.COLLATION_CHARACTER_SET_APPLICABILITY", "t.TABLE_COLLATION = c.COLLATION_NAME", "c")
      ->selectAs("t.TABLE_NAME", "tableName")
      ->selectAS("t.ENGINE", "engine")
      ->selectAS("t.TABLE_COLLATION", "collage")
      ->selectAS("c.CHARACTER_SET_NAME ", "charset")
      ->where("TABLE_SCHEMA='{$this->getDatabase()}'", "and", "TABLE_TYPE='BASE TABLE'")
      ->sql();

    return $sql;
      
  }

  // SQL par obtener los primary keys de una tabla
  public function sqlGetTablePrimaryKeys(AmTable $t){
    
    $sql = $this
      ->newQuery("information_schema.KEY_COLUMN_USAGE")
      ->selectAs("COLUMN_NAME", "name")
      ->where("TABLE_SCHEMA='{$this->getDatabase()}'", "and", "TABLE_NAME='{$t->getTableName()}'", "and", "CONSTRAINT_NAME='PRIMARY'")
      ->orderBy("ORDINAL_POSITION")
      ->sql();
    
    return $sql;

  }

  // SQL para obtener el listado de columnas de una tabla
  public function sqlGetTableColumns(AmTable $t){
    
    $sql = $this
      ->newQuery("information_schema.COLUMNS")
      ->selectAs("COLUMN_NAME", "name")
      ->selectAs("DATA_TYPE", "type")
      ->selectAs("CHARACTER_MAXIMUM_LENGTH", "charLenght")
      ->selectAs("NUMERIC_PRECISION", "floatPrecision")
      ->selectAs("IS_NULLABLE <> 'NO'", "notNull")
      ->selectAs("COLUMN_DEFAULT", "defaultValue")
      ->selectAs("COLLATION_NAME", "collage")
      ->selectAs("CHARACTER_SET_NAME", "charset")
      ->selectAs("EXTRA", "extra")
      ->where("TABLE_SCHEMA='{$this->getDatabase()}'", "and", "TABLE_NAME='{$t->getTableName()}'")
      ->orderBy("ORDINAL_POSITION")
      ->sql();
    
    return $sql;
    
  }
  
  // SQL para obtener el listade de foreign keys de una tabla
  public function sqlGetTableForeignKeys(AmTable $t){
      
    $sql = $this
      ->newQuery("information_schema.KEY_COLUMN_USAGE")
      ->selectAs("CONSTRAINT_NAME", "name")
      ->selectAs("COLUMN_NAME", "columnName")
      ->selectAs("REFERENCED_TABLE_NAME", "toTable")
      ->selectAs("REFERENCED_COLUMN_NAME", "toColumn")
      ->where("TABLE_SCHEMA='{$this->getDatabase()}'", "and", "TABLE_NAME='{$t->getTableName()}'", "and", "CONSTRAINT_NAME<>'PRIMARY'", "and", "REFERENCED_TABLE_SCHEMA=TABLE_SCHEMA")
      ->orderBy("CONSTRAINT_NAME", "ORDINAL_POSITION")
      ->sql();
        
    return $sql;

  }
  
  // SQL para obtener el lista de de referencias a una tabla
  public function sqlGetTableReferences(AmTable $t){
      
    $sql = $this
      ->newQuery("information_schema.KEY_COLUMN_USAGE")
      ->selectAs("CONSTRAINT_NAME", "name")
      ->selectAs("COLUMN_NAME", "columnName")
      ->selectAs("TABLE_NAME", "fromTable")
      ->selectAs("REFERENCED_COLUMN_NAME", "toColumn")
      ->where("TABLE_SCHEMA='{$this->getDatabase()}'", "and", "REFERENCED_TABLE_NAME='{$t->getTableName()}'", "and", "CONSTRAINT_NAME<>'PRIMARY'", "and", "REFERENCED_TABLE_SCHEMA=TABLE_SCHEMA")
      ->orderBy("CONSTRAINT_NAME", "ORDINAL_POSITION")
      ->sql();
    
    return $sql;
      
  }

  // Obtener el SQL para la clausula SELECT
  public function sqlSelect(AmQuery $q, $with = true){

    $selectsOri = $q->getSelects();  // Obtener argmuentos en la clausula SELECT
    $distinct = $q->getDistinct();
    $selects = array();  // Lista de retorno

    // Recorrer argumentos del SELECT
    foreach($selectsOri as $as => $field){

      // Si es una consulta se incierra entre parentesis
      if($field instanceof AmQuery)
        $field = "({$field->sql()})";
      
      // Agregar parametro AS
      $selects[] = AmORM::isNameValid($as) ? "$field AS '$as'" : (string)$field;

    }

    // Unir campos
    $selects = implode(", ", $selects);

    // Si no se seleccionó ningun campo entonces se tomaran todos
    $selects = (empty($selects) ? "*" : $selects);

    // Agregar SELECT
    return trim(($with ? "SELECT ".($distinct ? "DISTINCT " : "") : "").$selects);

  }

  // Obtener el SQL para la clausula FROM
  public function sqlFrom(AmQuery $q, $with = true){
      
    $fromsOri = $q->getFroms();  // Listado de argumentos de la clausula FROM 
    $froms = array();   // Listado de retorno
    
    // Recorrer lista del FROM
    foreach($fromsOri as $as => $from){
            
      if($from instanceof AmQuery){
        // Si es una consulta se encierra en parentesis
        $from = "({$from->sql()})";
      }elseif($from instanceof AmTable){
        // Si es una tabla se concatena el nombre de la BD y el de la tabla
        $from = $this->getParseNameTable($from->getTableName());
      }elseif(AmORM::isNameValid($from)){
        // Si es una tabla se concatena el nombre de la BD y el de la tabla como strin
        $from = $this->getParseNameTable($from);
      }elseif(false !== (preg_match("/^([a-zA-Z_][a-zA-Z0-9_]*)\.([a-zA-Z_][a-zA-Z0-9_]*)$/", $from, $matches)!= 0)){
        // Dividir por el punto
        $from = $this->getParseName($matches[1]).".".$this->getParseName($matches[2]);
      }elseif(is_string($from)){
        $from = $from = "($from)";
      }
            
      // Agregar parametro AS
      $froms[] = AmORM::isNameValid($as) ? "$from AS $as" : $from;
            
    }
        
    // Unir argumentos procesados      
    $froms = implode(", ", $froms);
    
    // Agregar FROM
    return trim(empty($froms) ? "" : (($with ? "FROM " : "").$froms));

  }

  // Obtener el SQL para una condicion IN
  public static function in($field, $collection){
    
    // Si es un array se debe preparar la condició
    if(is_array($collection)){
        
        // Filtrar elementos repetidos
        $collection = array_filter($collection);
        
        // Si no esta vacía la colecion
        if(!empty($collection)){
          
          // Agregar cadenas dentro de los comillas simple
          $func = create_function('$c', 'return is_numeric($c) ? $c : "\'$c\'";');
          $collection = array_map($func, array_values($collection));

          // Unir colecion por comas
          $collection = implode($collection, ",");
            
        }else{
          // Si es una colecion vacía
          $collection = null;
        }
        
    }elseif($collection instanceof AmQuery){

      // Si es una consulta entonces se obtiene el SQL
      $collection = $collection->sql();

    }
    
    // Agregar el comando IN
    return isset($collection) ? "$field IN($collection)" : "false";
      
  }

  // Helper para obtener el SQL de la clausula WHERE
  protected function parseWhere($condition, $prefix = null, $isIn = false){

    if($isIn){
      
      // Es una condicion IN
      $condition = self::in($condition[0], $condition[1]);

    }elseif(is_array($condition)){

      $str = "";
      $lastUnion = "";

      // Recorrer condiciones
      foreach($condition as $c){

        // Obtener siguiente condicion
        $next = $this->parseWhere($c["condition"], $c["prefix"], $c["isIn"]);

        // Es la primera condicion
        if(empty($str)){
          $str = $next;
        }else{

          // Si el operador de union es igual al anterior o no hay una anterior
          if($c["union"] == $lastUnion || empty($lastUnion)){
            $str = "$str {$c["union"]} $next";
          }else{
            // Cuando cambia el operador de union se debe agregar la condicion anterior
            // entre parentesis
            $str = "($str) {$c["union"]} $next";
          }

          // guardar para la siguiente condicion
          $lastUnion = $c["union"];

        }

      }

      // Agregar parentesis a la condicion
      $condition = empty($str) ? "" : "($str)";

    }

    // Eliminar espacios al principio y al final
    $condition = trim($condition);

    // Agregar el prefix (NOT) si existe
    return empty($condition) ? "" : trim($prefix." ".$condition);

  }

  // Obtener SQL para la clausula WHERE de una consulta
  public function sqlWhere(AmQuery $q, $with = true){
    $where = $this->parseWhere($q->getWheres());  
    return trim(empty($where) ? "" : (($with ? "WHERE " : "").$where));
  }

  // Obtener el SQL para la clausula JOIN de una consulta
  public function sqlJoins(AmQuery $q){

    // Resultado
    $joinsOri = $q->getJoins();
    $joinsResult = array();
    
    //Recorrer cada tipo de join
    foreach($joinsOri as $type => $joins){
      // Recorrer cada join
      foreach($joins as $join){
          
          // Declarar posiciones del array como variables
          // Define $on, $as y $table
          extract($join);

          // Eliminar espacios iniciales y finales
          $on = trim($on);
          $as = trim($as);
          
          // Si los parametros quedan vacios
          if(!empty($on)) $on = " ON $on";
          if(!empty($as)) $as = " AS $as";

          if($table instanceof AmQuery){
            // Si es una consulta insertar SQL dentro de parenteris
            $table = "({$table->sql()})";
          }elseif($table instanceof AmTable){
            // Si es una tabla obtener el nombre
            $table = $table->getTableName();
          }

          // Agrgar parte de join
          $joinsResult[] = " $type JOIN $table$as$on";
          
          // Liberar variables
          unset($table, $as, $on);

      }
    }

    // Unir todas las partes
    return trim(implode(" ", $joinsResult));

  }

  // Obtener el SQL de una clasula ORDER BY
  public function sqlOrders(AmQuery $q, $with = true){

    $ordersOri = $q->getOrders(); // Obtener orders agregados
    $orders = array();  // Orders para retorno

    // Recorrer lista de campos para ordenar
    foreach($ordersOri as $order => $dir){
      $orders[] = "$order $dir";
    }
    
    // Unir resultado
    $orders = implode(", ", $orders);
    
    // Agregar ORDER BY
    return trim(empty($orders) ? "" : (($with ? "ORDER BY " : "").$orders));

  }

  // Obtener el SQL de una clasula GROUP BY
  public function sqlGroups(AmQuery $q, $with = true){

    // Unir grupos
    $groups = implode(", ", $q->getGroups());

    // Agregar GROUP BY
    return trim(empty($groups) ? "" : (($with ? "GROUP BY " : "").$groups));

  }

  // Obtener SQL para la clausula LIMIT
  public function sqlLimit(AmQuery $q, $with = true){

    // Obtener limite
    $limit = $q->getLimit();

    // Agregar LIMIT
    return trim(!isset($limit) ? "" : (($with ? "LIMIT " : "").$limit));

  }

  // Obtener SQL para la clausula OFFSET
  public function sqlOffset(AmQuery $q, $with = true){

    // Obtener punto de partida
    $offset = $q->getOffset();

    // Agregar OFFSET
    return trim(!isset($offset) ? "" : (($with ? "OFFSET " : "").$offset));

  }

  // Obtener el SQL para la clausula SET de una consulta UPDATE
  public function sqlSets(AmQuery $q, $with = true){

    // Obtener sets
    $setsOri = $q->getSets();
    $sets = array(); // Lista para retorno

    // Recorrer los sets
    foreach($setsOri as $set){
        
      $value = $set["value"];
      
      // Acrear asignacion
      if($value === null){
        $sets[] = "{$set["field"]} = NULL";
      }elseif($set["const"] === true){
        $sets[] = "{$set["field"]} = " . $this->realScapeString($value);
      }elseif($set["const"] === false){
        $sets[] = "{$set["field"]} = $value";
      }
        
    }
    
    // Unir resultado
    $sets = implode(",", $sets);

    // Agregar SET
    return ($with? "SET " : "") . $sets;

  }

  // Obtener el SQL para una consulta UPDATE
  public function sqlUpdate(AmQuery $q){
        
    return implode(" ", array(
      "UPDATE",
      trim($q->sqlFrom(false)),
      trim($q->sqlJoins()),
      trim($q->sqlSets()),
      trim($q->sqlWhere())
    ));

  }

  // Obtener el SQL para una consulta DELETE
  public function sqlDelete(AmQuery $q){
      
    // Obtener el nombre de la tabla
    $tableName = $this->getParseNameTable($q->getTable());
    
    // Agregar DELETE FROM
    return implode(" ", array(
      "DELETE FROM",
      trim($tableName),
      trim($q->sqlWhere())
    ));

  }

  // Obtener el SQL para una consulta de insercon
  public function sqlInsertInto($table = null, $values = array(), array $fields = array()){
    
    // Si es una consulta
    if($values instanceof AmQuery){

      // Los valores a insertar son el SQL de la consulta
      $strValues = $values->sql();

    // Si los valores es un array con al menos un registro
    }elseif(is_array($values) && count($values)>0){

      // Preparar registros para crear SQL
      foreach($values as $i => $v)
        // Unir todos los valores con una c
        $values[$i] = "(" . implode(",", $values[$i]) . ")";

      // Unir todos los registros
      $values = implode(",", $values);

      // Obtener Str para los valores
      $strValues = "VALUES $values";

    }
      
    // Si el Str de valores no está vacío
    if(!empty($strValues)){

      // Obtener nombre de la tabla
      $tableName = $this->getParseNameTable($table);

      // Obtener el listado de campos
      foreach ($fields as $key => $field)
        $fields[$key] = $this->getParseName($field);

      // Unir campos
      $fields = implode(",", $fields);
      
      // Generar SQL
      return "INSERT INTO $tableName($fields) $strValues";
        
    }
    
    // Consulta invalida
    return "";
      
  }

  // Devuelve una cadena con un valor valido en el gesto de BD
  public function realScapeString($value){
    $value = mysql_real_escape_string($value);
    // Si no tiene valor asignar NULL
    return isset($value)? "'$value'" : "NULL";
  }

  // Obtener el SQL de la consulta
  public function sql(AmQuery $q){

    return !empty($q->sql) ? $q->sql :
      trim(implode(" ", array(
      trim($q->sqlSelect(true)),
      trim($q->sqlFrom(true)),
      trim($q->sqlJoins()),
      trim($q->sqlWhere(true)),
      trim($q->sqlGroups(true)),
      trim($q->sqlOrders(true)),
      trim($q->sqlLimit(true)),
      trim($q->sqlOffSet(true))
    )));

  }

  // Obtener el SQL para un campo de una tabla al momento de crear la tabla
  public function sqlField(AmField $field){

    // Preparar las propiedades  
    $name = $this->getParseName($field->getName());
    $type = array_search(self::$TYPES, $field->getType());
    if(!$type) $type = $field->getType();
    $lenght = $field->getCharLenght();
    $lenght = !empty($lenght) ? "({$lenght})" : "";
    $notNull = $field->getNotNull() ? " NOT NULL" : "";
    $charset = $this->sqlCharset($field->getCharset());
    $collage = $this->sqlCollage($field->getCollage());

    $default = $field->getDefaultValue();
    $default = $default === null ? "" : " DEFAULT '{$default}'";
    
    $autoIncrement = $field->getAutoIncrement() ? " AUTO_INCREMENT" : "";
    
    return "$name$type$lenght$autoIncrement$charset$collage$notNull$default";

  }

  // Obtener el SQL para crear una tabla
  public function sqlCreateTable(AmTable $t){
      
    // Obtener nombre de la tabla
    $tableName = $this->getParseNameTable($t->getTableName());

    // Lista de campos
    $fields = array();
    $realFields = $t->getFields();

    // Obtener el SQL para cada camppo
    foreach($realFields as $field)
      $fields[] = $this->sqlField($field);
      
    // Obtener los nombres de los primary keys
    $pks = $t->getPks();
    foreach($pks as $offset => $pk)
      $pks[$offset] = $this->getParseName($t->getField($pk)->getName());

    // Preparar otras propiedades
    $engine = empty($t->engine) ? "" : "ENGINE={$t->engine} ";        
    $charset = $this->sqlCharset($t->getCharset());
    $collage = $this->sqlCollage($t->getCollage());

    // Agregar los primaris key al final de los campos
    $fields[] = empty($pks) ? "" : "PRIMARY KEY (" . implode(", ", $pks). ")";
    
    // Unir los campos
    $fields = implode(", ", $fields);

    // Preparar el SQL final
    return "CREATE TABLE IF NOT EXISTS $tableName($fields)$engine$charset$collage;";

  }

  // Obtener el SQL para una consulta TRUNCATE: Vaciar una tabla
  public function sqlTruncate($table = null){
    
    // Obtener nombre de la tabla
    $tableName = $this->getParseNameTable($table);

    return "TRUNCATE $tableName";
    
  }

  // Obtener el SQL para eliminar una tabla
  public function sqlDropTable($table){
    
    // Obtener nombre de la tabla
    $tableName = $this->getParseNameTable($table);

    return "TRUNCATE $tableName";
    
  }


}
