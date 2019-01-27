<?php

/* @var $this yii\web\View */

use app\models\ChallengeHasQuestion;
use yii\helpers\Html;
use app\models\Question;

$this->title = 'Вебинар';

?>

<!--<div class="panel panel-default">
<div class="panel-body">
<center><label>Выполняли задание:</label>
    <strong>12 человек</strong></center>
    <center><label>Правильно: 2
    <br>Неправильно: 10</label></center>
<div class="progress">
    <div class="progress-bar progress-bar-info progress-bar-danger" role="progressbar" aria-valuenow="25.9" aria-valuemin="10" style="width: 25.9%">
    </div>
    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="74.1" aria-valuemin="10" style="width: 74%">
    </div>
</div>
    </div>
</div>-->


    <center><img src="/i/badge.png" width="200" /></center>
<div class="challenge-form">
    <script>
        $(function(){
            $('#tabs a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })
        });
    </script>

    <ul class="nav nav-tabs" id="tabs">
        <li role="presentation" class="active"><a href="#info">О вебинаре</a></li>
<?php if ($cleanWebinarChallenges): ?>
    <?php $numberOfChallenge = 0; ?>
    <?php foreach ($cleanWebinarChallenges['challenge'] as $cleanWebinarChallengeNumber => $cleanWebinarChallenge): ?>
        <?php $numberOfChallenge += 1; ?>
        <?php if ($cleanWebinarChallenges['isDone'][$cleanWebinarChallengeNumber] == 0): ?>
            <?php $color = '#f13e46'; ?>
        <?php else: ?>
            <?php $color = '#26A69A'; ?>
        <?php endif; ?>
        <li role="presentation"><a href="#<?= $numberOfChallenge ?>" style="color: <?= $color; ?>">Тест №<?= $numberOfChallenge ?></a></li>
    <?php endforeach; ?>
<?php endif; ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="info">
            <div class="site-about">
                <h1><?= Html::encode($this->title) ?></h1>

                <?php if ($data):?>
                    <p>
                        <strong><?= $data['webinar_description']; ?></strong><br>
                        Номер вебинара в курсе: <strong><?= $data['webinar_number']; ?></strong><br>
                        Неделя в курсе: <strong><?= $data['webinar_week']; ?></strong><br>
                        Начало: <?= $data['webinar_start']; ?><br>
                        Окончание: <?= $data['webinar_end']; ?><br>
                        Ссылка на YouTube для встраивания в страницу: <?= $data['webinar_link']; ?><br>
                        Курс <strong><?= $data['course_name']; ?></strong>,
                        <?= $data['isSubscribed'] ? 'подписка на курс оформлена!': 'не забудь подписаться на этот курс!'; ?>
                    </p>
                <?php else: ?>
                    <p>
                        Страница для вебинара создана, а сам вебинар пока что нет! Либо имеется ошибка в его оформлении!
                    </p>
                <?php endif; ?>

            </div>
        </div>
        <?php if ($cleanWebinarChallenges): ?>
            <?php $numberOfChallenge = 0; ?>
    <?php foreach ($cleanWebinarChallenges['challenge'] as $cleanWebinarChallengeNumber => $cleanWebinarChallenge): ?>
                <?php $numberOfChallenge += 1; ?>
        <div role="tabpanel" class="tab-pane fade" id="<?= $numberOfChallenge ?>">
            <!-- если тест не выполнен -->
            <?php if ($cleanWebinarChallenges['isDone'][$cleanWebinarChallengeNumber] == 0): ?>
                <?php if ($cleanWebinarChallenges['isDone'][$cleanWebinarChallengeNumber] == 0): ?>
                    <?php $color = '#f13e46'; ?>
                <?php else: ?>
                    <?php $color = '#26A69A'; ?>
                <?php endif; ?>
                <div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
                    <div class="panel-heading" role="tab">
                        <h4 class="panel-title" style="color: <?= $color; ?>"><strong>Не выполнен!</strong>
                        Тест №<?= $numberOfChallenge ?> (В системе №<?= $cleanWebinarChallengeNumber ?>)</h4>
                    </div>
                    <div role="tabpanel">
                        <div class="panel-body">
                            <center><p class="lead"><?= $cleanWebinarChallenge->name ?></p>
                                <?php $challengeAttempts = \app\models\Attempt::find()->where(['challenge_id' => $cleanWebinarChallenge->id])->all();?>
                                <?php $marks = 0;
                                foreach ($challengeAttempts as $challengeAttempt){
                                    $marks += intval($challengeAttempt->mark);
                                }
                                if (count($challengeAttempts) != 0) {
                                    $averageMark = $marks / count($challengeAttempts);
                                } else {
                                    $averageMark = 0;
                                }
                                ?>
                                <p>Всего выполняли раз: <?= count($challengeAttempts); ?></p>
                                <p>Средняя оценка: <?= round($averageMark); ?></p>
                                <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $cleanWebinarChallengeNumber, 'confirm' => true]) ?>" target="_blank">Начать тест №<?= $numberOfChallenge ?></a></p>
                            </center>
                        </div>
                    </div>
                </div>

            <!-- если тест выполнен -->
            <?php else: ?>
               <?php /**
                * @var \app\helpers\ChallengeSummarizer $summary
                */
                $webinarAnswers = \app\models\WebinarAnswers::find()->where(['challenge_id' => $cleanWebinarChallengeNumber])->andWhere(['user_id' => Yii::$app->user->id])->one();

                if ($webinarAnswers) {
                    $challenge = \app\models\Challenge::find()->where(['id' => $webinarAnswers->challenge_id])->one();
                    $questions = $challenge->getQuestionsByChallengeId($webinarAnswers->challenge_id);
                    $challengeAttempts = \app\models\Attempt::find()->where(['challenge_id' => $webinarAnswers->challenge_id])->all();
                    $marks = 0;
                    foreach ($challengeAttempts as $challengeAttempt){
                        $marks += intval($challengeAttempt->mark);
                    }
                    if (count($challengeAttempts) != 0) {
                        $averageMark = $marks / count($challengeAttempts);
                    } else {
                        $averageMark = 0;
                    }
                    $challengeAttempts = count($challengeAttempts);

                    $newQuestion = [];
                    foreach ($questions as $q) {
                        $newQuestion[] = $q['question'];
                    }
                    $questions = $newQuestion;
                }
                $number = 1;?>

                <?php if ($webinarAnswers): ?>
                    <?php if ($cleanWebinarChallenges['isDone'][$cleanWebinarChallengeNumber] == 0): ?>
                        <?php $color = '#f13e46'; ?>
                    <?php else: ?>
                        <?php $color = '#26A69A'; ?>
                    <?php endif; ?>
                <div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
                    <div class="panel-heading" role="tab">
                        <h4 class="panel-title" style="color: <?= $color; ?>;"><strong>Выполнен!</strong> Общие результаты теста №<?= $numberOfChallenge ?> (в системе №<?= $cleanWebinarChallengeNumber ?>)</h4>
                    </div>
                    <div role="tabpanel">
                        <div class="panel-body">
                            <center><p class="lead"><?= $challenge->name ?></p>
                                <p>Всего выполняли раз: <?= $challengeAttempts; ?></p>
                                <p>Средняя оценка: <?= round($averageMark); ?></p>
                                <p><strong>Твоя оценка: <?= $webinarAnswers->mark ? $challenge->getTextMark($webinarAnswers->mark) : 'не доступно - слишком мало было дано ответов' ?></strong></p>
                                <p><strong>Набрано баллов: <?= $webinarAnswers->all_user_points; ?> из <?= $webinarAnswers->all_points; ?></strong></p>
                                <p>Время выполнения: <?= $webinarAnswers->time ?> мин.</p>
                            </center>
                        </div>
                    </div>
                </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#results">Твои подробные результаты</a>
                    </h4>
                </div>
                <div id="results" class="collapse">
                        <div aria-labelledby="summaryHead">
                            <div class="panel-body">
                                <table class="table table-finish table-hover table_results">
                                    <?php $results = json_decode($webinarAnswers->result, true); ?>
                                    <?php $hints = json_decode($webinarAnswers->hints, true); ?>
                                    <?php $points = json_decode($webinarAnswers->points, true); ?>
                                    <?php //\yii\helpers\VarDumper::dump($points, 10, true); ?>
                                    <?php foreach (json_decode($webinarAnswers->answers, true) as $realQuestionId => $answer):?>
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
                                                                <i class="fa options-text" data-id="options<?= $number ?>"></i>
                                                            <?php else: ?>
                                                                <ul>
                                                                    <?php $question->getOptionsFinish($question->data); ?>
                                                                </ul>
                                                            <?php endif;?>
                                                        </td>
                                                        <!-- Твой ответ -->
                                                        <td class="<?= $results[$question->id] ? 'success' : 'danger' ?> text-left">
                                                            <?php if ($question->question_type_id == \app\models\QuestionType::TYPE_ASSOC_TABLE): ?>
                                                                <i class="fa answer-text" data-id="answer<?= $number; ?>"></i>
                                                            <?php else: ?>
                                                                <?php $challenge->getAnswersFinish($question->data, $question->id, $question->question_type_id, json_decode($webinarAnswers->answers, true), $question); ?>
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
                                                        <td class="<?= !$hints[$question->id] ? 'success' : 'danger' ?>"><center><?php $question->getRightHintText($hints[$question->id], $results[$question->id], $question->question_type_id) ?></center></td>
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
                                                            <p class="text-center"><center><?php// $summary->getAnswersFinish($question->data, $question->id, $question->question_type_id, $summary->answers, $question); ?></center></p>
                                                        </td>
                                                    </tr>
                                                    <!-- Вставка блока с объяснением при 7-м задании -->
                                                    <tr id="explanation<?= $number?>" style="display: none;">
                                                        <td colspan="8">
                                                            <center><i class="fa explanation-text" data-id="explanation<?= $number?>"></i><strong>Объяснение ответа:</strong></center>
                                                            <p class="text-center"><center><?php// $question->getCommentFinish($question->data) ?></center></p>
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
                                                            <!--<td class="text-left"><?php// $question->text ?> </td>-->
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
                                                    <?php $questionData = Question::find()->where(['id' => $question->id])->one()?>
                                                    <?php $numberOfPupils = $questionData->right_points + $questionData->wrong_points; ?>
                                                    <?php
                                                    if ($questionData->right_points !== 0) {
                                                        $numberOfRightPointsCoefficient = ($questionData->right_points + $questionData->wrong_points) / $questionData->right_points;
                                                        $numberOfRightPoints = 100 / $numberOfRightPointsCoefficient;
                                                    } else {
                                                        $numberOfRightPoints = 0;
                                                    }
                                                    ?>
                                                    <?php
                                                    //    if ($questionData->wrong_points !== 0) {
                                                    //        $numberOfWrongPointsCoefficient = ($questionData->right_points + $questionData->wrong_points) / $questionData->wrong_points;
                                                    //        $numberOfWrongPoints = 100 / $numberOfWrongPointsCoefficient;
                                                    //    } else {
                                                    //        $numberOfWrongPoints = 0;
                                                    //    }
                                                    ?>
                                                    <?php $numberOfWrongPoints = 100 - $numberOfRightPoints; ?>
                                                    <?php //\yii\helpers\VarDumper::dump($numberOfRightPoints, 10, true); ?>
                                                    <center>Выполняли задание раз: <strong><?= $numberOfPupils; ?></center>
                                                    <center><label>Неправильно: <strong><?= $questionData->wrong_points; ?></strong>
                                                            / Правильно: <strong><?= $questionData->right_points; ?></strong></label></center>
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-info progress-bar-danger" role="progressbar" aria-valuenow="25.9" aria-valuemin="10" style="width: <?= $numberOfWrongPoints; ?>%">
                                                        </div>
                                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="74.1" aria-valuemin="10" style="width: <?= $numberOfRightPoints; ?>%">
                                                        </div>
                                                    </div>
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
                                        <td class="text-center"><strong>Всего баллов: <?= $webinarAnswers->all_user_points; ?> из <?= $webinarAnswers->all_points; ?></strong></td>
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
            </div>

                <?php else: ?>
                    <br>
            <h4 class="panel-title" style="font-weight: bold">Данные о твоём тесте были удалены из базы! Увы :(</h4>
                    <br>
                    <div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
                        <div class="panel-heading" role="tab">
                            <h4 class="panel-title">Тест №<?= $numberOfChallenge ?> (В системе №<?= $cleanWebinarChallengeNumber ?>)</h4>
                        </div>
                        <div role="tabpanel">
                            <div class="panel-body">
                                <center><p class="lead"><?= $cleanWebinarChallenge->name ?></p>
                                    <?php $challengeAttempts = \app\models\Attempt::find()->where(['challenge_id' => $cleanWebinarChallenge->id])->all();?>
                                    <?php $marks = 0;
                                    foreach ($challengeAttempts as $challengeAttempt){
                                        $marks += intval($challengeAttempt->mark);
                                    }
                                    if (count($challengeAttempts) != 0) {
                                        $averageMark = $marks / count($challengeAttempts);
                                    } else {
                                        $averageMark = 0;
                                    }
                                    ?>
                                    <p>Всего выполняли раз: <?= count($challengeAttempts); ?></p>
                                    <p>Средняя оценка: <?= round($averageMark); ?></p>
                                    <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $cleanWebinarChallengeNumber, 'confirm' => true]) ?>" target="_blank">Попробовать повторно пройти тест №<?= $numberOfChallenge ?></a></p>
                                </center>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>



            <?php endif; ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" href="#<?= $cleanWebinarChallenge->id ?>">Рейтинг сложных заданий</a>
                    </h4>
                </div>
                <div id="<?= $cleanWebinarChallenge->id ?>" class="collapse">
                    <div class="panel-body">
                        <?php $questions = ChallengeHasQuestion::find()->innerJoinWith('question')->where(['challenge_has_question.challenge_id' => $cleanWebinarChallenge->id])->all(); ?>
                        <?php $rangeQuestions = []; ?>
                        <?php foreach ($questions as $key => $question): ?>
                            <?php $rangeQuestions[$question['question']->id][] = $question['question']->wrong_points; ?>
                        <?php endforeach; ?>

                        <?php array_multisort($rangeQuestions, SORT_DESC, $questions); ?>

                        <?php //\yii\helpers\VarDumper::dump($rangeQuestions, 10, true); ?>

                        <?php foreach ($questions as $question): ?>
                            <div class="panel panel-info">
                                <div class="panel-body">
                                    <?php $numberOfPupils =$question['question']->right_points + $question['question']->wrong_points; ?>
                                    <?php
                                    if ($question['question']->right_points !== 0) {
                                        $numberOfRightPointsCoefficient = ($question['question']->right_points + $question['question']->wrong_points) / $question['question']->right_points;
                                        $numberOfRightPoints = 100 / $numberOfRightPointsCoefficient;
                                    } else {
                                        $numberOfRightPoints = 0;
                                    }
                                    ?>
                                    <?php $numberOfWrongPoints = 100 - $numberOfRightPoints; ?>
                                    <?php //\yii\helpers\VarDumper::dump($numberOfRightPoints, 10, true); ?>
                                    <?php //\yii\helpers\VarDumper::dump($question['question'], 10, true); ?>
                                    <p><strong>№ задания</strong>: <?= $question['question']->id; ?></p>
                                    <p><strong>Вопрос</strong>: <?= $question['question']->text; ?></p>
                                    <?php if ($question['question']->question_type_id == 7): ?>
                                        <p><center><strong>Варианты ответа</strong>: <?= $question['question']->getOptionsFinish($question['question']->data)?></center></p>
                                    <?php else: ?>
                                        <p><strong>Варианты ответа</strong>: <?= $question['question']->getOptionsFinish($question['question']->data)?></p>
                                    <?php endif; ?>
                                    <center>Выполняли задание раз: <strong><?= $numberOfPupils; ?></strong></center>
                                    <center><label>Неправильно: <strong><?= $question['question']->wrong_points; ?></strong>
                                            / Правильно: <strong><?= $question['question']->right_points; ?></strong></label></center>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-info progress-bar-danger" role="progressbar" aria-valuenow="25.9" aria-valuemin="10" style="width: <?= $numberOfWrongPoints; ?>%">
                                        </div>
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="74.1" aria-valuemin="10" style="width: <?= $numberOfRightPoints; ?>%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>