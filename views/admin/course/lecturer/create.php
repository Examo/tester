<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CourseLecturer */

$this->title = 'Create Course Lecturer';
$this->params['breadcrumbs'][] = ['label' => 'Course Lecturers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-lecturer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
