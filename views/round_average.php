<? foreach ($data['round_average'] as $round => $avg): ?>
  <div id='round-avg-<?=$round;?>' class="stats alert alert-success hidden">
    <span class="label label-danger pull-right"><?=$avg;?></span>
    <h4>Round Avg. </h4>
  </div>
<? endforeach; ?>

<? foreach ($data['jokers_hit'] as $round => $pc): ?>
  <div id='joker-pc-<?=$round;?>' class="stats alert alert-success hidden">
    <span class="label label-danger pull-right"><?=$pc;?></span>
    <h4>Joker %</h4>
  </div>
<? endforeach; ?>

<? foreach ($data['user_round_average'] as $player_id => $round_avg): ?>

  <? foreach ($round_avg as $round => $avg): ?>
    <div id='round-<?=$round;?>-player-<?=$player_id;?>-avg' class="stats alert alert-success hidden">
      <span class="label label-danger pull-right"><?=$avg;?></span>
      <h4>Player Avg. </h4>
    </div>
  <? endforeach; ?>
<? endforeach; ?>

<!---->