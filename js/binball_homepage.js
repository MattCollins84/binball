var BinBallHomepage = function() {

  // views and models, in this new crazy world!
  var views = new BinBallViews();
  var models = new BinBallModels();

  // starting params
  

  // reference to the parent object
  var parent = this;

  // insto setup
  var userData = {
    host: "no"
  }

  var userQuery = {
    host: "yes"
  }

  // live game data
  var games = {}

  // insto, for realtime goodness
  var insto = new InstoClient('1595dd1cb1e713d09b6b20ab83c210b2', userData, userQuery, {
    
    // on connect
    onConnect: function(data) { 

    },

    // parse eisting connected users
    onConnectedUsers: function(data) { 
      
      if (typeof data.users != "undefined" && data.users.length) {

        for (var u in data.users) {

          games[data.users[u].game_id] = { 
            id: data.users[u]._id,
            name: data.users[u].name
          }

        }

        views.homepage.currentGames(games);

      }

    },

    // when a new user connects
    onUserConnect: function(data) {

      games[data.game_id] = {
        id: data._id,
        name: data.name
      }
      views.homepage.currentGames(games);

    },

    // when a user disconnects
    onUserDisconnect: function(data) {

      delete games[data.game_id];
      views.homepage.currentGames(games);

    },

    // when we receive an incoming notification
    onNotification: function(data) {

    }
    /*
    ,
    onQuery: function(data) { ... },
    onUserConnect: function(data) { ... },
    onUserDisconnect: function(data) { ... }
    */
    
  });

}