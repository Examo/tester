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
    <div class="panel-heading" role="tab">
        <h4 class="panel-title">
            Подробные результаты теста
        </h4>
    </div>
    <div id="summary" class="panel-collapse" role="tabpanel">
        <div class="table-finish">
            <div class="table-row">
                <div class="table-cell-top"><strong>№ в тесте</strong><br>[№ в системе]</div>
                <div class="table-cell-top"><strong>Вопрос</strong></div>
                <div class="table-cell-top"><strong>Варианты ответа</strong></div>
                <div class="table-cell-top"><strong>Твой ответ</strong></div>
                <div class="table-cell-top"><strong>Правильный ответ</strong></div>
                <div class="table-cell-top"><strong>Объяснение правильного ответа</strong></div>
                <div class="table-cell-top"><strong>Твой ответ был...</strong></div>
                <div class="table-cell-top"><strong>Подсказка была?</strong></div>
                <div class="table-cell-top"><strong>Получаешь балл(-ы)</strong></div>
            </div>

                <?php foreach ($summary->answers as $realQuestionId => $answer):?>
                    <?php foreach( $questions as $i => $question ): ?>
                        <?php  if ($realQuestionId == $question['id']):?>
                <div class="table-row">
                    <div class="table-cell"><strong><?= $number ?><?php $number++?></strong><br>[<?= $question->id ?>]</div>
                    <div class="table-cell-left"><?= $question->text ?></div>
                    <div class="table-cell-left"><?php $question->getOptionsFinish($question->data); ?></div>
                    <div class="<?= $results[$question->id] ? 'table-cell-success' : 'table-cell-danger' ?>"><center><?php $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers); ?></center></div>
                    <div class="table-cell-left"><?php $question->getCorrectAnswersFinish($question->data, $question->question_type_id)?></div>
                    <div class="table-cell-left"><?= $question->getCommentFinish($question->data); ?></div>
                    <div class="<?= $results[$question->id] ? 'table-cell-success' : 'table-cell-danger' ?>"><center><?= $results[$question->id] ? 'Правильный!' : 'Неправильный...' ?></center></div>
                    <div class="<?= !$hints[$question->id] ? 'table-cell-success' : 'table-cell-danger' ?>"><center><?= $question->getRightHintText($hints[$question->id], $results[$question->id]) ?></center></div>
                    <div class="table-cell"><strong><?= $points[$question->id] ?></strong></div>
                </div>

                        <?php endif;?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <div class="table-row">
                    <?php for($i = 0; $i < 8; $i++):?>
                        <?php echo '<div class="table-cell"></div>';?>
                    <?php endfor;?>

                <div class="table-cell"><strong>Всего баллов: <?= $summary->getAllPoints($questions, $points)['allPoints']; ?> из <?=$summary->getAllPoints($questions, $points)['numberOfPoints']?></strong></div>
            </div>
        </div>

    </div>

    <div class="table_results" style="opacity: 1;">

        <table class="table-finish">
            <tbody>
            <tr><td>1</td>
                <td><span class="orange">1</span></td>
                <td>34</td>
                <td>1<i class="fa question-text" data-id="trq_<?= $number?>"></i>
                    <i class="fa explanation-text" data-id="trv_<?= $number?>"></i></td>
            </tr>
            <tr id="trq_<?= $number?>" style="display: none;">
                <td colspan="4">
                    <p>В каких из приведённых ниже предложений верно передана ГЛАВНАЯ информация, содержащаяся в тексте?</p>
                    <p>1. В XIV веке танцы на балах были очень торжественны, поскольку основное внимание уделялось обсуждению костюмов и демонстрации украшений. </p>
                    <p>2. Танцы на балах в XIV веке были очень торжественны и состояли в основном из разнообразного движения колонн танцоров по залу. </p>
                    <p>3. Балы и танцевальные маскарады становятся в XIV веке популярной формой развлечения богатых, которые во время неторопливых танцев обсуждали костюмы и демонстрировали украшения. </p>
                    <p>4. Обсуждению костюмов и демонстрации украшений на балах в XIV веке уделяли основное внимание, поэтому танцы были медлительны и торжественны. </p>
                    <p>5. Основное внимание на балах и танцевальных маскарадах в XIV веке уделялось обсуждению костюмов и демонстрации украшений.</p>
                </td>
            </tr>
            <tr id="trv_<?= $number?>" style="display: none;">
                <td colspan="4">
                    <p>Предложения 1, 2, 5 не передают главной информации.</p>
                </td>
            </tr>

            </tbody>
        </table>
    </div>

    <script style="opacity: 1;">
        $(".table_results td .question-text, .table_results td .explanation-text").click(function(){
            var $tr_id = $(this).attr('data-id');
            $('#'+$tr_id).toggle('fast');
        });
        setTimeout(function(){
            anim_ball(10);
        },1100);
    </script>


    <!--</div>-->
</div>