<?php

use yii\helpers\Html;
use app\widgets\EventWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Course */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('course', 'Course') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="course-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= EventWidget::widget(['course_id' => $model->id]) ?>

    <?= $this->render('_form', [
        'model' => $model,
        'lecturer' => $lecturer,
        'users' => $users
    ]) ?>



</div>