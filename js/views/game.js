var GameViews = function() {
  
  return (function() {

    var scorecard = new ScorecardViews();

    return {

      render: function(game) {

        // if the game hasn't started, do nothing
        if (!game.gameStarted) {
          return;
        }

        // hide the player entry stuff
        $(".player-entry").addClass("hidden");

        $("#scorecard").html("");

        // create the table
        var table = scorecard.table(game)

        // create the headers
        var headers = scorecard.headers(game);
        headers.appendTo(table);

        // how many rounds?
        // add the round rows
        for (var i = 0; i < game.totalRounds; i++) {

          var row = scorecard.row(i)

          // cell for each player
          for (var p in game.players) {
            
            var playerCell = scorecard.cell(game, i, p);

            // add to row
            playerCell.appendTo(row);

          }

          row.appendTo(table);

        }

        // total rows
        var totalRow = scorecard.totals(game);

        totalRow.appendTo(table);

        // avg row
        var avgRow = scorecard.averages(game);
        avgRow.appendTo(table);

        // add the table
        table.appendTo("#scorecard");

        $('select.score-input').change(function(e){
          game.addScore(this.id.replace("score", ""), this.value);
        });

      },

      scores: function(game) {

        var obj = {};

        // for each player
        for (var p in game.players) {

          var total = 0;

          var scores = game.scores[p];

          for (var s in scores) {

            var multiplier = 1;

            if (game.jokers[p] == s && game.jokers[p] !== false) {
              multiplier = 2;
            }

            total += (scores[s] * multiplier);

          }

          $('#total-'+p).text(total);

        }

      },

      averages: function(game) {

        var obj = {};

        // for each player
        for (var p in game.players) {

          var total = 0;

          var attempts = game.attempts[p];

          for (var a in attempts) {

            total += attempts[a];

          }

          var avg = (total / attempts.length).toFixed(3);

          if (isNaN(avg)) {
            avg = 0;
          }

          $('#avg-'+p).text(avg);

        }

      }

    }

  })();

}