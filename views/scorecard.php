<script>
  
  var binball;
  $(document).ready(function() {

    binball = new BinBall("<?=$data['game_id'];?>", "<?=$data['user']['_id'];?>", "<?=$data['creator'];?>", "<?=$data['user']['name']." ".$data['user']['surname'];?>");

  });

</script>

<div class="container mt80">
  
  <input type="hidden" name="game_id" id="game_id" value="<?=$data['game_id'];?>" />
  <input type="hidden" name="user_id" id="user_id" value="<?=$data['user']['_id'];?>" />

  <div class="row mb20">
    
    <div class="col-md-12">
      <a class="btn btn-success mb10 pull-right hidden" id='share-btn' onclick="binball.share(this);" href='#'><i class="fa fa-share white"> </i> Share this game</a>
      <div class="input-group block100" id='share'>
        <input type="text" id="shareurl" class="form-control hidden" value="http://<?=$_SERVER['HTTP_HOST'].str_replace($data['creator_id'], "share", $_SERVER['REDIRECT_URL']);?>" />
        <a class='btn btn-info mt20 pull-right' target="_blank" href='https://twitter.com/intent/tweet?text=<?=urlencode("Follow my #binball game live! http://".$_SERVER['HTTP_HOST'].str_replace($data['creator_id'], "share", $_SERVER['REDIRECT_URL']));?>' target="_blank"><i class="fa fa-2x fa-twitter-square fa-lg white"></i> Share game</a>
        <a class='btn btn-info mt20 pull-right mr20' target="_blank" href='https://www.facebook.com/dialog/feed?app_id=134530986736267&link=http%3A%2F%2Fwww.binball.org&name=BinBall&description=<?=urlencode("Follow my #binball game live! http://".$_SERVER['HTTP_HOST'].str_replace($data['creator_id'], "share", $_SERVER['REDIRECT_URL']));?>&amp;redirect_uri=http://facebook.com/' target="_blank"><i class="fa fa-2x fa-facebook-square fa-lg white"></i> Share game</a>
      </div>
    </div>

  </div>

  <div class="row player-entry">
    
    <? if ($data['creator']): ?>
      <div class="col-md-6">
        
        <h4>Add a new player...</h4>

        <input type="text" class="form-control input-lg mb10" placeholder="Enter a players name..." id="player-name" value="" />

        <input type="text" class="form-control input-lg mb10" placeholder="Enter their email address..." id="player-email" value="" />

        <p><a class="btn btn-success mb10" href='Javascript:binball.addPlayerForm();'><i class="fa fa-plus-circle white"> </i> Add player</a></p>

      </div>

      <div class="col-md-3">
        
        <h4>Pick from suggested players</h4>
        <? foreach ($data['suggested_players'] as $suggest): ?>
        <?
          $email = explode("@", $suggest['email']);
          foreach ($email as $k => $e) {
            /*if (strlen($email[$k]) > 6) {
              if ($k) {
                $email[$k] = "...".substr($e, -7);
              } else {
                $email[$k] = substr($e, 0, 7)."...";
              }
            }*/
          }
        ?>
          <p class="mb10"><a href='Javascript:binball.addSuggestedPlayer("<?=$suggest['name'];?>", "<?=$suggest['email'];?>");' data-email="<?=$suggest['email'];?>"><?=$suggest['name'];?> (<?=implode("@", $email);?>)</a></p>
        <? endforeach; ?>

      </div>
    
    <? else: ?>
      
      <div class="col-md-9">
        <h1>Please wait while game is setup...</h1>
      </div>

    <? endif; ?>

    <div class="col-md-3">
      
      <h4>These guys are playing</h4>
      <span id="player-list"></span>

    </div>

  </div>
  
  <? if ($data['creator']): ?>

    <div class="row player-entry">
      
      <div class="col-md-2">
        
        <div class="input-group input-group-lg mt20 mb20">
          <input type="text" class="form-control input-sm" value="8" id="number-of-rounds" size="5" />
          <span class="input-group-addon">Rounds</span>
        </div>

      </div>

    </div>

    <div class="row player-entry">
      
      <div class="col-md-12">
        
        <button class="btn btn-success btn-lg mt20" onclick="binball.start()"><i class="fa fa-play-circle white"></i> Play BinBall</button>

      </div>

    </div>
  <? endif; ?>

  <div class="row" id="scorecard-container">
    
    <div class="col-md-10" id="scorecard">
    </div>

    <div class="col-md-2">
      <span id="leaderboard"></span>

      <span id="stats"></span>
    </div>

  </div>

</div>