<?php

use yii\helpers\Html;

$this->title = Yii::t('challenge', 'Weeks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges'), 'url' => ['admin/challenge/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-index">

    <h1><?= Html::encode($this->title) ?> по курсам</h1>

    <p>

    </p>
    <?php foreach ($courses as $course): ?>
        <?= Html::a(Yii::t('challenge', 'Weeks') . ' по курсу "' . $course->name .'"', ['week?course_id=' . $course->id], ['class' => 'btn btn-primary']) ?>
    <?php endforeach; ?>
</div>