var BinBallViews = function() {
  
  return (function() {

    return {

      players: new PlayerViews(),
      game: new GameViews(),
      homepage: new HomepageViews()

    }

  })();

}