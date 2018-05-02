<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ar\LearnObject */

$this->title = 'Создать объект для Учёбы';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => 'Объекты для Учёбы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="learn-object-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
