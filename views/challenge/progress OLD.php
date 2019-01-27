<?php
    use yii\widgets\ActiveForm;
    use app\widgets\AnswerEditor;

/**
 * @var \app\helpers\ChallengeSummarizer $summary
 * @var \app\models\Question $question
 * @var \app\models\Challenge $challenge
 * @var \app\helpers\ChallengeSession $session
 */

    $currentQuestion = $session->getCurrentQuestionNumber();
    $totalQuestions = $challenge->getQuestionsCount();

    $question = $session->getCurrentQuestion();

    $summary = \app\helpers\ChallengeSummarizer::fromSession( $session );


?>

<div class="panel panel-default">
    <?php if($challengeItem): ?>
    <p><center><img src="/i/<?= $challengeItem->name ? $challengeItem->name : "no_image" ; ?>.png" /></center></p>
    <?php endif; ?>
    <div class="panel-heading">
        <?= $challenge->name ?>
        <div class="pull-right text-right" style="width: 30%;">
            Задание <?= $currentQuestion + 1 ?> из <?= $totalQuestions ?>
        </div>
        <div class="progress">
            <?php if( $challenge->settings->immediate_result ): ?>
                <?php $correctness = $summary->getCorrectness() ?>
                <?php foreach( $summary->getQuestions() as $i => $q ): ?>

                    <?php $comment = ["<strong>Задание ".( $i+1 )." из $totalQuestions</strong>"] ?>
                    <?php if( $correctness[$q->id] ):?>
                        <?php $comment[] = 'Выполнено правильно' ?>
                    <?php else: ?>
                        <?php $comment[] = 'Выполнено с ошибками' ?>
                        <?php $comment[] = $q->getComment(true) ?>
                        <?php if(count($mistakes = $summary->getMistakes($q))) $comment[] = '<ul><li>'.implode( '<li>', $mistakes ).'</ul>' ?>
                    <?php endif; ?>

                    <div
                        class="progress-bar progress-bar-<?= $correctness[$q->id] ? 'success' : 'danger' ?>"
                        style="width: <?= floor( 100 / $totalQuestions ) ?>%"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        data-html="true"
                        title="<?= htmlspecialchars( implode( '<p></p>', $comment ) ) ?>"
                    ></div>
                <?php endforeach;?>
            <?php else: ?>
                <div class="progress-bar progress-bar-info" style="width: <?= floor( $currentQuestion / $totalQuestions * 100) ?>%"></div>
            <?php endif;?>
        </div>
    </div>
    <div class="panel-body">
        <?= $question->getText() ?>
        <?php if (!empty($_SESSION['pre'])) { ?>

        <?php } ?>
          <?php $form = ActiveForm::begin([
              'action' => ['challenge/answer', 'id' => $challenge->id],
              'method' => 'post'
          ]); ?>

        <?php if (empty($_SESSION['pre'])) { ?>
              <?php echo AnswerEditor::widget([
                  'name' => 'answer',
                  'question' => $question,
              ]) ?>
            <div class="hint-content" style="display: none">
                <div class="panel panel-default" style="border: solid; border-color: #00a5bb">
                    <div class="panel-heading">
                        <div class="general-item-list">
                            <div class="item">
                                <div class="item-head">
                                    <div class="item-details">
                                        <img class="item-pic" src="/i/hintemoticon.jpg">
                                        <div class="item-name primary-link"><strong>Кошка подсказывает:</strong></div>
                                    </div>
                                </div>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } else { ?>
            <?php echo AnswerEditor::widget([
                'name' => 'answer',
                'question' => $question,
                'answer' => $_SESSION['pre'],
                'immediate_result' => $challenge->settings->immediate_result,
            ]) ?>
            <?php if ($question->question_type_id !== \app\models\QuestionType::TYPE_ASSOC_TABLE && $question->question_type_id !== \app\models\QuestionType::TYPE_THREE_QUESTION) { ?>
                <strong>Твой ответ <?= $question->check($_SESSION['pre']) ? 'ПРАВИЛЬНЫЙ!' : 'НЕПРАВИЛЬНЫЙ...' ?></strong><br><br>
                <div class="comment-content">
                    <div class="panel panel-default" style="border: solid; border-color: <?= $question->check($_SESSION['pre']) ? '#219187' : '#F3565D'?>">
                        <div class="panel-heading">
                            <div class="general-item-list">
                                <div class="item">
                                    <div class="item-head">
                                        <div class="item-details">
                                            <img class="item-pic" src="/i/hintemoticon.jpg">
                                            <div class="item-name primary-link"><strong>Кошка объясняет:</strong></div>
                                        </div>
                                    </div>
                                    <span><?= json_decode($question->comment) ? implode("<br>", yii\helpers\Json::decode($question->comment)) : $question->comment ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <?php } ?>

            <div class="row question-buttons">
               <div class="col-xs-6 col-md-6 text-left">
                  <?php if (empty($_SESSION['pre'])) { ?>
                    <input type="submit" class="btn btn-success" value="Ответить">
                  <?php } else { ?>
                    <a href="<?= \yii\helpers\Url::toRoute(['challenge/continue', 'id' => $challenge->id]) ?>" class="btn btn-success ">Продолжить</a>
                  <?php } ?>
               </div>
               <div class="col-xs-6 col-md-6 text-right">
                  <?php if (empty($_SESSION['pre'])) { ?>
                      <?php if ($question->question_type_id !== \app\models\QuestionType::TYPE_THREE_QUESTION) { ?>
                          <a href="#" class="btn btn-primary hint-button">Подсказать</a>
                      <?php } ?>
                      <?php if( $session->getCurrentQuestionNumber() < $challenge->getQuestionsCount() - 1 ): ?>
                          <a href="<?= \yii\helpers\Url::toRoute(['challenge/skip', 'id' => $challenge->id]) ?>" class="btn btn-warning ">Пропустить</a>
                      <?php endif; ?>
                  <?php } ?>
                  <a href="<?= \yii\helpers\Url::toRoute(['challenge/finish', 'id' => $challenge->id]) ?>" class="btn btn-danger">Завершить</a>
               </div>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

<script>
    $(function() {

        function showHint(hint, hide=true) {
            $('.hint-content span').html(hint);
            $('.hint-content').show();
            if (hide) {
                $('.hint-button').hide();
            }
        }

        $('.hint-button').click( function() {
            var type = <?= $question->question_type_id ?>;
            var id = $(this).data('id');
            if (type === <?= \app\models\QuestionType::TYPE_THREE_QUESTION ?>) {
                $.get('<?= \yii\helpers\Url::to(['challenge/hint', 'id' => $challenge->id]) ?>', function(data) {
                    var hints = JSON.parse(data);
                    sessionStorage.setItem('quest_num', id);
                    showHint(hints[id], false);
                });
            } else {
                $.get('<?= \yii\helpers\Url::to(['challenge/hint', 'id' => $challenge->id]) ?>', function(data) {
                    showHint(data);
                });
            }
            return false;
        });

        <?php if( $session->isHintUsed() ): ?>
            var type = <?= $question->question_type_id ?>;
            if (type === <?= \app\models\QuestionType::TYPE_THREE_QUESTION ?>) {
                var id = sessionStorage.getItem('quest_num');
                var hints = JSON.parse(<?= \yii\helpers\Json::encode( $session->hint()) ?>);
                showHint(hints[id], false);
            } else {
                showHint(<?= \yii\helpers\Json::encode( $session->hint()) ?>);
            }
        <?php endif;?>

        <?php if($challenge->settings->immediate_result ): ?>
        $('.progress-bar[data-toggle="tooltip"]').tooltip();
        <?php endif; ?>
    });
</script>