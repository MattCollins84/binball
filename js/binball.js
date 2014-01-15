var BinBall = function() {

  this.players = []; //["Matt", "dave", "Glynn", "Simon", "Dan"];
  this.jokers = [] //[false, false, false, false, false];
  this.scores = [] //[[], [], [], [], []];
  this.attempts = [] //[[], [], [], [], []];
  this.leaderboard = [];

  var parent = this;

  this.generateLeaderboard = function() {

    $('#leaderboard-table').remove();

    this.leaderboard = [];

    var temp = {};

    var keys = {};

    // for each player
    for (var p in this.players) {

      // get the current total
      var scores = this.scores[p];

      var total = 0;
      for (var s in scores) {

        if (typeof scores[s] != "number") {
          scores[s] = parseInt(scores[s], 10);
        }

        var multiplier = 1;

        if (this.jokers[p] !== false && this.jokers[p] == s) {
          multiplier = 2;
        }

        total += (scores[s] * multiplier);

      }

      // has the joker been played?
      var joker = (this.jokers[p] === false?"no":"yes");

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

      var key = prefix+total.toString()+"-"+this.players[p].toLowerCase().replace(/[^a-zA-Z0-9]/g, "")+"-"+joker;

      temp[key] = this.players[p];

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
      var score = $("<td />", {text: theScore});
      score.appendTo(row);

      row.appendTo(table);
    }

    table.appendTo('#leaderboard')

  }

  // add a player to the list
  this.addPlayer = function() {

    var name = $('#player-name').val();

    if (!name) {
      return alert("You must enter a name");
    }

    this.players.push(name);
    this.jokers.push(false);
    this.scores.push([]);
    this.attempts.push([]);
    $('#player-name').val("");
    $('#player-list').text(this.players.join(", "));

  }

  // start the game!
  this.start = function() {

    $(".player-entry").addClass("hidden");
    this.renderScorecard();

  }

  // render our scorecard
  this.renderScorecard = function() {

    // create table
    var table = $("<table/>", {id: "scores-table", class: "table table-bordered"});

    // add the headings
    var headingsRow = $("<tr/>");

    $("<th/>", {"text": ""}).appendTo(headingsRow);
    for (var p in this.players) {
      var playerHeading = $("<th/>", {"text": this.players[p]});
      var joker = $("<img/>", {"id": "joker-"+p, class: "pull-left", src: "/images/joker.png"});
      joker.prependTo(playerHeading);
      (function(player_id){
        joker.bind('click', function() {
          parent.jokerMiss(player_id)
        });
      })(p);
      playerHeading.appendTo(headingsRow);
    }

    headingsRow.appendTo(table);

    var totalRounds = $('#number-of-rounds').val();
    if (typeof totalRounds != "number") {
      totalRounds = parseInt(totalRounds, 10);
    }

    // add the round rows
    for (var i = 0; i < totalRounds; i++) {

      var round = (i+3).toString();

      var opts = {
        id: "round-"+round
      }

      // make the first row active
      if (i == 0) {
        opts.class = "success";
      }

      // add the row
      var row = $("<tr/>", opts);
      $("<td/>", {"text": (i+3).toString(), class: "bold"}).appendTo(row);

      // cell for each player
      for (var p in this.players) {
        
        // drop down
        var ident = "r"+round+"p"+p;
        var scoreInput = $("<select />", {id: "score"+ident, class: "score-input"});

        var o = $("<option />", {value: "", text: "- SELECT -"});
        o.appendTo(scoreInput);

        var attempt = 1;
        for (r = i+3; r > 0; r--) {
          var o = $("<option />", {value: r, text: "Attempt "+attempt});
          o.appendTo(scoreInput);
          attempt++;
        }

        var o = $("<option />", {value: 0, text: "- FAILED -"});
        o.appendTo(scoreInput);

        
        var playerCell = $("<td/>", {"html": scoreInput, id: ident});

        // joker icon
        var jokerIcon = $("<button />", {value: 0, text: "J", class: "btn btn-sm btn-warning pull-right btn-joker-"+p, id: "joker-"+ident});
        
        (function(i) {
          jokerIcon.bind("click", function() {
            parent.playJoker(i);
          });
        })(ident);

        jokerIcon.prependTo(playerCell);

        // add to row
        playerCell.appendTo(row);
      }

      row.appendTo(table);

    }

    // total rows
    var row = $("<tr/>", {id: "total-row"});
    $("<td/>", {"text": "Totals", class: "bold"}).appendTo(row);

    for (var p in this.players) {
      
      var span = $("<span />", {id: "total-"+p, text: "0"});

      var totalCell = $("<td/>", {html: span, class: "bold"});
      
      totalCell.appendTo(row);

    }

    row.appendTo(table);

    // avg row
    var row = $("<tr/>", {id: "avg-row"});
    $("<td/>", {"text": "Attempt Avg.", class: "bold"}).appendTo(row);

    for (var p in this.players) {
      
      var span = $("<span />", {id: "avg-"+p, text: "0"});

      var totalCell = $("<td/>", {html: span, class: "bold"});
      
      totalCell.appendTo(row);

    }

    row.appendTo(table);

    table.appendTo("#scorecard");

    $('select.score-input').change(function(e){
      parent.addScore(this.id.replace("score", ""), this.value);
    });

  }

  // play a joker
  this.playJoker = function(player_id) {
    
    var original = player_id;

    var bits = player_id.split(/[a-zA-Z]/);

    player_id = bits[bits.length-1];
    var round_id = parseInt(bits[bits.length-2], 10);

    $('.btn-joker-'+player_id).addClass('hidden');
    $('#joker-r'+round_id+"p"+player_id).removeClass('hidden');
    $('img#joker-'+player_id).addClass('hidden');

    this.jokers[player_id] = parseInt((round_id-3), 10);

    parent.addScore("r"+round_id+"p"+player_id, $("select#scorer"+round_id+"p"+player_id).val());

  }

  // calculate a score
  this.addScore = function(player_id, score) {

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
      console.log("You cannot alter this score yet");
      $('select#scorer'+round_id+'p'+player_id).val("")
    }

    else {
      this.scores[player_id][r] = score;
      this.attempts[player_id][r] = attempt;
    }
    

    var newScore = 0;
    for (var s in this.scores[player_id]) {

      s = parseInt(s, 10);

      var multiplier = 1;

      if (this.jokers[player_id] !== false && this.jokers[player_id] == s) {
        multiplier = 2;
      }

      newScore += (this.scores[player_id][s] * multiplier);

    }
    
    $('#total-'+player_id).text(newScore);

    // average attempts
    var l = this.attempts[player_id].length;
    var total_attempts = 0;
    for (var a in this.attempts[player_id]) {
      if (typeof this.attempts[player_id][a] != "number") {
        this.attempts[player_id][a] = parseInt(this.attempts[player_id][a]);
      }

      total_attempts += this.attempts[player_id][a];
    }

    var avg = (total_attempts / l).toFixed(3);

    $('#avg-'+player_id).text(avg);

    // have we finished this line?
    var nextRound = true;
    $('select[id^=scorer'+round_id+']').each(function(e, x) {
      if (this.value == "") {
        nextRound = false;
      }
    });


    if (nextRound) {
      $('#round-'+round_id).removeClass('success');
      $('#round-'+(round_id+1)).addClass('success');
    }

    parent.generateLeaderboard();

  }

  this.jokerMiss = function(player_id) {
    $('.btn-joker-'+player_id).addClass('hidden');
    $('img#joker-'+player_id).addClass('hidden');
    this.jokers[player_id] = 9999;
  }

}