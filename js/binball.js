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

    for (var d in data) {

      var item = data[d];

      parent[d] = item;

    }

  }

  var render = function() {


    // render the players list
    views.players.renderPlayerList(parent.players);

    // render the scorecard
    views.game.render(parent);

    // calculate total scores
    views.game.scores(parent);

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
    $.ajax({
      type: "GET",
      url: "/create/player",
      data: { name: name, email: email }
    })
    .done(function( msg ) {
      
      

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

    $.ajax({
      type: "GET",
      url: "/stats",
      data: { min_round: 3, max_round: (parseInt(totalRounds, 10) + 2), emails: parent.emails }
    })
    .done(function( html ) {
      
      // parent.stats = html;
      parent.gameStarted = true;

      sync();

    });

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

  this.playJoker = function() {
    console.log('play joker');
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

    $.ajax({
      type: "GET",
      url: "/add/score",
      data: { game_id: $('#game_id').val(), email: this.emails[player_id], attempt: attempt, hit_joker: hitJoker, round: round_id }
    })
    .done(function( msg ) {
      


    });

    sync();

  }

}