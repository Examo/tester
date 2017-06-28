<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\ChallengeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('challenge', 'Challenges');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' ' . Yii::t('challenge', 'Challenge'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute'=>'challenge_type_id',
                'filter' => \app\models\ChallengeType::getList(),
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
                'value' => function($model) {
                    return $model->challengeType->name;
                }
            ],
            [
                'attribute'=>'subject_id',
                'filter' => \app\models\Subject::getList(),
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
                'value' => function($model) {
                    return $model->subject->name;
                }
            ],
            [
                'attribute' => 'name',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
            [
                'attribute' => 'description',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
            [
                'attribute' => 'week',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-2'
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
