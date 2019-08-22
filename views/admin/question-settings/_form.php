<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\widgets\QuestionSettingsEditor;
use app\models\QuestionType;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-settings-form" xmlns="http://www.w3.org/1999/html">
    <?php $form = ActiveForm::begin(); ?>
<?php

$script = <<< JS
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
    <script>
        $(function () {

        });
    </script>
    <br>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="question-settings">
            <?= $form->field($model, 'type_id')->widget(Select2::className(), [
                'data' => QuestionType::getList(),
                'options' => [
                    'id' => 'type_id',
                    'multiple' => false
                ],
            ]) ?>

            <?= $form->field($model, 'name')->textInput() ?>
            <br />

            <?= $form->field($model, 'settings')->widget(QuestionSettingsEditor::className()) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
