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

          var row = scorecard.row(i, game)

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

      },

      leadboard: function(game) {

        $('#leaderboard-table').remove();

        this.leaderboard = [];

        var temp = {};

        var keys = {};

        // for each player
        for (var p in game.players) {

          // get the current total
          var scores = game.scores[p];

          var total = 0;
          for (var s in scores) {

            if (typeof scores[s] != "number") {
              scores[s] = parseInt(scores[s], 10);
            }

            var multiplier = 1;

            if (game.jokers[p] !== false && game.jokers[p] == s) {
              multiplier = 2;
            }

            total += (scores[s] * multiplier);

          }

          // has the joker been played?
          var joker = (game.jokers[p] === false?"no":"yes");

          var max = game.calculateMaximum((3 + scores.length), game.totalRounds, (game.jokers[p] === false?true:false)) + total;
          game.maxScores[p] = max;


          // generate key in this format '<score>-<jokerplayed>-<name>'

          var prefix = "0";
          switch(total.toString().length) {

            case 1:
            prefix = "000";
            break;

            case 2:
            prefix = "00";
            break;

            case 3:
            prefix = "0";
            break;

            default:
            case 4:
            prefix = "";
            break;

          }

          var key = prefix+total.toString()+"-"+game.players[p].toLowerCase().replace(/[^a-zA-Z0-9]/g, "")+"-"+joker+"-"+max;

          temp[key] = game.players[p];

        }

        // start our table
        var table = $("<table/>", {id: "leaderboard-table", class: "table table-bordered"});

        var headingsRow = $("<tr />", {class: "active"});
        $("<th />", {text: "Name", colspan: 2}).appendTo(headingsRow);
        headingsRow.appendTo(table);

        // get the keys
        var keys = Object.keys(temp);
        keys = keys.sort().reverse();

        for (var k in keys) {
          var row = $("<tr />", {class: (keys[k].split("-")[2]=="yes"?"danger":"")});
          var name = $("<td />", {text: temp[keys[k]]});
          name.appendTo(row);
          var theScore = keys[k].split("-")[0].replace(/^0+/, "");
          if (theScore == "") {
            theScore = 0;
          }
          var score = $("<td />", {text: theScore+"/"+keys[k].split("-")[3]});
          score.appendTo(row);

          row.appendTo(table);
        }

        table.appendTo('#leaderboard');

      },

      stats: function(game) {

        // hide all stats
        $('.stats').addClass("hidden");

        if (game.currentRound > game.maxDistance) {
          return alert("Game over, we have a winner!");
        }

        // make round average visible
        $('#round-avg-'+game.currentRound).removeClass("hidden");

        // make round joker %
        $('#joker-pc-'+game.currentRound).removeClass("hidden");

        // make player round average visible
        $('#round-'+game.currentRound+'-player-'+game.currentPlayer+'-avg').removeClass("hidden");

        // make user round joker %
        $('#joker-'+game.currentRound+'-player-'+game.currentPlayer+'-pc').removeClass("hidden");

      }

    }

  })();

}