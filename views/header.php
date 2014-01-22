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
    <link href="/css/custom.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="/js/binball.js"></script>
    
  </head>

  <body>

    <div class="navbar navbar-inverse <?=($data['jumbotron']?"mb0":"");?> br0" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">BinBall</a>
        </div>
        
        <form class="navbar-form navbar-right">
          <?
          if ($data['game_id']) {
            $confirm = "onclick=\"return confirm('This will end your current game, do you want to continue?');\"";;
          }
          ?>
          <a class="btn btn-success" href="/binball/play" <?=$confirm;?>><i class="fa fa-play-circle"></i> Play BinBall</a>
          <? if ($data['user']): ?>
            <a href="#" class="btn btn-success"><i class="fa fa-user fa-lg"> </i> <?=$data['user']['name'];?></a>
            <a href="/sign-out" class="btn btn-info"><i class="fa fa-sign-out fa-lg"></i></a>
          <? else: ?>
            <a href="/auth/facebook" class="btn btn-primary"><i class="fa fa-facebook-square fa-lg"></i>&nbsp;&nbsp;Connect with Facebook</a>
          <? endif; ?>
        </form>
        
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/rules">Rules</a></li>
            <li><a href="/guide">Guide</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>