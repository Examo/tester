<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\SubsetWidget;
use app\models\Subject;
use app\models\ar\User;

/* @var $this yii\web\View */
/* @var $model app\models\Course */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'discipline_id')->widget(\kartik\select2\Select2::className(), [
        'data' => \app\models\Discipline::getList(),
        'options' => [
            'id' => 'discipline_id',
            'multiple' => false
        ],
    ]) ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'user_id')->label('Преподаватель курса')->widget(\kartik\select2\Select2::className(), [
            'data' => User::getList(),
            'options' => [
                'id' => 'user_id',
                'multiple' => false
            ],
        ]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php // $form->field($model, 'start_time')->widget(DatePicker::className(), [
          //  'options' => ['placeholder' => Yii::t('course', 'Введите дату начала')],
          //  'pluginOptions' => [
          //      'autoclose' => true,
          //      'format' => 'yyyy-mm-dd 00:00:00'
          //  ]
        //]); ?>

    <?= $form->field($model, 'subjects')->widget(SubsetWidget::className(), [
        'form' => $form,
        'child' => Subject::className(),
        'fields' => [
            'id' => 'hiddenInput',
            'name' => 'textInput',
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
