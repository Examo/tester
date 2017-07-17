<?php

use yii\helpers\Html;

$this->title = Yii::t('course', 'Courses stats');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['admin/course']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php foreach ($courses as $course): ?>
        <p><?= Html::a('По курсу "' . $course->name .'"', ['stat?course_id=' . $course->id], ['class' => 'btn btn-primary']) ?></p>
    <?php endforeach; ?>
</div>
