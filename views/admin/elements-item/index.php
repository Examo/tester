<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ElementsItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('elements_item', 'Elements_item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' ' . Yii::t('element', 'Element'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
                'attribute' => 'name',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-4'
                ],
            ],


            [
                'attribute' => 'element_id',
                'content' => function($data) {
                    return Html::Tag('span', $data->element->name);
                },
                'headerOptions' => [
                    'class' => 'col-md-6'
                ],
                'filter' => ArrayHelper::map(\app\models\ar\Element::find()->all(),'id','name'),
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
