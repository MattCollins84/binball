<div class='jumbotron jt-small mb60'>
  <div class="container mb20">
    <div class="row">
      
      <div class="col-md-4">
        <img src="/images/logo-350.png" />
      </div>

      <div class="col-md-8">
        <h1 class="mt0 headline">Welcome to BinBall.<br /><span>Breaktime will never be the same...</span></h1>
        <p><i class="fa fa-quote-left"></i></p>
        <p>BinBall changed my life, I don't know how I got through my working day before I discovered BinBall...</p>
        <p>- Dave Fender</i></p>
        <p><i class="fa fa-quote-right pull-right"> </i></p>
      </div>
      
    </div>
  </div>
</div>

<div class="container mb20">
  
  <div class="row feature">
    <div class="col-md-7">
      <h1 class="headline">What is BinBall? <span>And how can I play?</span></h1>
      <p>BinBall is a (mostly) office-based recreational activity designed to break the boredom of your typical workplace. It was conceived on December 23rd 2013 by some bored, and forward-thinking individuals looking to kill some time before the holidays.</p>
      <p>To play BinBall, you will need the following:</p>
      <ul>
        <li>At least one friend, or willing work colleague</li>
        <li>A suitable bin, we suggest a waste paper bin about 1' in both height and diameter</li>
        <li>A suitable ball, we suggest something tennis ball sized, ideally soft (e.g. a stress ball)</li>
      </ul>
      
      <? if ($data['user']): ?>
        <p><a class="btn btn-success" href="/binball/play"><i class="fa fa-play-circle"></i> Play BinBall</a></p>
      <? else: ?>
        <p>To use our handy scorecard, you will need to authenticate via Facebook. This is to record player statistics, and we will not share your data with anyone else.</p>
        <p><a href="/auth/facebook" class="btn btn-primary"><i class="fa fa-facebook-square fa-lg"></i>&nbsp;&nbsp;Connect with Facebook</a></p>
      <? endif; ?>

    </div>
    <div class="col-md-5">
      <img src="/images/equipment.png" />
    </div>
  </div>

  <div class="row feature">
    <div class="col-md-5">
      <img src="/images/howtoplay.png" />
    </div>
    <div class="col-md-7">
      <h1 class="headline">Are there any rules? <span>Or maybe a scoring system?</span></h1>
      <p>The OFFICIAL rules of BinBall are evolving all the time but all rules must adhere to our philosophy:</p>
      <p><em>BinBall is all about competitively throwing balls into bins to score points. The player with the most points at the end of the game wins!</em></p>
      <p>For a complete run down of the current OFFICIAL BinBall rules and and regulations, click <a href='/rules'>here</a>.</p>

      <? if ($data['user']): ?>
        <p><a class="btn btn-success" href="/binball/play"><i class="fa fa-play-circle"></i> Play BinBall</a></p>
      <? else: ?>
        <p>To use our handy scorecard, you will need to authenticate via Facebook. This is to record player statistics, and we will not share your data with anyone else.</p>
        <p><a href="/auth/facebook" class="btn btn-primary"><i class="fa fa-facebook-square fa-lg"></i>&nbsp;&nbsp;Connect with Facebook</a></p>
      <? endif; ?>
    </div>
  </div>

  <div class="row feature">
    <div class="col-md-7">
      <h1 class="headline">Can I win anything?</h1>
      <p>Unfortunately we cannot offer any prizes for your participation in BinBall, however, every time a game is completed the person with the most points shall be declared the <b>BinBall Wizard</b>, until he/she is next defeated in a game of BinBall.</p>
      <p>It is customary that the person currently holding the title of <b>BinBall Wizard</b> shall not have to make his own beverage whilst in the working environment.</p>
      <p>Keep an eye-out for OFFICIAL BinBall competitions in the future...</p>
      <? if ($data['user']): ?>
        <p><a class="btn btn-success" href="/binball/play"><i class="fa fa-play-circle"></i> Play BinBall</a></p>
      <? else: ?>
        <p>To use our handy scorecard, you will need to authenticate via Facebook. This is to record player statistics, and we will not share your data with anyone else.</p>
        <p><a href="/auth/facebook" class="btn btn-primary"><i class="fa fa-facebook-square fa-lg"></i>&nbsp;&nbsp;Connect with Facebook</a></p>
      <? endif; ?>
    </div>
    <div class="col-md-5">
      <img src="/images/binball-wizard-500.png" />
    </div>
  </div>

</div>