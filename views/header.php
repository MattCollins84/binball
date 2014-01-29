<?php
	
	require_once("includes/config.php");
  require_once("includes/User.php");

  if (!isset($data)) {
    $data = array();
  }

  $data['user'] = User::getActiveUser();

  // redirect?
  if ($_SESSION['ref'] && $data['user']) {
    $ref = $_SESSION['ref'];
    $_SESSION['ref'] = "";
    header("Location: ".$ref);
    exit;
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BinBall</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">
  
    <!-- Custom CSS -->
    <link href="/css/flatty.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/css/font-awesome.min.css" rel="stylesheet">

    <link rel="icon" 
      type="ico" 
      href="/favicon.ico">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>

    <? if (isset($data['insto']) && $data['insto']): ?>
      <script type="text/javascript" src="https://api.insto.co.uk:3000/lib/client.js"></script>
    <? endif; ?>

    <!-- GAME MECHANICS -->
    <script src="/js/models.js"></script>
    <script src="/js/models/player.js"></script>
    <script src="/js/models/game.js"></script>
  
    <script src="/js/views.js"></script>
    <script src="/js/views/player.js"></script>
    <script src="/js/views/scorecard.js"></script>
    <script src="/js/views/game.js"></script>
    <script src="/js/views/homepage.js"></script>

    <script src="/js/binball.js"></script>
    <script src="/js/binball_homepage.js"></script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-47560579-1', 'binball.org');
      ga('send', 'pageview');

    </script>
  

    <? if ($data['homepage']): ?>
    <script>
      $(document).ready(function() {
        var binball = new BinBallHomepage();
      });
    </script>
    <? endif; ?>
    
  </head>

  <body>

    <div class="navbar navbar-fixed-top navbar-default br0" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        
        <form class="navbar-form navbar-right">
          <?
          if ($data['game_id']) {
            $confirm = "onclick=\"return confirm('This will end your current game, do you want to continue?');\"";;
          }
          ?>
          <a class="btn btn-success" href="/binball/play" <?=$confirm;?>><i class="fa fa-play-circle white"></i> Play BinBall</a>
          <? if ($data['user']): ?>
            <a href="/profile" class="btn btn-success"><i class="fa fa-user fa-lg white"> </i> <?=$data['user']['name'];?></a>
            <a href="/sign-out" class="btn btn-warning"><i class="fa fa-sign-out fa-lg white"></i></a>
          <? else: ?>
            <a href="/auth/facebook" class="btn btn-primary"><i class="fa fa-facebook-square fa-lg white"></i>&nbsp;&nbsp;Connect with Facebook</a>
          <? endif; ?>
        </form>
        
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/"><b>Home</b></a></li>
            <li><a href="/rules"><b>Rules</b></a></li>
            <li><a href="/guide"><b>Guide</b></a></li>
            <li><a href="/history"><b>History</b></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>