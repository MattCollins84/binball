<script>
  
  var binball;
  $(document).ready(function() {

    binball = new BinBall();

  });

</script>

<div class="container mb20">
  
  <input type="hidden" name="game_id" id="game_id" value="<?=$data['game_id'];?>" />
  <input type="hidden" name="user_id" id="user_id" value="<?=$data['user']['_id'];?>" />

  <div class="row player-entry">
    
    <div class="col-md-6">
      
      <h4>Add a new player...</h4>

      <input type="text" class="form-control input-lg mb10" placeholder="Enter a players name..." id="player-name" />

      <input type="text" class="form-control input-lg mb10" placeholder="Enter their email address..." id="player-email" />

      <p><a class="btn btn-success mb10" href='Javascript:binball.addPlayer();'><i class="fa fa-plus-circle"> </i> Add player</a></p>

    </div>

    <div class="col-md-3">
      
      <h4>Pick from suggested players</h4>
      <? foreach ($data['suggested_players'] as $suggest): ?>
      <?
        $email = explode("@", $suggest['email']);
        foreach ($email as $k => $e) {
          if (strlen($email[$k]) > 6) {
            if ($k) {
              $email[$k] = "...".substr($e, -7);
            } else {
              $email[$k] = substr($e, 0, 7)."...";
            }
          }
        }
      ?>
        <p><a href='Javascript:binball.addSuggestedPlayer("<?=$suggest['name'];?>", "<?=$suggest['email'];?>");' data-email="<?=$suggest['email'];?>"><?=$suggest['name'];?> (<?=implode("@", $email);?>)</a></p>
      <? endforeach; ?>

    </div>

    <div class="col-md-3">
      
      <h4>These guys are playing</h4>
      <span id="player-list"></span>

    </div>

  </div>

  <div class="row player-entry">
    
    <div class="col-md-2">
      
      <div class="input-group input-group-lg mt20 mb20">
        <input type="text" class="form-control input-sm" value="8" id="number-of-rounds" size="3" />
        <span class="input-group-addon">Rounds</span>
      </div>

    </div>

  </div>

  <div class="row player-entry">
    
    <div class="col-md-12">
      
      <button class="btn btn-success btn-lg mt20" onclick="binball.start()"><i class="fa fa-play-circle"></i> Play BinBall</button>

    </div>

  </div>

  <div class="row" id="scorecard-container">
    
    <div class="col-md-10" id="scorecard">
    </div>

    <div class="col-md-2">
      <span id="leaderboard"></span>

      <span id="stats"></span>
    </div>

  </div>

</div>