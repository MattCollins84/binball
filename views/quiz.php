<script>
  var quiz;

  $(document).ready(function(){

    quiz = new Quizr(<?=json_encode($data['quiz']);?>);

  });

</script>

<div class="container card start-card active">
  <div class="row" id='user-container'>  
    
  </div>
</div>

  
  <?
  /***
    Generate question cards
  ***/
    $progress = 0;
  ?>
  <? foreach ($data['quiz']['rounds'] as $round_key => $round): ?>
      
    <div class="container card round-card">
      <div class="row">  
        <div class="col-md-12">
          <h1>Round <?=($round_key+1);?></h1>
          <? if ($round_key > 0): ?>
            <h4 class='waiting'>Waiting for other players to finish...</h4>
          <? endif; ?>
          <!-- <button type="button" class="btn btn-lg btn-block btn-success" onclick='quiz.startRound()'>Carry on</button> -->
        </div>
      </div>
      <div class="row hidden" id='progress-container-<?=$progress;?>'>  
        <div class="col-md-12">
          <h3>Get ready...</h3>
          <div class="progress">
            <div id='progress-<?=$progress;?>' class="progress-bar timer" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </div>
      </div>
    </div>
    <? $progress++; ?>

    <? foreach ($round['questions'] as $question_key => $question): ?>
    <?
    $answerArray = array($question['answer'], $question['wrong']);
    shuffle($answerArray);
    ?>
      <div class="container card question-card">
        
        <div class="row">  
          <div class="col-md-12">
            <h2><?=$question['text'];?></h2>
          </div>
        </div>

        <div class="row">  
          
          <div class="col-md-4">
            <button type="button" class="btn btn-lg btn-block btn-success" onclick='quiz.answer(<?=$round_key;?>, <?=$question_key;?>, "<?=$answerArray[0];?>");'><?=$answerArray[0];?></button>
          </div>

          <div class="col-md-4">
            <div class="progress">
              <div id='progress-<?=$progress;?>' class="progress-bar timer" role="progressbar" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <button type="button" class="btn btn-lg btn-block btn-success" onclick='quiz.answer(<?=$round_key;?>, <?=$question_key;?>, "<?=$answerArray[1];?>");'><?=$answerArray[1];?></button>
          </div>

        </div>

      </div>
      <? $progress++; ?>

    <? endforeach; ?>   

  <? endforeach; ?>

<div class="container card end-card">
  <div class="row">  
    <div class="col-md-12">
      <h1>End of quiz!</h1>
      <h4 class='waiting'>Waiting for other players to finish...</h4>
      <!-- <button type="button" class="btn btn-lg btn-block btn-success" onclick='quiz.startRound()'>Carry on</button> -->
    </div>
  </div>
  <div class="row hidden" id='progress-container-<?=$progress;?>'>  
    <div class="col-md-12">
      <h3>Calculating scores...</h3>
      <div class="progress">
        <div id='progress-<?=$progress;?>' class="progress-bar timer" role="progressbar" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container card score-card">
  <div class="row">  
    
    <div class="col-md-8">
      <h1>The scores</h1>
      <div id='final-scores'></div>
    </div>

    <div class="col-md-4">
      <h1>Share this</h1>
      <p>Or some bollocks like that</p>
    </div>

  </div>
</div>