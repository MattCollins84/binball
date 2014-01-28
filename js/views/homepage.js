var HomepageViews = function() {
  
  return (function() {

    return {

      currentGames: function(games) {

        $('#livelist').html("");

        for (var g in games) {

          var game = g;
          var id = games[g].id;
          var name = games[g].name;

          var p = $("<p />");

          var a = $("<a />", {id: "game-"+game, href: "/binball/game/"+game+"/share", text: "Spectate game created by "+name});
          a.appendTo(p);
          p.appendTo('#livelist');

        }

        if (Object.keys(games).length) {
          $('#livegames').removeClass("hidden");
          $('#toprow').removeClass("mt");
        } else {
          $('#livegames').addClass("hidden");
          $('#toprow').addClass("mt");
        }

      }

    }

  })();

}