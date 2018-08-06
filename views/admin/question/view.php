<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Question */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php if ($model->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION) {
        $data = \yii\helpers\Json::decode($model->data);
        $question = $data['question'] ? implode('<br>', $data['question']) : '';
        $answer = $data['answer'] ? implode('<br>', $data['answer']) : '';
        $model->data = '<b>Задания:</b> <br>' . $question . '<br> <b>Ответы:</b> <br>' . $answer;
        $model->hint = implode('<br>', \yii\helpers\Json::decode($model->hint));
        $model->comment = implode('<br>', \yii\helpers\Json::decode($model->comment));
    }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'question_type_id',
            'text:ntext',
            'data:raw',
            'hint:raw',
            'comment:raw',
            'cost',
        ],
    ]) ?>

</div>
