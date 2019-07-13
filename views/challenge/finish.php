<?php
/**
 * @var \app\helpers\ChallengeSummarizer $summary
 */
use app\models\Question;
use yii\bootstrap\ActiveForm;

$questions = $summary->getQuestions();
$results = $summary->getCorrectness();
$hints = $summary->getHints();
$points = $summary->getPoints();
$number = 1;
?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">Общие результаты</h4>
        <?php if( Yii::$app->session->hasFlash('dailyQuest') ): ?>
            <br>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('dailyQuest'); ?>
            </div>
        <?php endif;?>
        <?php if( Yii::$app->session->hasFlash('success') ): ?>
            <br>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif;?>
    </div>
    <div role="tabpanel">
        <div class="panel-body">
            <center><p class="lead"><?= $challenge->name ?></p>
                <p><strong>Оценка: <?= $summary->getMark() ? $summary->getTextMark($summary->getMark()) : 'не доступно - слишком мало было дано ответов' ?></strong></p>
                <p><?php $summary->getEmoticon($summary->getMark());?></p>
                <?php if($challengeItem): ?>
                    <p><center><img src="/i/<?= $challengeItem->name ? $challengeItem->name : "no_image" ; ?>.png" /></center></p>
                <?php endif; ?>
                <p><strong>Всего набрано баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></p>
                <p>Время выполнения: <?= round($summary->getTime() / 60) ?> мин.</p>

                <!--<p><a class="btn btn-lg btn-success" href="<?php // \yii\helpers\Url::toRoute(['challenge/save', 'id' => $challenge->id]) ?>">Кинуть на стену в VK</a></p>-->
                <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Повторить этот тест</a></p>
                <?php if ($challenge->element_id == 1): ?>
                    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['/feed']) ?>">Продолжить кушать</a></p>
                    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['/clean']) ?>">Или сделать уборку</a></p>
                <?php endif; ?>
                <?php if ($challenge->element_id == 2): ?>
                    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['/clean']) ?>">Продолжить уборку</a></p>
                    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['/feed']) ?>">Или покушать</a></p>
                <?php endif; ?>
            </center>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Подробные результаты</h4>
    </div>
    <div aria-labelledby="summaryHead">
        <div class="panel-body">
            <table class="table table-finish table-hover table_results">
                <?php foreach ($summary->answers as $realQuestionId => $answer):?>
                    <?php foreach( $questions as $i => $question ): ?>
                        <?php  if ($realQuestionId == $question['id']):?>
                            <tr>
                                <th class="text-left">№ в тесте<br>[№ в системе]</th>
                                <?php if ($question->question_type_id !== \app\models\QuestionType::TYPE_THREE_QUESTION): ?>
                                    <th class="text-left">Вопрос</th>
                                    <th class="text-left">Варианты ответа</th>
                                <?php else: ?>
                                    <!--<th class="text-left">Текст</th>-->
                                    <th colspan="2" class="text-left">Вопросы</th>
                                <?php endif;?>
                                <th class="text-left">Твой ответ</th>
                                <th class="text-left">Объяснение ответа</th>
                                <th class="text-left">Подсказка была?</th>
                                <th class="text-left">Получаешь балл</th>
                            </tr>

                            <?php if ($question->question_type_id !== \app\models\QuestionType::TYPE_THREE_QUESTION): ?>
                                <tr>
                                    <!-- № -->
                                    <td class="text-center"><strong><?= $number ?><?php $number++?></strong><br>[<?= $question->id ?>]</td>
                                    <!-- Вопрос -->
                                    <td class="text-left"><?= $question->text ?></td>
                                    <!-- Варианты ответа -->
                                    <td class="text-left">
                                        <?php if ($question->question_type_id == \app\models\QuestionType::TYPE_ASSOC_TABLE): ?>
                                            <i class="fa options-text" data-id="options<?= $number?>"></i>
                                        <?php else: ?>
                                            <ul>
                                                <?php $question->getOptionsFinish($question->data); ?>
                                            </ul>
                                        <?php endif;?>
                                    </td>
                                    <!-- Твой ответ -->
                                    <td class="<?= $results[$question->id] ? 'success' : 'danger' ?> text-left">
                                        <?php if ($question->question_type_id == \app\models\QuestionType::TYPE_ASSOC_TABLE): ?>
                                            <i class="fa answer-text" data-id="answer<?= $number?>"></i>
                                        <?php else: ?>
                                            <?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers, $question, false); ?>
                                            <?php ?>
                                        <?php endif;?>
                                    </td>
                                    <!-- Объяснение ответа -->
                                    <td class="text-left">
                                        <?php if ($question->question_type_id == \app\models\QuestionType::TYPE_ASSOC_TABLE): ?>
                                            <i class="fa explanation-text" data-id="explanation<?= $number?>"></i>
                                        <?php else: ?>
                                            <?= json_decode($question->comment) ? implode("<br>", yii\helpers\Json::decode($question->comment)) : $question->comment ?>
                                        <?php endif;?>
                                    </td>
                                    <!-- Подсказка была? -->
                                    <td class="<?= !$hints[$question->id] ? 'success' : 'danger' ?>"><center><?= $question->getRightHintText($hints[$question->id], $results[$question->id], $question->question_type_id) ?></center></td>
                                    <!-- Получаешь балл -->
                                    <td class="text-center"> <strong><?= $points[$question->id] ?> </strong></td>
                                </tr>
                                <!-- Вставка блока с опциями при 7-м задании -->
                                <tr id="options<?= $number?>" style="display: none;">
                                    <td colspan="8">
                                        <center><i class="fa options-text" data-id="options<?= $number?>"></i><strong>Варианты ответа:</strong></center>
                                        <p class="text-center"><center><?php $question->getOptionsFinish($question->data); ?></center></p>
                                    </td>
                                </tr>
                                <!-- Вставка блока с ответом при 7-м задании -->
                                <tr id="answer<?= $number?>" style="display: none;">
                                    <td colspan="8">
                                        <center><i class="fa answer-text" data-id="answer<?= $number?>"></i><strong>Твой ответ:</strong></center>
                                        <p class="text-center"><center><?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers, $question, false); ?></center></p>
                                    </td>
                                </tr>
                                <!-- Вставка блока с объяснением при 7-м задании -->
                                <tr id="explanation<?= $number?>" style="display: none;">
                                    <td colspan="8">
                                        <center><i class="fa explanation-text" data-id="explanation<?= $number?>"></i><strong>Объяснение ответа:</strong></center>
                                        <p class="text-center"><center><?= $question->getCommentFinish($question->data) ?></center></p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <!-- 3 Вопроса -->
                                <?php $qData = yii\helpers\Json::decode($question->data);
                                $qResults = yii\helpers\Json::decode($results[$question->id]);
                                $qComments = json_decode($question->comment) ? yii\helpers\Json::decode($question->comment) : [$question->comment, $question->comment, $question->comment];
                                $qAnswers = json_decode($summary->answers[$question->id]) ? yii\helpers\Json::decode($summary->answers[$question->id]) : [$summary->answers[$question->id], $summary->answers[$question->id], $summary->answers[$question->id]];
                                $qHints = $hints[$question->id];
                                $qIter = 0;
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center"><?= $question->text ?> </td>

                                </tr>
                                <?php foreach ($qData['question'] as $qDataQuestion): ?>

                                    <tr>
                                        <td class="text-center"><strong><?= $number ?><?php $number++?></strong><br>[<?= $question->id ?>]</td>
                                        <!-- Текст -->
                                        <!--<td class="text-left"><?php $question->text ?> </td>-->
                                        <!-- Вопросы -->
                                        <td colspan="2" class="text-left">
                                            <?= \yii\helpers\Html::encode($qDataQuestion) ?>
                                        </td>
                                        <!-- Твой ответ -->
                                        <td class="<?= $qResults[$qIter] ? 'success' : 'danger' ?> text-left">
                                            <?= \yii\helpers\Html::encode($qAnswers[$qIter][0]) ?>
                                        </td>
                                        <!-- Объяснение ответа -->
                                        <td class="text-left">
                                            <?= $qComments[$qIter] ?>
                                        </td>
                                        <!-- Подсказка была? -->
                                        <td class="<?= isset($qHints[$qIter]) && $qHints[$qIter]  ? 'danger' : 'success' ?>"></td>
                                        <?php if ($qIter === 0): ?>
                                            <!-- Получаешь балл -->
                                            <td rowspan="3" class="text-center" style="vertical-align: middle;"><strong><?= $points[$question->id] ?> </strong></td>
                                        <?php endif;?>
                                    </tr>
                                    <?php $qIter++; ?>
                                <?php endforeach; ?>
                            <?php endif;?>
                            <tr>
                            <th colspan="7">
                                <?php // $numberOfPupils = 0; ?>

                                <br>
                            </th>
                            </tr>
                        <?php endif;?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr>
                    <?php for($i = 0; $i < 6; $i++):?>
                        <?php echo '<td></td>';?>
                    <?php endfor;?>
                    <td class="text-center"><strong>Всего баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></td>
                </tr>
            </table>
        </div>
    </div>
    <script style="opacity: 1;">
        $(".table_results td .options-text, .table_results td .answer-text, .table_results td .correct-answer-text, .table_results td .explanation-text").click(function(){
            var $tr_id = $(this).attr('data-id');
            $('#'+$tr_id).toggle('slow');
        });
        setTimeout(function(){
            anim_ball(10);
        },500);
    </script>
</div>


