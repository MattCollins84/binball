var PlayerViews = function() {
  
  return (function() {

    return {

      renderPlayerList: function(players) {

        $('#player-list').text("");

        for (var p in players) {
          var p = $("<p />", {"text": players[p], class: "mb10"});
          p.appendTo('#player-list');
        }

      }

    }

  })();

}