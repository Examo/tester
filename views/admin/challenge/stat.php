<?php
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
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges') . ' (с переходом на статистику)', 'url' => ['admin/challenge/index']];
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

            <!-- Всего баллов -->
            <td class="text-center">
                <?= $challenge->grade_number ?>
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
                <a data-toggle="collapse" href="#common">Распространённые ошибки</a>
            </h4>
        </div>
        <div id="common" class="collapse">
            <div class="panel-body">
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
            </div>
        </div>
    </div>

</div>