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

<div class="panel panel-default">
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            <?= $challenge->name ?>

        </h4>
    </div>
    <div role="tabpanel">
        <div class="panel-body">
            <center><p class="lead"><?= $challenge->description ?></p>

            <p><strong>Оценка: <?= $summary->getMark() ? $summary->getTextMark($summary->getMark()) : 'не доступно' ?></strong></p>
            <p><?php $summary->getEmoticon($summary->getMark());?></p>
            <p><strong>Всего набрано баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></p>
            <p>Время выполнения: <?= round($summary->getTime() / 60) ?> мин.</p>

            <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Повторить этот тест</a></p></center>

        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="summaryHead">
        <h4 class="panel-title">
            <a role="button" data-toggle="collapse" href="#summary" aria-expanded="true" aria-controls="summary">
                Подробные результаты

            </a>
             
        </h4>
    </div>
    <div id="summary" class="panel-collapse" role="tabpanel" aria-labelledby="summaryHead">
        <div class="panel-body">
            <table class="table table-condensed table-hover">
                <tr>
                    <th class="col-md-1 text-center">№ в тесте<br>[№ в системе]</th>
                    <th class="col-md-7">Вопрос</th>
                    <th class="col-md-7">Варианты ответа</th>
                    <th class="col-md-7 text-center">Твой ответ</th>
                    <th class="col-md-7 text-center">Правильный ответ</th>
                    <th class="col-md-1 text-center">Объяснение ответа</th>
                    <th class="col-md-2 text-center">Твой ответ был...</th>
                    <th class="col-md-1 text-center">Подсказка была?</th>
                    <th class="col-md-1 text-center">Получаешь балл</th>
               </tr>
                <?php foreach ($summary->answers as $realQuestionId => $answer):?>
                <?php foreach( $questions as $i => $question ): ?>
                    <?php  if ($realQuestionId == $question['id']):?>
                <tr>
                    <td class="text-center"><strong><?= $number ?><?php $number++?></strong><br>[<?= $question->id ?>]</td>
                    <td class="text-left"><?= $question->text ?></td>
                    <td class="text-left">
                        <ul>
                            <?php $question->getOptionsFinish($question->data); ?>
                        </ul>
                    </td>

                    <td class="<?= $results[$question->id] ? 'success' : 'danger' ?>"><center><?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers); ?></center></td>

                    <td class="text-left">
                        <ul>
                             <?php $question->getCorrectAnswersFinish($question->data, $question->question_type_id)?>
                        </ul>
                    </td>
                    <td class="text-left"><?= $question->getCommentFinish($question->data); ?></td>
                    <td class="<?= $results[$question->id] ? 'success' : 'danger' ?>"><center><?= $results[$question->id] ? 'Правильный!' : 'Неправильный...' ?></center></td>
                    <td class="<?= !$hints[$question->id] ? 'success' : 'danger' ?>"><center><?= $question->getRightHintText($hints[$question->id], $results[$question->id]) ?></center></td>
                    <td class="text-center"><?= $points[$question->id] ?></td>
                </tr>
                            <?php endif;?>
                <?php endforeach; ?>
                <?php endforeach; ?>
                <tr>
                    <?php for($i = 0; $i < 8; $i++):?>
                        <?php echo '<td></td>';?>
                    <?php endfor;?>

                    <td class="text-center"><strong>Всего баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></td>
                </tr>
            </table>

        </div>
        <div class="table_results" style="opacity: 1;">

            <table class="table-finish">

                <tr>
                    <td>NEED<i class="fa question-text" data-id="trq_<?= $number?>"></i>
                        <i class="fa explanation-text" data-id="trv_<?= $number?>"></i></td>
                </tr>
                <tr id="trq_<?= $number?>" style="display: none;">
                    <td colspan="4">
                        <p>QUESTION TEXT</p>
                    </td>
                </tr>
                <tr id="trv_<?= $number?>" style="display: none;">
                    <td colspan="4">
                        <p>EXPLANATION TEXT</p>
                    </td>
                </tr>


            </table>
        </div>
    </div>
</div>