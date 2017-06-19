<?php
/**
 * @var \app\helpers\ChallengeSummarizer $summary
 */
    $questions = $summary->getQuestions();
    $results = $summary->getCorrectness();
    $hints = $summary->getHints();
    $points = $summary->getPoints();
    $number = 1;
?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">Общие результаты</h4>
    </div>
    <div role="tabpanel">
        <div class="panel-body">
            <center><p class="lead"><?= $challenge->name ?></p>
            <p><strong>Оценка: <?= $summary->getMark() ? $summary->getTextMark($summary->getMark()) : 'не доступно - слишком мало было дано ответов' ?></strong></p>
            <p><?php $summary->getEmoticon($summary->getMark());?></p>
            <p><strong>Всего набрано баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></p>
            <p>Время выполнения: <?= round($summary->getTime() / 60) ?> мин.</p>
            <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Повторить этот тест</a></p></center>
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
                                <th class="text-left">Вопрос</th>
                                <th class="text-left">Варианты ответа</th>
                                <th class="text-left">Твой ответ</th>
                                <th class="text-left">Объяснение ответа</th>
                                <th class="text-left">Подсказка была?</th>
                                <th class="text-left">Получаешь балл</th>
                            </tr>
                            <tr>
                                <!-- № -->
                                <td class="text-center"><strong><?= $number ?><?php $number++?></strong><br>[<?= $question->id ?>]</td>
                                <!-- Вопрос -->
                                <td class="text-left"><?= $question->text ?></td>
                                <!-- Варианты ответа -->
                                <td class="text-left">

                                        <?php if ($question->question_type_id == 7): ?>
                                            <i class="fa options-text" data-id="options<?= $number?>"></i>
                                        <?php else: ?>
                                            <ul>
                                            <?php $question->getOptionsFinish($question->data); ?>
                                            </ul>
                                        <?php endif;?>

                                </td>
                                <!-- Твой ответ -->
                                <td class="<?= $results[$question->id] ? 'success' : 'danger' ?> text-left">
                                    <?php if ($question->question_type_id == 7): ?>
                                        <i class="fa answer-text" data-id="answer<?= $number?>"></i>
                                    <?php else: ?>
                                        <?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers); ?>
                                    <?php ?>
                                    <?php endif;?>
                                </td>
                                <!-- Объяснение ответа -->
                                <td class="text-left">
                                    <?php if ($question->question_type_id == 7): ?>
                                        <i class="fa explanation-text" data-id="explanation<?= $number?>"></i>
                                    <?php else: ?>
                                        <?= $question->comment ?>
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
                                    <p class="text-center"><center><?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers); ?></center></p>
                                </td>
                            </tr>
                            <!-- Вставка блока с объяснением при 7-м задании -->
                            <tr id="explanation<?= $number?>" style="display: none;">
                                <td colspan="8">
                                    <center><i class="fa explanation-text" data-id="explanation<?= $number?>"></i><strong>Объяснение ответа:</strong></center>
                                    <p class="text-center"><center><?= $question->getCommentFinish($question->data) ?></center></p>
                                </td>
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