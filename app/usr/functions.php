<?php

function enlace($text, $blank = true){
  $links = Am::getConfig("usr/links");
  $url = $links[$text];
  $style = empty($url)? 'style="color:red"' : "";
  $blank = $blank === true? 'target="_blank"' : "";
  echo '<a href="'.$url.'" '.$blank.' '.$style.'>'.$text.'</a>';
}

function docEnlace($class){
  echo '<a><code>'.$class.'</code></a>';
}

function parrafosDe($text){
  return "<p>".implode("</p><p>", explode("\n", $text))."</p>";
}

function getCodeFile($path){
  return htmlentities(file_get_contents("views/codes/{$path}.txt"));
}