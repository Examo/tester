<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CourseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('course', 'Courses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' ' . Yii::t('course', 'Course'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a(Yii::t('lecturer', 'Course Lecturers'), ['admin/course/lecturers'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'headerOptions' => [
                    'class' => 'text-center'
                ],
                'contentOptions' =>[
                    'class' => 'text-center'
                ],
            ],

            [
                'attribute' => 'name',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-2 text-center'
                ],
            ],


            [
                'attribute' => 'description',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-5 text-center'
                ],
            ],

            [
                'attribute' => 'start_time',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-2 text-center'
                ],
                'contentOptions' =>[
                    'class' => 'text-center'
                ],
            ],
            [
                'label' => 'К статистике',
                'format' => 'raw',
                'value' => function($model){
                    return '<center><a href='. \yii\helpers\Url::to(['admin/course/stat', 'course_id' => $model->attributes['id']]) . ' " class="btn btn-xs btn-success">Перейти</a></center>';
                },
                'headerOptions' => [
                    'class' => 'col-md-1 text-center'
                ],
                'contentOptions' =>[
                    'class' => 'text-center'
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => [
                    'class' => 'col-md-1 text-center'
                ],
                'contentOptions' =>[
                    'class' => 'text-center'
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
