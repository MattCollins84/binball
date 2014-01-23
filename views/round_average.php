<? foreach ($data['round_average'] as $round => $avg): ?>
  <div id='round-avg-<?=$round;?>' class="stats alert alert-success hidden">
    <span class="label label-danger pull-right"><?=$avg;?></span>
    <h4>Round Avg. </h4>
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

<? foreach ($data['joker_round'] as $round => $pc): ?>
  <div id='joker-pc-<?=$round;?>' class="stats alert alert-success hidden">
    <span class="label label-danger pull-right"><?=$pc;?></span>
    <h4>Round Joker %</h4>
  </div>
<? endforeach; ?>

<? foreach ($data['joker_user_round'] as $player_id => $joker_pc): ?>

  <? foreach ($joker_pc as $round => $pc): ?>
    <div id='joker-<?=$round;?>-player-<?=$player_id;?>-pc' class="stats alert alert-success hidden">
      <span class="label label-danger pull-right"><?=$pc;?></span>
      <h4>Player Joker % </h4>
    </div>
  <? endforeach; ?>
<? endforeach; ?>