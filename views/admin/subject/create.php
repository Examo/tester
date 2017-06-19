<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Subject */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('subject', 'Subject');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('subject', 'Subjects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
