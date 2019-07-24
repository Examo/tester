<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('question', 'Questions');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' ' . Yii::t('question', 'Question'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a( Yii::t('questionSettings', 'List of Question Settings'), ['admin/questionSettings/index'], ['class' => 'btn btn-primary']) ?>
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
                'attribute'=>'question_type_id',
                'filter' => \app\models\QuestionType::getList(),
                'headerOptions' => [
                    'class' => 'col-md-3'
                ],
                'value' => function($model) {
                    return $model->questionType->name;
                }
            ],
            [
                'attribute' => 'text',
                'format' => 'ntext',
                'headerOptions' => [
                    'class' => 'col-md-7'
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
