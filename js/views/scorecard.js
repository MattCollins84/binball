var ScorecardViews = function() {
  
  return (function() {

    return {

      table: function(game) {

        var table = $("<table/>", {id: "scores-table", class: "table table-bordered"});

        return table;

      },

      headers: function(game) {

        var headers = $("<tr/>");

        $("<th/>", {"text": "#"}).appendTo(headers);
        for (var p in game.players) {
          var playerHeading = $("<th/>", {"text": game.players[p]});
          
          if (game.jokers[p] === false) {
            var joker = $("<button />", {"id": "joker-"+p, class: "pull-right btn btn-danger", text: "JF", "data-toggle": "tooltip", "title": "Joker Failed" });
            joker.tooltip({placement: "left"});
            joker.prependTo(playerHeading);
            (function(player_id){
              joker.bind('click', function() {
                game.jokerMiss(player_id)
              });
            })(p);
          }

          playerHeading.appendTo(headers);
        }

        return headers;

      },

      row: function(round, game) {

        var opts = {
          id: "round-"+(round+3)
        }

        // make the first row active
        if ((round+3) == game.currentRound) {
          opts.class = "success";
        }

        // add the row
        var row = $("<tr/>", opts);
        $("<td/>", {"text": (round+3).toString(), class: "bold"}).appendTo(row);

        return row;

      },

      cell: function(game, round, player) {

        round += 3;
        var roundIndex = round - 3;

        var disabled = (game.creator?false:"disabled");

        // drop down
        var ident = "r"+round+"p"+player;
        var scoreInput = $("<select />", {id: "score"+ident, class: "score-input", disabled: disabled});

        var o = $("<option />", {value: "", text: "- SELECT -"});
        o.appendTo(scoreInput);

        // current attempts
        var attempts = game.attempts[player];

        var attempt = 1;
        for (r = round; r > round-3; r--) {
          var selected = false;

          if (typeof attempts[roundIndex] != "undefined" && attempts[roundIndex] == attempt) {
            selected = "selected";
          }

          var o = $("<option />", {value: r, text: "Attempt "+attempt, selected: selected});
          o.appendTo(scoreInput);
          attempt++;
        }

        var failSelected = false;
        
        if (game.scores[player][roundIndex] == 0) {
          failSelected = "selected";
        }

        var o = $("<option />", {value: 0, text: "- FAILED -", selected: failSelected});
        o.appendTo(scoreInput);

        
        var playerCell = $("<td/>", {"html": scoreInput, id: ident});

        // do we need to render the joker icon?
        if (game.jokers[player] == roundIndex || game.jokers[player] === false) {
          
          // joker icon
          var jokerIcon = $("<button />", {value: 0, text: "J", class: "btn btn-sm btn-warning pull-right btn-joker-"+player, id: "joker-"+ident, "data-toggle": "tooltip", "title": "Play Joker"});
          
          if (game.jokers[player] === false && game.creator) {
            
            jokerIcon.tooltip({placement: "left"});
            (function(i) {
              jokerIcon.bind("click", function() {
                game.playJoker(i);
              });
            })(ident);
            
          }

          jokerIcon.prependTo(playerCell);
        }

        return playerCell;

      },

      totals: function(game) {

        var row = $("<tr/>", {id: "total-row"});
        $("<td/>", {"text": "Totals", class: "bold"}).appendTo(row);

        for (var p in game.players) {
          
          var span = $("<span />", {id: "total-"+p, text: "0"});

          var totalCell = $("<td/>", {html: span, class: "bold"});
          
          totalCell.appendTo(row);

        }

        return row;

      },
      averages: function(game) {

        var row = $("<tr/>", {id: "avg-row"});
        $("<td/>", {"text": "Attempt Avg.", class: "bold"}).appendTo(row);

        for (var p in game.players) {
          
          var span = $("<span />", {id: "avg-"+p, text: "0"});

          var totalCell = $("<td/>", {html: span, class: "bold"});
          
          totalCell.appendTo(row);

        }

        return row;

      }

    }

  })();

}