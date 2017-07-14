<?php

use yii\helpers\Html;

$this->title = Yii::t('course', 'Course stats') . ' "'.$course->name . '"';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['admin/course/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses stats'), 'url' => ['admin/course/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($course->getAllUsers($course->id)):?>
    <table class="table table-striped table-bordered">
        <tbody>
        <th class="col-md-1 text-center">ID ученика</th>
        <th class="col-md-2 text-center">Имя ученика</th>
        <th class="col-md-2 text-center">Тест / Попытки / Последняя оценка</th>
        <th class="col-md-1 text-center">Все курсы ученика</th>

            <?php foreach ($course->getAllUsers($course->id) as $user):?>
            <tr>
                <!-- ID ученика -->
                <td class="text-center">
                    <?= $user->user_id; ?>
                </td>

                <!-- Имя ученика -->
                <td class="text-center">
                    <?php foreach($dataProvider->models as $model):?>
                        <?php if ($user->user_id == $model->attributes["id"]):?>
                            <?= $model->attributes["username"] ?>
                        <?php endif; ?>
                    <?php endforeach;?>
                </td>

                <!-- Тест / Попытки / Последняя оценка -->
                <td class="text-center">
                    <?php foreach ($course->getChallenges()->all() as $challenge):?>
                        <?php if ($challenge->getAttemptsCount($user->user_id)):?>
                            №<?= $challenge->id ?> /
                        <?= $challenge->getAttemptsCount($user->user_id)?> /
                        <?php endif;?>

                    <?php if ($challenge->getMarks($user->user_id, $challenge->id) && $challenge->getAttemptsCount($user->user_id)):?>
                        <?php foreach( $challenge->getMarks($user->user_id, $challenge->id) as $markContainer):?>
                        <?php endforeach;?>
                        <strong><?= $markContainer->mark?></strong><br>
                        <?php endif;?>
                    <?php endforeach; ?>
                </td>

                <!-- Все курсы ученика -->
                <td class="text-center">
                    <a href="<?= \yii\helpers\Url::to(['admin/grades/list', 'user_id' => $user->user_id])?>" class="btn btn-xs btn-success">Перейти</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif;?>
    <?php if (!$course->getAllUsers($course->id)):?>
            <p>Никто ещё не подписался на курс, вот какой он хороший :(</p>
    <?php endif;?>
</div>

