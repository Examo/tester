<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\ar\Element;

/* @var $this yii\web\View */
/* @var $model app\models\ElementsItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="element-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'element_id')->widget(Select2::className(), [
        'data' => Element::getList(),
        'options' => [
            'id' => 'subject_id',
            'multiple' => false
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
