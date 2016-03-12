<?php

/**
 * Clase para las consultas SQL
 */

class AmQuery extends AmObject{

  // Propidades
  protected
    $name = null,       // Nombre de la fuente
    $table = null,      // Tabla en la que se basa la consulta
    $model = null,      // Nombre dle modelo para los registros de la consulta
    $selects = array(), // Lista de campos para la clausula SELECT
    $distinct = false,  // Para solo obtener los registros diferentes 
    $froms = array(),   // Lista de tablas para la clausula FROM
    $wheres = array(),  // Lista de condiciones para la clausula WHERE
    $joins = array(),   // Lista de tablas para la clausula JOIN
    $orders = array(),  // Lista de campos para la clausula ORDER BY
    $groups = array(),  // Lista de campos para la clausula GROUP BY
    $limit = null,      // Cantidad de registro: LIMIT
    $offset = null,     // Posicion de inicio: OFFSET
    $sets = array(),    // Lista de cambios SETS para consultas UPDATE
    $source = null,     // Fuente de datos
    $result = null;     // Manejador para el resultado de la consulta

  // Métodos GET para algunas propiedades
  public function getName(){ return $this->name; }
  public function getSource(){ return $this->source; }
  public function getTable(){ return $this->table; }
  public function getModel(){ return $this->model; }
  public function getSelects(){ return $this->selects; }
  public function getFroms(){ return $this->froms; }
  public function getWheres(){ return $this->wheres; }
  public function getOrders(){ return $this->orders; }
  public function getGroups(){ return $this->groups; }
  public function getLimit(){ return $this->limit; }
  public function getOffset(){ return $this->offset; }
  public function getSets(){ return $this->sets; }
  public function getDistinct(){ return $this->distinct; }

  // GET para los joins
  public function getJoins($type = null){

    // Se solicito los joins de un tipo
    if(isset($type))
      return $this->joins[strtoupper($type)];

    // Devolver todos los joins
    return $this->joins;

  }

  // Métodos SET para algunas propiedades
  public function setSource($value){ $this->source = $value; return $this; }
  public function setSelects(array $value){ $this->selects = $value; return $this; }

  // Asignar signar la clausula distint
  public function distinct(){ $this->distinct = true; return $this; }
  public function noDistinct(){ $this->distinct = true; return $this; }

  // Ejecuta la consulta SQL
  public function execute(){

    // Obtener la fuente
    $s = $this->getSource();

    // Ejecutar desde el driver
    return $this->result = $s->execute($this->sql());

  }

  // Devuelve otra instancia de la consulta actual
  public function getCopy(){
    return clone($this);
  }

  // Devuelve una copia aislada de la consulta actual 
  public function getAlone($as = "q"){
    return $this->getSource()->newQuery($this, $as);
  }

  // Método para asignar array de valores por un metodo
  // Destinado al metodo ->select y ->from
  private function setArrayAttribute($method, $args){
    
    // Agregar cada argmento
    foreach($args as $arg){
      // Si es un array
      if(is_array($arg)){
        foreach($arg as $as => $value){
          $this->$method($value, $as);
        }
      }else{
        // Si es una cadena
        $this->$method($arg);
      }
    }

    return $this;

  }
  
  // Asignar los selects
  public function select(){
    return $this->setArrayAttribute("selectAs", func_get_args());
  }

  // Método para agregar clausula SELECT
  public function selectAs($field, $as = null){
    
    // Si no se indicó el argumetno $as
    if(empty($as)){
      if (AmORM::isNameValid($field)){
        // Agregar en una posicion espeficia
        $this->selects[$field] = $field;
      }else{
        // Agregar al final
        $this->selects[] = $field;
      }
    }elseif(AmORM::isNameValid($as)){
      // Agregar en una posicion espeficia
      $this->selects[$as] = $field;
    }else{
      // Agregar al final
      $this->selects[] = $field;
    }
    
    return $this;

  }
  
  // Asignar los selects
  public function from(){
    return $this->setArrayAttribute("fromAs", func_get_args());
  }

  // Método para agregar clausula FROM
  public function fromAs($from, $as = null){
    
    // Asignacion de la tabla si aun no ha sido asignada  
    if(!isset($this->table)){
      if($from instanceof AmTable){
        // Asignar como tabla
        $this->table = $from;
      }elseif($from instanceof AmQuery){
        // Asignar tabla de la consulta
        $this->table = $from->getTable();
      }
    }

    // Asignacion del modelo
    if(empty($this->model)){
      if($from instanceof AmTable){
        // Asignar modelo de la tabla
        $this->model = $from->getModelName();
      }elseif($from instanceof AmQuery){
        // Asignar modelo de tabla de la consulta
        $table = $from->getTable();
        if(isset($table) && $table instanceof AmTable){
          $this->model = $table->getModelName();
        }
      }elseif(is_string($from) && AmORM::isNameValid($from)){
        // Asignar modelo
        $this->model = $from;
      }
    }

    // Asignacion del from
    if(empty($as)){

      // Si no se indicó el parametro $as
      if($from instanceof AmQuery){
        // Si es una consulta se agrega al final
        $this->froms[] = $from;
      }elseif($from instanceof AmTable){
        // Si es nua tabla se asigna en una posicion especifica
        $this->froms[$from->getTableName()] = $from;
      }elseif (AmORM::isNameValid($from)){
        // Se asigna en una posicion especifica 
        $this->froms[$from] = $from;
      }else{
        // Agregar al final
        $this->froms[] = $from;
      }

    }elseif(AmORM::isNameValid($as)){
      // Adicion en posicion determinada
      $this->froms[$as] = $from;
    }else{
      // Adicion al final de la lista de tablas
      $this->froms[] = $from;
    }

    return $this;
      
  }

  // Preparar las condiciones para agregarlas al array de condiciones
  protected function parseWhere($conditions){
    
    // Si no es un array de retornar tal cual
    if(!is_array($conditions)) return $conditions;

    $ret = array();
    $nextPrefijo = "";  // Operador booleano de prefijo
    $nextUnion = "AND"; // Operador booleano de enlace

    // Por cada condicione
    foreach($conditions as $condition){
      
      // Obtiene la condicion de union y la vuelve mayuscula
      if(!is_array($condition)){
        $upperCondition = strtoupper($condition);
      }elseif(count($condition)==3 && strtoupper($condition[1]) == "IN"){
        // Eliminar condicion dle medio
        $condition = array($condition[0], $condition[2]);
        $upperCondition = "IN";
      }else{
        $upperCondition = "";
      }

      if($upperCondition == "AND" || $upperCondition == "OR"){
        // Si la primera condicion es un operador boolean doble
        $nextUnion = $upperCondition;
      }elseif($upperCondition == "NOT"){
        // Si es el operator booleano de negacion agregar para la siguiente condicion
        $nextPrefijo = $upperCondition;
      }else{

        // Sino es un operador booleano se agrega al listado de condiciones de retorno
        $ret[] = array(
          "union" => $nextUnion,
          "prefix" => $nextPrefijo,
          "condition" => $upperCondition == "IN"? $condition : $this->parseWhere($condition),
          "isIn" => $upperCondition == "IN"
        );
        
        $nextPrefijo = "";

      }

    }

    return $ret;

  }

  // Metodo para agregar condiciones
  public function where(){
        
    $args = func_get_args();

    // Parchar las condificones para luego agregarlas
    foreach($this->parseWhere($args) as $where){
      $this->wheres[] = $where;
    }

    return $this;

  }

  // Agregar condiciones con AND y OR
  public function andWhere(){ return $this->where("and", func_get_args()); }
  public function orWhere(){ return $this->where("or", func_get_args());}

  // Eliminar todas las condiciones
  public function clearWhere(){
    $this->conditions = array();
    return $this;
  }

  // Agregar un join
  public function joins($type, $table, $on, $as){

    // Convertir a mayusculas
    $type = strtoupper($type);

    // Si no existe la colecion de join para el tipo indicado entonces se crea
    if(!isset($this->joins[$type]))
      $this->joins[$type] = array();

    // Agregar los joins
    $this->joins[$type][] = array("table" => $table, "on" => $on, "as" => $as);

    return $this;
      
  }

  // INNER, LEFT y RIGHT Join
  public function innerJoin($table, $on = null, $as = null){ return $this->joins("inner", $table, $on, $as); }
  public function leftJoin($table, $on = null, $as = null){ return $this->joins("left", $table, $on, $as);}
  public function rigthJoin($table, $on = null, $as = null){ return $this->joins("right", $table, $on, $as); }

  // Agregar campos para ordenar por en un sentido determinado
  public function orders($dir, array $orders){

    // Recorrer para agregar
    foreach($orders as $order){

      // Liberar posicion para que al agregar quede en ultima posicion
      unset($this->orders[$order]);
      $this->orders[$order] = $dir;

    }

    return $this;

  }

  // Agregar campos de orden Ascendiente
  public function orderBy(){
    return $this->orders("ASC", func_get_args());
  }

  // Agregar campos de orden Descendiente
  public function orderByDesc(){
    return $this->orders("DESC", func_get_args());
  }

  // Agregar campos para agrupar
  public function groups(array $groups){
      
    // Elimintar los campos que se agregaran de los existentes
    $this->groups = array_diff($this->groups, $groups);

    // Agregar cada campo
    foreach($groups as $group)
      $this->groups[] = $group;

    return $this;
      
  }

  // Agregar un campos para agrupar
  public function groupBy(){
    return $this->groups(func_get_args());
  }

  // Agregar un límite a la consulta
  public function limit($limit){
    $this->limit = $limit;
    return $this;
  }

  // Agregar punto de inicio para la consulta
  public function offSet($offset){
    $this->offset = $offset;
    return $this;
  }

  // Agregar un SET a la consulta. Es tomado en cuenta cuando se realiza una
  // actualizacio sobre la consulta
  public function set($field, $value, $const = true){
    $this->sets[] = array("field" => $field, "value" => $value, "const" => $const);
    return $this;
  }

  // Obtener una consulta para contar los registros de la consulta actual
  public function countQuery(){
    return $this->getCopy()->setSelects(array("count" => "count(*)"));
  }

  // Obtener la cantidad de registros que devolverá la consulta
  public function count(){

    // Crear la consulta para contar
    $ret = $this->countQuery()->getRow("object");

    // Si se generó un error devolver cero, de lo contrari
    // devolver el valor obtenido
    return $ret === false ? 0 : intval($ret->count);
    
  }

  // Obtener un registro del resultado de la consulta
  public function getRow($as = null, $formater = null){

    // Obtener la fuente de datos  
    $s = $this->getSource();
    
    // Se ejecuta la consulta si no se ha ejecutado la consulta
    if(null === $this->result)
      $this->execute();
    
    // Si se generó un error en la consulta retornar false
    if(false === $this->result)
      return false;

    // Obtener el registro
    $r = $s->getFetchAssoc($this->result);
    
    // Si no existe mas registros
    if(false === $r)
      return false;

    // Si no se indicó como devolver el objeto
    if(!isset($as)){

      // Obtener el nombre de la clase del model
      $className = AmORM::model($this->getModel(), $s->getName());
      
      // Se encontró el modelo
      if (false !== $className){
        $r["isNew"] = false;      // Indica que no es un registr nuevo
        $r = new $className($r);  // Crear instancia del modelo
      }else{
        // Devolver como objeto de Amathista
        $r = new AmObject($r);
      }
    
    }elseif($as == "array"){
      // Retornar como erray
      // $r = $r;
    }elseif($as == "object"){
      // Retornar como objeto
      $r = (object)$r;
    }elseif($as == "am"){
      // Retornar como objeto de Amathista
      $r = new AmObject($r);
    }else{
      // Sino retornar null
      $r = null;
    }

    // Formatear el valor
    if(isset($formater) && isValidCallback($formater))
      $r = call_user_func_array($formater, array($r));

    return $r;

  }

  // Devuelve una columna de la consulta.
  public function getCol($field){
    
    // Crear la consulta
    $q = $this->getSource()->newQuery($this)->selectAs($field);
    
    // Array para retorno
    $ret = array();

    // Mientras exista resgistros en cola
    while(false !== ($row = $q->getRow("array"))){ 
      $ret[] = $row[$field]; // Agregar registros al array
    }
    
    return $ret;
      
  }

  // Devuelve un array con los registros resultantes de la consulta
  public function getResult($as = null, $formater = null){

    // Si no es callback válido asignar null para 
    // ahorrar sentencias
    if(!isValidCallback($formater))
      $formater = null;

    // Crear consulta
    $q = $this->getSource()->newQuery($this);

    // Array para retorno
    $ret = array();
    
    // Mientras exista resgistros en cola
    while(false !== ($row = $q->getRow($as, $formater))){
      $ret[] = $row; // Agregar registros al array
    }
    
    return $ret;

  }

  // Actualizar los registro seleccionados
  public function update(){

    // Si hay al menos un cambio que realizar
    if(count($this->sets)>0){

      // Obtener instancia de la fuente
      $source = $this->getSource();

      // Obtener la instancia de la tabla
      $table = $source->getTable($this->getTable());

      // Agregar fechas de creacion y modificacion si existen en la tabla
      if($table->hasUpdatedAtField())
        $this->set($table->getUpdatedAtField(), date("c"), true);

      return false !== $source->execute($this->sqlUpdate());
    }

    return true;
  }

  // Eliminar registros selecionados
  public function delete(){
    return false !== $this->getSource()->execute($this->sqlDelete());
  }

  // Insertar los registros resultantes de la consulta en una table
  public function insertInto($table, array $fields = array()){
    return $this->getSource()->insertInto($table, $this, $fields);
  }

  // Metodos para obtener el SQL de cada clausula
  public function sqlSelect($with = true){ return $this->getSource()->sqlSelect($this, $with); }
  public function sqlFrom($with = true){ return $this->getSource()->sqlFrom($this, $with); }
  public function sqlWhere($with = true){ return $this->getSource()->sqlWhere($this, $with); }
  public function sqlOrders($with = true){ return $this->getSource()->sqlOrders($this, $with); }
  public function sqlGroups($with = true){ return $this->getSource()->sqlGroups($this, $with); }
  public function sqlLimit($with = true){ return $this->getSource()->sqlLimit($this, $with); }
  public function sqlOffset($with = true){ return $this->getSource()->sqlOffset($this, $with); }
  public function sqlJoins(){ return $this->getSource()->sqlJoins($this); }
  public function sqlSets($with = true){ return $this->getSource()->sqlSets($this, $with); }

  // Metodos para obtener el SQL los diferentes tipos de consulta
  public function sql(){ return $this->getSource()->sql($this); }
  public function sqlInsertInto($table, array $fields = array()){ return $this->getSource()->sqlInsertInto($this, $table, $fields); }
  public function sqlUpdate(){ return $this->getSource()->sqlUpdate($this); }
  public function sqlDelete(){ return $this->getSource()->sqlDelete($this); }

  // Conver a Cadena de caracteres implica devolver el SQL de la consulta
  public function __toString() {
    return $this->sql();
  }

  // PENDIENTE DESARROLLAR
  public function createSql(){}
  public function create(){}
  
  public function dropSQL(){}
  public function drop(){}
    
  public static function export(){}

}