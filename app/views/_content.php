(# parent:views/-viewport.php #)
(# set:paso="" #)

<?php

$pasosIndexes = array_keys($pasos);
$pasosValues = array_values($pasos);

$actual = array_search($paso, $pasosIndexes);

$anterior  = $actual!==false && $actual>0?
  $pasos[$pasosIndexes[$actual - 1]] : false;
$siguiente = $actual!==false && $actual<(count($pasosIndexes)-1)?
  $pasos[$pasosIndexes[$actual + 1]] : false;

?>

(# section:stepBar #)
  <div class="row">
    <div class="col s6">
      <?php if ($anterior): ?>
        <a href="<?php Am::eUrl($anterior[0]) ?>"><<&nbsp;<?php echo $anterior[1] ?></a>
      <?php endif ?>
      &nbsp;
    </div>
    <div class="col s6 text-right">
      <?php if ($siguiente): ?>
        <a href="<?php Am::eUrl($siguiente[0]) ?>"><?php echo $siguiente[1] ?>&nbsp;>></a>
      <?php endif ?>
    </div>
  </div>
(# endsection #)

<div class="container">
  <div class="row">
    <div class="col m2">

      <div class="indice">
        (# place:views/_sidebar.php #)
      </div>

    </div>
    <div class="col m10">
      <div class="page-inner">
        
        (# put:stepBar #)
        (# child #)
        <br>
        (# put:stepBar #)

      </div>
    </div>
  </div>
</div>