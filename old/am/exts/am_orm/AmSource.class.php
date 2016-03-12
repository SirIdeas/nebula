<?php

/**
 * Abstraccion para las conexiones a las base de datos
 */

abstract class AmSource extends AmObject{

  protected static
    $ORM_FOLDER = "model";

  // Propiedades
  protected
    $name     = null,     // Nombre clave para la fuente. Se Asumo es unico
    $prefix   = null,     // Prefijo para las clases nacidas de esta fuente
    $driver   = null,     // Driver utilizado en la fuente
    $database = null,     // Nombre de la base de datos para conectarse
    $server   = null,     // Nombre del servidor
    $port     = null,     // Puerto de conexion
    $user     = null,     // Usuario para la conexion
    $pass     = null,     // Password para la conexion
    $charset  = null,     // Codificacion de caracteres
    $collage  = null,     // Colexion de caracteres
    $tables   = array(),  // Listado de instancias de tablas
    $tableNames = null;   // Listade de los nombres de la tabla de la BD

  // Reescribir constructor para leer la configuracion particular
  // de la fuente
  public function __construct($params = array()) {

    // Parchar los parametros
    $params = AmObject::parse($params);

    // Asignar solo el nombre
    parent::__construct(array(
      "name" => $params["name"]
    ));

    // Mezclar con los valores particulares de la fuente
    $params = array_merge($this->getConf(), $params);

    // Eliminar el nombre porque ya se asignó
    unset($params["name"]);
    
    // Llamar al constructor con los nuevos argumentos
    parent::__construct($params);

  }

  // El destructor del objeto deve cerrar la conexion
  public function __destruct() {
    $this->close();
  }

  // Métodos get para las propiedades principales
  public function getName(){ return $this->name; }
  public function getPrefix(){ return $this->prefix; }
  public function getDriver(){ return $this->driver; }
  public function getDatabase(){ return $this->database; }
  public function getServer(){ return $this->server; }
  public function getPort(){ return $this->port; }
  public function getUser(){ return $this->user; }
  public function getPass(){ return $this->pass; }
  public function getCharset(){ return $this->charset; }
  public function getCollage(){ return $this->collage; }
  public function getTables(){ return $this->tables; }
  public function getTableNames(){ return $this->tableNames; }

  // Obtener la instancia de una tabla
  public function getTable($table){

    // Si es una instancia de una tabla
    if($table instanceof AmTable)
      return $table;

    // Si ya existe la instancia de la tabla
    if($this->hasTableInstance($table))
      return $this->tables[$table];

    // Sino instanciar la tabla
    return AmORM::table($table, $this->getName());
  }

  // Indica si ya está cargada una instancia de las tablas
  public function hasTableInstance($table){
    return isset($this->tables[$table]);
  }

  // Nombre de las clases relacionadas a una tabla
  public function getClassNameTableBase($model){  return $this->getClassNameTable($model)."Base"; }
  public function getClassNameTable($model){      return $this->getClassNameModel($model)."Table"; }  
  public function getClassNameModelBase($model){  return $this->getClassNameModel($model)."Base"; }
  public function getClassNameModel($model){      return $this->getPrefix() . AmORM::camelCase($model, true); }

  // Obtener la ruta de la carpeta para las clases del ORM de la BD actual
  public function getFolder(){
    return self::getFolderOrm() . "/" . $this->getName();
  }

  // Retorna donde se guarda la configuración de la fuente
  public function getPathConf(){
    return $this->getFolder() . "/" . AmORM::underscor($this->getName()) . ".conf";
  }

  // Devuelve la configuracion particular de la fuente
  public function getConf(){
    $path = $this->getPathConf() . ".php";
    return AmCoder::read($path);
  }

  // Obtener la carpeta para un tabla
  public function getFolderModel($model){
    return $this->getFolder() . "/" . AmORM::underscor($model);
  }

  // Obtener la carpeta de archivos bases para un tabla
  public function getFolderModelBase($model){
    return $this->getFolderModel($model) . "/base";
  }

  // Devuelve la direccion del archivo de configuracion
  public function getPathConfToModel($model){
    return $this->getFolderModelBase($model) . "/". AmORM::underscor($model) .".conf";
  }

  // Devuelve la dirección de la clase de la tabla Base
  public function getPathClassTableBase($model){
    return $this->getFolderModelBase($model) . "/". $this->getClassNameTableBase($model) .".class";
  }

  // Devuelve la dirección de la clase de la tabla
  public function getPathClassTable($model){
    return $this->getFolderModel($model) . "/". $this->getClassNameTable($model) .".class";
  }

  // Devuelve la dirección de la clase del model Base
  public function getPathClassModelBase($model){
    return $this->getFolderModelBase($model) . "/". $this->getClassNameModelBase($model) .".class";
  }

  // Devuelve la dirección de la clase del model
  public function getPathClassModel($model){
    return $this->getFolderModel($model) . "/". $this->getClassNameModel($model) .".class";
  }

  // Inidic si todas las clases y archivos de un model existes
  public function existsModel($model){
    return is_file($this->getPathConfToModel($model) . ".php")
        && is_file($this->getPathClassTableBase($model) . ".php")
        && is_file($this->getPathClassTable($model) . ".php")
        && is_file($this->getPathClassModelBase($model) . ".php")
        && is_file($this->getPathClassModel($model) . ".php");
  }

  // Obtener la configuracion del archivo de configuracion propio de un model
  public function getTableConf($model){
    return AmCoder::decode($this->getPathConfToModel($model).".php");
  }

  // Crea el archivo de configuracion para una fuente
  public function createFileConf($rw = false){

    // Obtener de el nombre del archivo destino
    $path = $this->getPathConf() . ".php";
    if(!is_file($path) || $rw){
      AmCoder::write($path, $this->toArray());
      return true;
    }
    return false;
  }

  // Crear carpetas de todas las tablas de la BD
  public function mkdirModel(){
    
    $ret = array(); // Para retorno

    $tables = $this->newQuery($this->sqlGetTables())
                   ->getCol("tableName");

    foreach ($tables as $t){
      // Obtener instancia de la tabla
      $table = $this->describeTable($t);
      // Crear modelo
      $ret[$t] = $table->mkdirModel();
    }

    return $ret;

  }

  // Metodo para crear todos los modelos de la BD
  public function createClassModels(){

     // Para retorno
    $ret = array(
      "source" => $this->createFileConf(),
      "tables" => array(),
    );

    $tables = $this->newQuery($this->sqlGetTables())
                   ->getCol("tableName");

    foreach ($tables as $t){
      // Obtener instancia de la tabla
      $table = $this->describeTable($t);
      // Crear modelo
      $ret["tables"][$t] = $table->createClassModels();
    }

    return $ret;

  }

  // Creaa todas las tablas de la BD
  public function createTables(){
    
    $ret = array(); // Para el retorno

    // Obtener los nombres de la tabla en el archivo
    $tablesNames = $this->getTableNames();

    // Recorrer cada tabla generar crear la tabla
    foreach ($tablesNames as $tableName)
      // Crear la tabla
      $ret[$tableName] = $this->createTableIfNotExists($tableName);

    return $ret;

  }

  // Setear la instancia de una tabla
  public function setTable($offset, AmTable $t){
    $this->tables[$offset] = $t;
    return $this;
  }

  // Realiza la conexión
  public function connect(){
    $ret = $this->initConnect();

    // Cambiar la condificacion con la que se trabajará
    if($ret){
      $this->setServerVar("character_set_server", $this->realScapeString($this->getCharset()));
      // REVISAR
      $this->execute("set names 'utf8'");
    }
    
    return $ret;
  }

  // Función para reconectar
  public function reconnect(){
    $this->disconnect();      // Desconectar
    return $this->connect();  // Volver a conectar
  }

  // Devuelve la cadena de conexión del servidor
  public function getServerString(){
    
    $port = $this->getPort();
    $defPort = $this->getDefaultPort();

    return $this->getServer() . ":" . (!empty($port) ? $port : $defPort);

  }

  // Seleccionar la base de datos
  public function select(){
    return $this->query($this->sqlSelectDatabase());
  }

  // Indica si la BD existe
  public function exists(){
    return $this->select();
  }

  // Devuelve el nombre de la BD para ser reconocida en el gestor de BD
  public function getParseNameDatabase(){
    return $this->getParseName($this->getDatabase());
  }

  // Devuelve el nombre de una tabla para ser reconocida en el gestor de BD
  public function getParseNameTable($table, $only = false){
    
    // Obtenerl solo el nombre de la tabla
    $table = $table instanceof AmTable? $table->getTableName() : $table;
    $table = $this->getParseName($table);

    // Si se desea obtener solo el nombre
    if($only)
      return $table;

    // Retornar el nombre de la tabla con la BD
    return $this->getParseNameDatabase().".".$table;

  }

  // Ejecutar una consulta SQL desde el ámbito de la BD actual
  public function execute($sql){
    $this->select();
    return $this->query($sql);
  }

  // Crea una instancia de un query
  public function newQuery($from = null, $as = "q"){
    $q = new AmQuery(); // Crear instancia
    $q->setSource($this);  // Asignar fuente
    if(!empty($from)) $q->fromAs($from, $as);  // Asignar el from de la consulta
    return $q;

  }

  // Ejecuta un conjunto de consultas
  public function executeGroup(array $queries){
    $sqls = array();
    foreach ($queries as $key => $q)
      // Si es un query obtener el SQL
      if($q instanceof AmQuery)
        $sqls[] = $q->sql();
      else
        // Si no convetir a string
        $sqls[] = (string)$q;

    return $this->execute(implode(";", $sqls));
    
  }

  // Devuelve un array con el listado de tablas de la BD
  public function getTablesFromSchema(){
    return $this->newQuery($this->sqlGetTables())
                ->getResult("array");
  }

  // Devuelve un array con el listado de tablas
  public function getTableDescription($table){
    return $this->newQuery($this->sqlGetTables())
                ->where("tableName = '$table'")
                ->getRow("array");
  }

  // Devuelve la descripcion completa de una tabla
  // incluyendo los campos
  public function describeTable($tableName){
      
    // Obtener la descripcion basica
    $table = $this->getTableDescription($tableName);
    
    // Si no se encontró la tabla retornar falso
    if($table === false)
      return false;
      
    // Asignar fuente
    $table["source"] = $this;

    // Crear instancia anonima de la tabla
    $table = new AmTable($table);
    // Buscar la descripcion de sus campos y relaciones
    $table->describeTable();
    
    // Retornar tgabla
    return $table;
      
  }

  // Obtener un listado de los campos primarios de una tabla
  public function getTablePrimaryKey(AmTable $t){
        
    $ret = array(); // Valor de retorno

    // Obtener los campos primarios de la tabla
    $pks = $this->newQuery($this->sqlGetTablePrimaryKeys($t))->getResult("array");
    
    // Agregar campos al retorn
    foreach($pks as $pk)
      $ret[] = $pk["name"];
    
    return $ret;
      
  }

  // Obtener un listado de las columnas de una tabla
  public function getTableColumns(AmTable $t){
    return $this->newQuery($this->sqlGetTableColumns($t))->getResult("array");
  }

  // Obtener un listado de las columnas de una tabla
  public function getTableForeignKeys(AmTable $t){

    $ret = array(); // Para el retorno

    // Obtener el nombre de la fuente
    $sourceName = $this->getName();

    // Obtener los ForeignKeys
    $fks = $this->newQuery($this->sqlGetTableForeignKeys($t))->getResult("array");
        
    foreach($fks as $fk){
      
      // Dividir el nombre del FK
      $name = explode(".", $fk["name"]);

      // Obtener el ultimo elemento
      $name = array_pop($name);
      
      // Si no existe el elmento en el array se crea
      if(!isset($ret[$name])){
        $ret[$name] = array(
          "source" => $sourceName,
          "table" => $fk["toTable"],
          "columns" => array()
        );
      }
      
      // Agregar la columna a la lista de columnas
      $ret[$name]["columns"][$fk["columnName"]] = $fk["toColumn"];

    }
    
    return $ret;

  }

  // Obtener el listado de referencias a una tabla
  public function getTableReferences(AmTable $t){
    
    $ret = array(); // Para el retorno

    // Obtener el nombre de la fuente
    $sourceName = $this->getName();

    // Obtener las referencias a una tabla
    $fks = $this->newQuery($this->sqlGetTableReferences($t))->getResult("array");
    
    // Recorrer los FKs
    foreach($fks as $fk){
      
      // Dividir el nombre del FK
      $name = explode(".", $fk["name"]);

      // Obtener el ultimo elemento
      $name = array_shift($name);
      
      // Si no existe el elmento en el array se crea
      if(!isset($ret[$name])){
        $ret[$name] = array(
          "source" => $sourceName,
          "table" => $fk["fromTable"],
          "columns" => array()
        );
      }
      
      // Agregar la columna a la lista de columnas
      $ret[$name]["columns"][$fk["toColumn"]] = $fk["columnName"];

    }
    
    return $ret;
      
  }

  // Setea el valor de una variable en el gestor
  public function setServerVar($varName, $value){
    return false !== $this->execute($this->sqlSetServerVar($varName, $value));
  }

  // Crea la BD
  public function create(){
    return false !== $this->execute($this->sqlCreate());
  }

  // Elimina la BD
  public function drop(){
    return false !== $this->execute($this->sqlCreate());
  }

  // Obtener la información de la BD
  public function getInfo(){
    return $this->newQuery($this->sqlGetInfo())->getRow("array");
  }

  // Crear tabla
  public function createTable(AmTable $t){
    return false !== $this->execute($this->sqlCreateTable($t));
  }

  // Crea un tabla en la BD
  public function createTableIfNotExists($model){
    // Si el model existe
    if($this->existsModel($model)){

      // Obtener la instancia de la tabla
      $table = $this->getTable($model);
      // Obtener la instancia de la BD y
      // retornar si se pudo crear o no la tabla en la BD
      if(!$table->exists()){
        // Intentar crear la tabla
        if($table->create())
          return true;

        // Retornar error de MSYL
        return $this->getErrNo() . ": " . $this->getError();

      }

      // La tabla ya existe en la BD
      return 1;

    }
    return 0;
  }

  // Elimina la Base de datos
  public function dropTable(AmTable $t){
    return false !== $this->execute($this->sqlDropTable($t));
  }

  // Vaciar tabla
  public function truncate(AmTable $t, $ignoreFK = false){
    $sql = "";
    if($ignoreFK === true)
      $sql .= $this->setServerVar("FOREIGN_KEY_CHECKS", 0);
    $ret = $this->execute($this->sqlTruncate($t));
    if($ignoreFK === true)
      $sql .= $this->setServerVar("FOREIGN_KEY_CHECKS", 1);
    return false !== $ret;
  }

  // Indica si la tabla existe
  public function existsTable(AmTable $t){
    return false !== $this->getTableDescription($t->getTableName());
  }

  // Ejecuta una consulta de insercion para los
  public function insertInto($table, $values, array $fields = array()){

    // Obtener la instancia de la tabla
    $table = $this->getTable($table);

    // Agregar fechas de creacion y modificacion si existen en la tabla
    $table->setAutoCreatedAt($values);
    $table->setAutoUpdatedAt($values);

    if($values instanceof AmQuery){

      // Si los campos recibidos estan vacíos se tomará
      // como campos los de la consulta
      if(count($fields) == 0)
        $fields = array_keys($values->select());
    
    // Si los valores es un array con al menos un registro
    }elseif(is_array($values) && count($values)>0){

      // Indica si
      $mergeWithFields = count($fields) == 0;
      
      // Recorrer cada registro en $values par obtener los valores a insertar
      foreach($values as $i => $v){

        if($v instanceof AmModel)
          // Si el registro es AmModel obtener sus valores como array
          // asociativo o simple
          $values[$i] = $v->dataToArray(!$mergeWithFields);
        elseif($v instanceof AmObject)
          // Si es una instancia de AmObjet se obtiene como array asociativo
          $values[$i] = $v->toArray();
        
        // Si no se recibieron campos, entonces se mezclaran con los
        // indices obtenidos
        if($mergeWithFields)
          $fields = array_unique(array_merge($fields, array_keys($values[$i])));

      }

      // Preparar registros para crear SQL
      $resultValues = array();
      foreach($values as $i => $v){

        // Asignar array vacío
        $resultValues[$i] = array();

        // Agregar un valor por cada campo de la consulta
        foreach($fields as $f)
          // Obtener el valor del registro actual en el campo actual
          $resultValues[$i][] = $this->realScapeString(isset($v[$f])? $v[$f] : null);

      }

      // Asignar nuevos valores
      $values = $resultValues;

    }
    
    // Obtener el SQL para saber si es valido
    $sql = $this->sqlInsertInto($table, $values, $fields);
    
    // Si el SQL está vacío o si se genera un error en la insercion
    // se devuelve falso
    if(trim($sql) == "" || $this->execute($sql) === false)
      return false;

    // Obtener el ultimo ID insertado
    $id = $this->getLastInsertedId();

    // Se retorna el el último id insertado o true en
    // el caso de que se hayan insertado varios registros
    return $id === 0 ? true : $id;
    
  }

  // Converite el objeto en un array
  public function toArray(){

    // Obtener los nombres de las ta blas
    $tablesNames = array();
    $tables = $this->getTablesFromSchema();
    foreach ($tables as $table) {
      $tablesNames[] = $table["tableName"];
    }

    // Obtener la informacion de la BD en los esquemas
    $info = $this->getInfo();

    // Mezclar el Charset y el Collage
    $info["charset"] = ($charset = $this->getCharset())===null? $info["charset"] : $charset;
    $info["collage"] = ($collage = $this->getCollage())===null? $info["collage"] : $collage;

    return array(
      "name" => $this->getName(),
      "prefix" => $this->getPrefix(),
      "driver" => $this->getDriver(),
      "database" => $this->getDatabase(),
      "server" => $this->getServer(),
      "port" => $this->getPort(),
      "user" => $this->getUser(),
      "pass" => $this->getPass(),
      "charset" => $info["charset"],
      "collage" => $info["collage"],
      "tableNames" => $tablesNames,
    );
  }

  /////////////////////////////////////////////////////////////////////////////
  // Metodos Abstractos que deben ser definidos en las implementaciones
  /////////////////////////////////////////////////////////////////////////////

  // Metodo para obtener el puerto por defecto para una conexión
  abstract public function getDefaultPort();

  // Metodo para crear una conexion
  abstract protected function initConnect();

  // Metodo para cerrar una conexión
  abstract public function close();
  
  // Obtener el número del último error generado en la conexión
  abstract public function getErrNo();
  
  // Obtener la descripcion del último error generado en la conexión
  abstract public function getError();

  // Devuelve un tipo de datos en el gestor de BD
  abstract public function getTypeOf($type);

  // Obtener el siguiente registro de un resultado
  abstract public function getFetchAssoc($result);

  // Obtener el ID del ultimo registro insertado
  abstract public function getLastInsertedId();

  // Realizar una consulta SQL
  abstract protected function query($sql);

  // Devuelve una cadena con un valor valido en el gesto de BD
  abstract public function realScapeString($value);

  //---------------------------------------------------------------------------
  // Metodo para obtener los SQL a ejecutar
  //---------------------------------------------------------------------------

  // Devuelve un nombre entre comillas simples entendibles por el gesto
  abstract public function getParseName($identifier);

  // Set de Caracteres
  abstract public function sqlCharset();

  // Colecion de caracteres
  abstract public function sqlCollage();

  // Setear un valor a una variable de servidor
  abstract public function sqlSetServerVar($varName, $value);

  // Devuelve un String con el SQL para crear la base de datos
  abstract public function sqlCreate();

  // SQL para seleccionar la BD
  abstract public function sqlSelectDatabase();

  // SQL para obtener el listado de tablas
  abstract public function sqlGetTables();
  
  /////////////////////////////////////////////////////////////////////////////
  // Metodos Abstractos que deben ser definidos en las implementaciones
  /////////////////////////////////////////////////////////////////////////////
  
  // Devuelve la carpeta destino para los orm
  public static function getFolderOrm(){
    return self::$ORM_FOLDER;
  }

}
