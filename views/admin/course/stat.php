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
        <th class="col-md-3 text-center">Общее количество выполненных тестов</th>
        <th class="col-md-3 text-center">Перейти к подробной статистике</th>

            <?php foreach ($course->getAllUsers($course->id) as $user):?>
            <tr>
                <td class="text-center">
                    <?= $user->user_id; ?>
                </td>
                <td>
                </td>
                <td>
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
