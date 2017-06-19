<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\SubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('subject', 'Subjects');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subject-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' ' . Yii::t('subject', 'Subject'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute'=>'course_id',
                'filter' => \app\models\Course::getList(),
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
                'value' => function($model) {
                    return $model->course->name;
                }
            ],

            [
                'attribute' => 'name',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-4'
                ],
            ],


            [
                'attribute' => 'description',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-4'
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
            ],

        ],
    ]); ?>
<?php Pjax::end(); ?></div>
