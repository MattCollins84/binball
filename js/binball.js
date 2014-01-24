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

  // reference to the parent object
  var parent = this;

  // insto setup
  this.userData = {
    game_id: game_id,
    user_id: user_id
  }

  this.userQuery = {
    game_id: game_id
  }

  // insto, for realtime goodness
  var insto = new InstoClient('1595dd1cb1e713d09b6b20ab83c210b2', this.userData, this.userQuery, {
    
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
          return console.log("SYNC", data);
          break;

        default:
          return console.log(data);
          break;

      }

      return callback(data);

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
  var addPlayer = function(name, email) {

    parent.players.push(name);
    parent.emails.push(email);
    parent.jokers.push(false);
    parent.scores.push([]);
    parent.maxScores.push(0);
    parent.attempts.push([]);

    $('#player-name').val("");
    $('#player-email').val("");

    insto.send(this.userQuery, {players: parent.players, emails: parent.emails, jokers: parent.jokers, scores: parent.scores, maxScores, action: "sync"}, true);

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

    $.ajax({
      type: "GET",
      url: "/create/player",
      data: { name: name, email: email }
    })
    .done(function( msg ) {
      
      addPlayer(name, email);

    });

  }

  // add a suggested player to the list
  this.addSuggestedPlayer = function(name, email) {
    
    $('a[data-email="'+email+'"]').remove();

    addPlayer(name, email);

  }

}