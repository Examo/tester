<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\widgets\QuestionEditor;
use app\models\QuestionType;
use app\models\Course;
use app\models\Subject;
use app\models\ChallengeType;
use kartik\markdown\MarkdownEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form" xmlns="http://www.w3.org/1999/html">

    <?php $form = ActiveForm::begin(); ?>

    <script>
        $(function(){
            $('#tabs a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })
        });
    </script>

    <ul class="nav nav-tabs" id="tabs">
        <li role="presentation" class="active"><a href="#question"><?= Yii::t('question', 'Question') ?></a></li>
        <li role="presentation"><a href="#settings"><?= Yii::t('question', 'Settings') ?></a></li>
    </ul>

    <br>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="question">
            <?= $form->field($model, 'question_type_id')->widget(Select2::className(), [
                'data' => QuestionType::getList(),
                'options' => [
                    'id' => 'question_type_id',
                    'multiple' => false
                ],
            ]) ?>

            <?= $form->field($model, 'text', ['template' => '{label}']) ?>
            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'text',
            ]) ?>
            <br />

            <?= $form->field($model, 'data')->widget(QuestionEditor::className()) ?>

            <?= $form->field($model, 'comment', ['template' => '{label}']) ?>
            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'comment',
            ]) ?>
            <br />

            <?= $form->field($model, 'hint', ['template' => '{label}']) ?>
            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'hint',
            ]) ?>
            <br />
        </div>
        <div role="tabpanel" class="tab-pane fade" id="settings">
            <?= $form->field($model, 'cost')->textInput() ?>

            <?= $form->field($model, 'courses_ids')->widget(Select2::className(), [
                'data' => Course::getList(),
                'options' => [
                    'id' => 'courses_ids',
                    'multiple' => true
                ],
            ]) ?>

            <?= $form->field($model, 'subjects_ids')->widget(Select2::className(), [
                'data' => Subject::getList(),
                'options' => [
                    'id' => 'subjects_ids',
                    'multiple' => true
                ],
            ]) ?>

            <?= $form->field($model, 'challengeTypes_ids')->widget(Select2::className(), [
                'data' => ChallengeType::getList(),
                'options' => [
                    'id' => 'challengeTypes_ids',
                    'multiple' => true
                ],
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
