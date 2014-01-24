var BinBallViews = function() {
  
  return (function() {

    return {

      players: new PlayerViews(),
      dom: new DomViews(),
      game: new GameViews()

    }

  })();

}