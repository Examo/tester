<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\ResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты';
?>

<div class="result-index">

    <h1><?= Html::encode($this->title) ?></h1>

<?php Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'id',
            'headerOptions' => [
                'class' => 'col-md-1'
            ]
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => [
                'class' => 'col-md-1'
            ],
            'template' => '{update}',
        ],
    ],
]); ?>

<?php Pjax::end(); ?>