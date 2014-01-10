<script>
  
  var binball;
  $(document).ready(function() {

    binball = new BinBall();

  });

</script>

<div class="container mb20">
  
  <div class="row player-entry">
    
    <div class="col-md-12">
        
      <div class="input-group input-group-lg">
        <input type="text" class="form-control" placeholder="Enter a players name..." id="player-name" />
        <span class="input-group-addon"><a href='Javascript:binball.addPlayer();'><i class="fa fa-plus-circle"> </i></a></span>
      </div>

    </div>

    <div class="col-md-12 mt20">
        
      <span id="player-list"></span>

    </div>
  </div>

  <div class="row player-entry">
    
    <div class="col-md-2">
      
      <div class="input-group input-group-lg mb20">
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

    <div class="col-md-2" id="leaderboard">
    </div>

  </div>

</div>