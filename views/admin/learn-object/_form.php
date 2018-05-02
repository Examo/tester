<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ar\LearnObject */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="learn-object-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'object')->textarea(['rows' => 1]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
