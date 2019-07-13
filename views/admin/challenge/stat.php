<?php

use app\helpers\QuestionStats;
use yii\helpers\Html;
?>
<?php foreach($course as $id): ?>
    <?php if ($id->id == $challenge->course_id):?>
        <?php $courseName = $id->name ?>
        <?php break; ?>
    <?php endif; ?>
<?php endforeach; ?>
<?php
$this->title = Yii::t('challenge', 'Challenge Statistics') . ' №'. $challenge->id . ' по курсу ' . '"' . $courseName . '"';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges'), 'url' => ['admin/challenge/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' новый ' . Yii::t('challenge', 'Challenge'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <table class="table table-striped table-bordered">
        <tbody>

        <th class="col-md-1 text-center">Название</th>
        <th class="col-md-1 text-center">Описание</th>
        <th class="col-md-1 text-center">Класс</th>
        <th class="col-md-1 text-center">Всего баллов</th>
        <th class="col-md-1 text-center">Неделя в курсе</th>
        <th class="col-md-1 text-center">Тип теста</th>
        <th class="col-md-1 text-center">Всего выполняли раз</th>
        <th class="col-md-1 text-center">Средняя оценка</th>
        <tr>

            <!-- Название -->
            <td class="text-center">
                <?= $challenge->name ?>
            </td>

            <!-- Описание -->
            <td class="text-center">
                <?= $challenge->description ?>
            </td>

            <!-- Класс -->
            <td class="text-center">
                <?= $challenge->grade_number ?>
            </td>

            <!-- Всего баллов -->
            <td class="text-center">
                <?php $cost = 0; ?>
                <?php foreach ($questions as $question): ?>
                    <?php $cost += $question['question']->cost; ?>
                <?php endforeach; ?>
                <?= $cost ?>
            </td>

            <!-- Неделя в курсе -->
            <td class="text-center">
                <?= $challenge->week ?>
            </td>

            <!-- Тип теста -->
            <td class="text-center">
                <?= $challenge->challenge_type_id ?>
            </td>

            <!-- Всего выполняли раз -->
            <td class="text-center">
                <?php $number = 0; ?>
                <?php foreach ($challenge->getAllChallengeAttempts($challenge->id) as $attempt):?>
                    <?php $number++ ?>
                <?php endforeach; ?>
                <?= $number ?>
            </td>

            <!-- Средняя оценка -->
            <td class="text-center">
                <?php $mark = 0; ?>
                <?php $number = 0; ?>
                <?php foreach ($challenge->getAllChallengeMarks($challenge->id) as $attempt):?>
                    <?php //\yii\helpers\VarDumper::dump($mark->mark, 10, true) ?>
                    <?php $mark += intval($attempt->mark)?>
                    <?php $number++ ?>
                <?php endforeach; ?>
                <?php $mark != 0 ? $mark = round($mark / $number) : $mark?>
                <?= $mark ?>
            </td>

        </tr>
        </tbody>
    </table>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#common">Рейтинг сложных заданий</a>
            </h4>
        </div>
        <div id="common" class="collapse">
            <div class="panel-body">
                <?php $rangeQuestions = []; ?>
                <?php foreach ($questions as $key => $question): ?>
                    <?php $rangeQuestions[$question['question']->id][] = $question['question']->wrong_points; ?>
                <?php endforeach; ?>

                <?php array_multisort($rangeQuestions, SORT_DESC, $questions); ?>

                <?php foreach ($questions as $question): ?>
                <? $questionModel = $question['question'];?>
                    <div class="panel panel-info">
                        <div class="panel-body">
                    <?php $numberOfPupils = $questionModel->right_points + $questionModel->wrong_points; ?>
                    <p><strong>№ задания</strong>: <?= $questionModel->id; ?></p>
                    <p><strong>Вопрос</strong>: <?= $questionModel->text; ?></p>
                            <?php if ($questionModel->question_type_id === app\models\QuestionType::TYPE_ASSOC_TABLE): ?>
                    <p><center><strong>Варианты ответа</strong>: <?= $questionModel->getOptionsFinish($questionModel->data)?></center></p>
                        <?php else: ?>
                        <p><strong>Варианты ответа</strong>: <?= $questionModel->getOptionsFinish($questionModel->data)?></p>
                        <?php endif; ?>
                    <?= QuestionStats::getProgressBar($questionModel) ?>
                    </div>
                </div>
                        </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" href="#allwho">Все выполнявшие</a>
            </h4>
        </div>
        <div id="allwho" class="collapse">
            <div class="panel-body">

                <table class="table table-striped table-bordered">
                    <tbody>
                    <th class="col-md-1 text-center">Имя</th>
                    <th class="col-md-1 text-center">Попытки</th>
                    <th class="col-md-1 text-center">Последняя оценка</th>
                    <th class="col-md-1 text-center">Средняя оценка</th>
                    <th class="col-md-1 text-center">Все курсы ученика</th>
                <?php foreach($usernames as $username):?>
                <?php foreach ($challenge->getAllChallengeUsers($challenge->id) as $user):?>
                    <?php if($username->attributes['id'] == $user->user_id): ?>

                        <?php if ($challenge->getAttemptsCount($user->user_id)):?>

                            <tr>

                            <!-- Тест -->
                            <td class="text-center">
                                <?= $username->attributes['username']; ?>
                            </td>

                            <!-- Попытки -->
                            <td class="text-center">
                                <?= $challenge->getAttemptsCount($user->user_id)?>
                            </td>
                        <?php endif;?>
                        <?php if ($challenge->getMarks($user->user_id, $challenge->id) && $challenge->getAttemptsCount($user->user_id)):?>

                            <!-- Последняя оценка -->
                            <td class="text-center">
                                <?php foreach( $challenge->getMarks($user->user_id, $challenge->id) as $markContainer):?>
                                <?php endforeach;?>
                                <strong><?= $markContainer->mark?></strong>
                            </td>

                            <!-- Средняя оценка -->
                            <td class="text-center">
                                    <?php $averageMark = 0; $number = 0; ?>
                                    <?php foreach( $challenge->getMarks($user->user_id, $challenge->id) as $markContainer):?>
                                        <?php $averageMark += $markContainer->mark; $number++; ?>
                                    <?php endforeach;?>
                                    <?php $averageMark != 0 ? $averageMark = round($averageMark / $number) : $averageMark?>
                                    <strong><?= $averageMark ?></strong>
                            </td>

                                <!-- Все курсы ученика -->
                                <td class="text-center">
                                    <a href="<?= \yii\helpers\Url::to(['admin/grades/list', 'user_id' => $user->user_id])?>" class="btn btn-xs btn-success">Перейти</a>
                                </td>

                            </tr>

                        <?php endif;?>


                    <?php endif;?>
                <?php endforeach; ?>

            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
