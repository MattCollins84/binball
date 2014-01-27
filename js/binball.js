var BinBall = function(game_id, user_id, creator) {

  creator = (creator=="1"?true:false);

  // views and models, in this new crazy world!
  var views = new BinBallViews();
  var models = new BinBallModels();

  // starting params
  this.players = []; //["Matt", "dave", "Glynn", "Simon", "Dan"];
  this.emails = []; //["Matt", "dave", "Glynn", "Simon", "Dan"];
  this.jokers = []; //[false, false, false, false, false];
  this.scores = []; //[[], [], [], [], []];
  this.maxScores = [];
  this.attempts = []; //[[], [], [], [], []];
  this.leaderboard = [];
  this.currentPlayer = 0;
  this.currentRound = 3;
  this.totalRounds = 0;
  this.maxDistance = 0;
  this.gameStarted = false;
  this.stats = null;
  this.creator = creator;

  // reference to the parent object
  var parent = this;

  // insto setup
  var userData = {
    game_id: game_id,
    user_id: user_id,
    creator: creator
  }

  var userQuery = {
    game_id: game_id
  }

  // insto, for realtime goodness
  var insto = new InstoClient('1595dd1cb1e713d09b6b20ab83c210b2', userData, userQuery, {
    
    // on connect
    onConnect: function(data) { 

    },

    // parse eisting connected users
    onConnectedUsers: function(data) { 

    },

    // when a new user connects
    onUserConnect: function(data) {

      return pushSync(data._id);

    },

    // when a user disconnects
    onUserDisconnect: function(data) {

    },

    // when we receive an incoming notification
    onNotification: function(data) {

      switch (data.action) {

        case "sync":
          setSyncObject(data);
          return render();
          break;

        default:
          return console.log(data);
          break;

      }

    }
    /*
    ,
    onQuery: function(data) { ... },
    onUserConnect: function(data) { ... },
    onUserDisconnect: function(data) { ... }
    */
    
  });
  
  /*
    PRIVATE FUNCTIONS
  */
  
  // create a sync object
  var getSyncObj = function() {

    var obj = {};

    for (var i in parent) {

      var item = parent[i];

      if (typeof item != "function") {

        obj[i] = item;

      }

    }

    obj.action = "sync";

    return obj;

  }

  // restore a sync object
  var setSyncObject = function(data) {

    delete data.action;
    delete data.creator;

    for (var d in data) {

      var item = data[d];

      parent[d] = item;

    }

  }

  var render = function() {


    // render the players list
    views.players.renderPlayerList(parent.players);

    if (parent.gameStarted) {

      // render the scorecard
      views.game.render(parent);

      // calculate total scores
      views.game.scores(parent);

      // calculate attempt averages
      views.game.averages(parent);

      // leaderboard
      views.game.leadboard(parent);

      // do we need to do the stats?
      if($('#stats').html() === "") {
        
        models.game.getStats(parent, function(stats) {

          $('#stats').html(stats);
          views.game.stats(parent);

        });

      }

      // or just render them
      else {

        views.game.stats(parent);

      }

    }
    

  }

  // add player to local store
  var addPlayer = function(name, email) {

    parent.players.push(name);
    parent.emails.push(email);
    parent.jokers.push(false);
    parent.scores.push([]);
    parent.maxScores.push(0);
    parent.attempts.push([]);

    $('#player-name').val("");
    $('#player-email').val("");

    sync();

  }

  var sync = function() {
    insto.send(userQuery, getSyncObj(), true);
  }

  var pushSync = function(id) {
    insto.send({"_id": id}, getSyncObj(), true);
  }

  /*
    PUBLIC FUNCTIONS
  */

  // add player from form
  this.addPlayerForm = function() {

    var name = $('#player-name').val();
    var email = $('#player-email').val();

    if (!name) {
      return alert("You must enter a name");
    }

    if (!email) {
      return alert("You must enter an email");
    }

    // add the player
    addPlayer(name, email);
    
    // store in cloudant
    models.players.create(name, email, function(res) {
      
    });

  }

  // add a suggested player to the list
  this.addSuggestedPlayer = function(name, email) {
    
    $('a[data-email="'+email+'"]').remove();

    addPlayer(name, email);

  }

  // start the game!
  this.start = function() {

    if (!this.players.length) {
      return alert("You don't have any players, dummy!");
    }

    var totalRounds = parseInt($('#number-of-rounds').val(), 10);
    this.totalRounds = totalRounds;
    this.maxDistance = totalRounds + 2;

    var max = this.calculateMaximum(3, totalRounds, true);

    for (var m in this.maxScores) {
      this.maxScores[m] = max;
    }

    parent.gameStarted = true;

    sync();

  }

  // calculate maximum remaining score
  this.calculateMaximum = function(start, end, joker) {

    // calculate the initial maximum score
    var max = 0;
    for (var t = start; t < (end + 3); t++) {
      max += t;
    }

    // add again for joker
    if (joker) {
      max += (t - 1);
    }

    return max;

  }

  // play a joker
  this.playJoker = function(player_id) {
    
    var original = player_id;

    var bits = player_id.split(/[a-zA-Z]/);

    player_id = bits[bits.length-1];
    var round_id = parseInt(bits[bits.length-2], 10);

    this.jokers[player_id] = parseInt((round_id-3), 10);

    parent.addScore("r"+round_id+"p"+player_id, $("select#scorer"+round_id+"p"+player_id).val(), true);

  }

  // calculate a score
  this.addScore = function(player_id, score, viaPlayJoker) {

    var bits = player_id.split(/[a-zA-Z]/);

    player_id = bits[bits.length-1];
    var round_id = parseInt(bits[bits.length-2], 10);

    var attempt = ((round_id - score) + 1);

    var r = (round_id-3);

    if (typeof score != "number") {
      score = parseInt(score, 10);
    }

    if (typeof this.scores[player_id][r] == "undefined" && this.scores[player_id].length == r) {
      this.scores[player_id].push(score);
      this.attempts[player_id].push(attempt);
    }

    else if (typeof this.scores[player_id][r] == "undefined" && this.scores[player_id].length < r) {
      alert("You cannot alter this score yet");
      $('select#scorer'+round_id+'p'+player_id).val("")
    }

    else {
      this.scores[player_id][r] = score;
      this.attempts[player_id][r] = attempt;
    }
    
    var hitJoker = false;
    for (var s in this.scores[player_id]) {

      hitJoker = false;
      
      s = parseInt(s, 10);

      if (this.jokers[player_id] !== false && this.jokers[player_id] == s) {
        hitJoker = true;
      }

    }

    // determine what is the next player
    if (player_id == (this.players.length-1)) {
      this.currentPlayer = 0;

      if (viaPlayJoker !== true) {
        this.currentRound++;
      }
      
    } else {
      this.currentPlayer++;
    }

    // add cloudant
    models.game.addScore({ 
      game_id: $('#game_id').val(), 
      email: this.emails[player_id], 
      attempt: attempt, 
      hit_joker: hitJoker, 
      round: round_id 
    }, function(res) {

      // console.log('score added', res);

    });

    sync();

  }

  // miss your joker
  this.jokerMiss = function(player_id) {

    this.jokers[player_id] = 9999;
    sync();

    models.game.jokerMiss({ 
      game_id: $('#game_id').val(), 
      email: this.emails[player_id], 
      round: this.currentRound
    }, function(res) {

      // console.log('joker missed', res);

    });
    
  }

  // show share link
  this.share = function(el) {
    $(el).addClass("hidden");
    $('#share').removeClass("hidden");
  }

}