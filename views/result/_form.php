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
/* @var $model app\models\Answer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form" xmlns="http://www.w3.org/1999/html">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data', ['template' => '{label}']) ?>
    <?= MarkdownEditor::widget([
        'model' => $model,
        'attribute' => 'data',
        'headerOptions' => [
             'hidden' => true
        ],
        'editorOptions' => [
            'class' => 'kv-md-editor',
            'disabled' => true
        ],
        'options' => [
            'class' => 'kv-md-input',
            'disabled' => true
        ]
    ]) ?>

    <?php $resultCriterions = $model->getResultCriterions(); ?>
    <?php function escapeArray($str) {
        return str_replace(array(',', '[', ']'), '', $str);
    } ?>
    <?php
        foreach ($resultCriterions as $blockTitle => $criterions) {
            echo Html::label($blockTitle);
            echo '</br>';
            echo '<div class="block-result">';

            $escapeBlockTitle = escapeArray($blockTitle);

            foreach ($criterions as $criterionTitle => $values) {
                echo Html::label($criterionTitle);
                echo '</br>';
                $escapeCriterionTitle = escapeArray($criterionTitle);
                $attr = "result[$escapeBlockTitle][$escapeCriterionTitle][]";

                echo $form->field($model, $attr)->widget(Select2::className(), [
                    'data' => $values,
                ])->label('');
            }

            echo '</div>';
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
