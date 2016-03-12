(:: parent:views/tpl.php :)
(:: set:paso="" :)
<?php
$pasos = Am::getProperty('pasos');
$pasosIndexes = array_keys($pasos);
$pasosValues = array_values($pasos);
$actual = array_search($paso, $pasosIndexes);
$anterior  = $actual!==false && $actual>0?$pasos[$pasosIndexes[$actual - 1]] : false;
$siguiente = $actual!==false && $actual<(count($pasosIndexes)-1)? $pasos[$pasosIndexes[$actual + 1]] : false;
?>

(:: section:stepBar :)
  <div class="row">
    <div class="col-xs-6">
      (: if ($anterior): :)
        <a href="(:= Am::url($anterior[0]) :)">
          <<&nbsp;(:= $anterior[1] :)
        </a>
      (: endif :)
      &nbsp;
    </div>
    <div class="col-xs-6 text-right">
      (: if ($siguiente): :)
        <a href="(:= Am::url($siguiente[0]) :)">
          (:= $siguiente[1] :)&nbsp;>>
        </a>
      (: endif :)
    </div>
  </div>
(:: endsection :)

<div class="container">
  <div class="row">
    <div class="col-sm-3 hidden-xs">
      <div class="indice">
        (:: place:views/sidebar.php :)
      </div>
    </div>
    <div class="col-sm-9 col-xs-12">
      <div class="page-inner">
    
        (:: put:stepBar :)
        (:: child :)
        <br>
        (:: put:stepBar :)

      </div>
    </div>
  </div>
</div>