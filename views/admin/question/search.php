<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\search\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<?php Pjax::begin(['id' => 'questions-search']); ?>    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'attribute' => 'add',
            'format' => 'raw',
            'value' => function ($model) {
                return '<input type="checkbox" class="add" data-id="'.$model->id.'" data-text="'.htmlentities( $model->text ).'" />';
            }
        ],

        'id',
        'question_type_id',
        'text:ntext',
    ],
]); ?>
<?php Pjax::end(); ?></div>
