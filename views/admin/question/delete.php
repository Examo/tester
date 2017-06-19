<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Question */

$this->title = Yii::t('app', 'Delete') . ' ' . Yii::t('question', 'Question') . ': '. $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('question', 'Questions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Delete');
?>
<div class="question-delete">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = \yii\widgets\ActiveForm::begin(); ?>

    <input type="hidden" name="confirm" value="1">

    <h2>Внимание!</h2>

    <p>Данный вопрос используется в следующих тестах: </p>
    <ul>
        <?php foreach( $challenges as $challenge ): ?>
            <li>
                <a href="<?= \yii\helpers\Url::to(['admin/challenge/view', 'id' => $challenge->id]) ?>">
                    <?= $challenge->name ?>
                </a>
            </li>
        <?php endforeach;?>
    </ul>

    <p>
        Также будет удалена вся история ответов на данный копрос.
    </p>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Delete'), ['class' =>'btn btn-danger']) ?>
        <a href="<?= \yii\helpers\Url::to(['admin/question/index']) ?>" class="btn btn-default"><?= Yii::t('app', 'Back') ?></a>
    </div>

    <?php \yii\widgets\ActiveForm::end(); ?>

</div>
