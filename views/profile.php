<div class="container mb20 mt60">
  
  <div class="row">
    
    <div class="col-md-12">
      <h1>Welcome, <?=$data['user']['name'];?></h1>

      <h2>Round averages</h2>
      <p>Your average number of attempts, per round, across every game you have played</p>
      <? foreach ($data['user_round_average'] as $player_id => $round_avg): ?>

        <? foreach ($round_avg as $round => $avg): ?>
          <div class="stats alert alert-success">
            <span class="label label-warning pull-right">Global average is <?=$data['round_average'][$round];?></span>
            <span class="label label-danger pull-right mr20">Your average is <?=$avg;?></span>
            <h4>Round <?=$round;?>. </h4>
          </div>
        <? endforeach; ?>
      <? endforeach; ?>
      <?
      if (count($data['user_round_average']) == 0 || !isset($data['user_round_average'])) {
        echo "<p>No data.</p>";
      }
      ?>

      <h2>Round failure percentages</h2>
      <p>The amount of times you have failed a particular round, expressed as percentages</p>
      <? foreach ($data['user_round_fails'] as $player_id => $fail_pc): ?>

        <? foreach ($fail_pc as $round => $pc): ?>
          <div class="stats alert alert-success">
            <span class="label label-danger pull-right">Your fail record is <?=$pc;?>%</span>
            <h4>Round <?=$round;?>. </h4>
          </div>
        <? endforeach; ?>
      <? endforeach; ?>
      <?
      if (count($data['user_round_fails']) == 0 || !isset($data['user_round_fails'])) {
        echo "<p>No data.</p>";
      }
      ?>

      <h2>Successful jokers</h2>
      <p>The amount of times you have successfully played your joker on a round, expressed as percentages</p>
      <? foreach ($data['joker_user_round'] as $player_id => $joker_pc): ?>

        <? foreach ($joker_pc as $round => $pc): ?>
          <div class="stats alert alert-success">
            <span class="label label-warning pull-right">Global success is <?=$data['joker_round'][$round];?>%</span>
            <span class="label label-danger pull-right mr20">Your joker success is <?=$pc;?>%</span>
            <h4>Round <?=$round;?>. </h4>
          </div>
        <? endforeach; ?>
      <? endforeach; ?>

      <?
      if (count($data['joker_user_round']) == 0 || !isset($data['joker_user_round'])) {
        echo "<p>No data.</p>";
      }
      ?>

    </div>

  </div>

</div>