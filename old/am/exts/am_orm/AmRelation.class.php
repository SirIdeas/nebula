<?php

class AmRelation extends AmObject{
  
  protected
    $source = "default",
    $table = null,
    $columns = array();
  
  // Métodos GET para las propiedades
  public function getSource(){ return $this->source; }
  public function getTable(){ return $this->table; }
  public function getColumns(){ return $this->columns; }
  
  // Generador de la consulta para la relación
  public function getQuery($model){
    
    // Una consulta para todos los registros de la tabla
    $q = AmORM::table($this->getTable(), $this->getSource())->all();
    
    foreach($this->getColumns() as $from => $to){
      $q->where("$to='{$model->$from}'");
    }
    
    return $q;
    
  }
  
  // Convertir a Array
  public function toArray(){
    
    return array(
      "source" => $this->getSource(),
      "table" => $this->getTable(),
      "columns" => $this->getColumns()
    );
    
  }
  
}
