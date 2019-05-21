<?php

use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\QuestionType;

$id = $id ? $id : (uniqid('qs') . '-modal');

?>

<div class="modal fade" id="<?= $id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Поиск задания</h4>
            </div>
            <div class="modal-body">
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
                        [
                            'attribute' => 'question_type_id',
                            'filter' => QuestionType::getList(),
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->questionType->name;
                            }
                        ],

                        'text:ntext',
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
            <div class="modal-footer">
                <span class="pull-left">Выбрано заданий: <span class="count">0</span></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary save">Добавить</button>
            </div>
        </div>
    </div>
</div>