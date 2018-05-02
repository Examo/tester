<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ar\LearnObject */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => 'Объекты для Учёбы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="learn-object-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать объект для Учёбы', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'object:ntext',
        ],
    ]) ?>

</div>
