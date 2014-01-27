var GameModel = function() {
  
  return (function() {

    return {

      addScore: function(data, callback) {

        $.ajax({
          type: "GET",
          url: "/add/score",
          data: data
        })
        .done(function( res ) {
          
          return callback(res);

        });
        
      },

      jokerMiss: function(data, callback) {

        $.ajax({
          type: "GET",
          url: "/miss/joker",
          data: data
        })
        .done(function( msg ) {
          
          return callback(msg);

        });

      },

      getStats: function(game, callback) {

        $.ajax({
          type: "GET",
          url: "/stats",
          data: { min_round: 3, max_round: (parseInt(game.totalRounds, 10) + 2), emails: game.emails }
        })
        .done(function( html ) {
          
          return callback(html);

        });

      }

    }

  })();

}