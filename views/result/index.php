<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\ResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'All essays');
?>

<div class="result-index">

    <?php echo Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>

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