<?php

// Indica si un callback es válido o no.
function isValidCallback($callback){
  // Si es un array evaluar como metodo
  if(is_array($callback))
    return call_user_func_array("method_exists", $callback);
  // Si es string evaluar como function
  if(is_string($callback))
    return function_exists($callback);
  // Es un callback invalido
  return false;
} 

// Devuele un valor de una posicion del array. Si el valor
// no existe devuelve el valor por $def
function itemOr($index, array $arr, $def = null){
  return isset($arr[$index])? $arr[$index] : $def;
}

// Indica si es una array asociativo o no
function isAssocArray(array $array){
  $j = 0;
  foreach($array as $i => $_){
    if($j !== $i)
      return true;
    $j++;
  }
  return false;
}

// Mezclar dos array si la primera posicion del segundo array
// es diferentes de falso, sino retornar el segundo array
function merge_if_snd_first_not_false(array $arr1, array $arr2){
  if(isset($arr2[0]) && $arr2[0] === false)
    return $arr2;
  return array_merge($arr1, $arr2);
}

// Mezclar dos array si la primera posicion del segundo array
// es diferentes de falso, sino retornar el segundo array.
// Devuelve los valores unicos
function merge_if_snd_first_not_false_unique(array $arr1, array $arr2){
  return array_unique(merge_if_snd_first_not_false($arr1, $arr2));
}

// Mezclar dos array recursivamente si la primera posicion
// del segundo array. es diferentes de falso, sino retornar
// el segundo array
function merge_r_if_snd_first_not_false(array $arr1, array $arr2){
  if(isset($arr2[0]) && $arr2[0] === false)
    return $arr2;
  return array_merge_recursive($arr1, $arr2);
}

// Mezvla dos array si ambos parametros son arrays.
// De lo contrario se conservará el segundo elemento
function merge_if_are_array($arr1, $arr2){
  if(is_array($arr1) && is_array($arr2))
    return array_merge($arr1, $arr2);
  return $arr2;
}

// Mezvla dos array recursivamente si ambos parametros son arrays
// De lo contrario se conservará el segundo elemento
function merge_r_if_are_array($arr1, $arr2){
  if(is_array($arr1) && is_array($arr2))
    return array_merge_recursive($arr1, $arr2);
  return $arr2;
}

// Mezcla dos arrays sin ambos parametros son arrays y si
// el primer elemento del segundo parametro no es falso.
function merge_if_are_array_and_snd_first_not_false($arr1, $arr2){
  if(is_array($arr1) && is_array($arr2))
    merge_if_snd_first_not_false($arr1, $arr2);
  return $arr2;
}

// Mezcla recursivamente dos arrays sin ambos parametros son
// arrays y si el primer elemento del segundo parametro no es falso.
function merge_r_if_are_array_and_snd_first_not_false($arr1, $arr2){
  if(is_array($arr1) && is_array($arr2))
    merge_r_if_snd_first_not_false($arr1, $arr2);
  return $arr2;
}

