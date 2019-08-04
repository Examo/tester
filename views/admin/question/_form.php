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

<?php

$comment = app\helpers\Json::encodeForJsParse($model['comment']);
$hint = app\helpers\Json::encodeForJsParse($model['hint']);
$TYPE_THREE_QUESTION = QuestionType::TYPE_THREE_QUESTION;
$TYPE_ASSOC_TABLE = QuestionType::TYPE_ASSOC_TABLE;
$script = <<< JS
            var id = $('#question_type_id').children('option:selected').val();

            if (id === "$TYPE_ASSOC_TABLE" || id === "$TYPE_THREE_QUESTION") {
                var comments = JSON.parse('$comment');
                var hints = JSON.parse('$hint');
            } else {
                var comments = '$comment';
                var hints = '$hint';
            }
            
            if (id === "$TYPE_THREE_QUESTION") {
                $("div[id^='question-comment-container']").each(function (i) {
                    $(this).show();
                    try {
                        $(this).find('textarea').val(comments[i]);
                    } catch {}
                });
                $("div[id^='question-hint-container']").each(function (i) {
                    $(this).show();
                    try {
                        $(this).find('textarea').val(hints[i]);
                    } catch {}
                });
                $("label[for=question-comment]").show();
            } else if (id === '$TYPE_ASSOC_TABLE') {
                $("div[id^='question-comment-container']:visible").each(function () {
                    $(this).hide();
                });
                $("div[id^='question-hint-container']:visible").each(function () {
                    $(this).hide();
                });
                $("label[for=question-comment]").hide();
                $('#question-hint-container').show();
                $('#question-hint-container').find('textarea').val(hints);
            } else {
                $("div[id^='question-comment-container']:visible").each(function () {
                    $(this).hide();
                });
                $("div[id^='question-hint-container']:visible").each(function () {
                    $(this).hide();
                });
                $('#question-comment-container').show();
                $('#question-comment-container').find('textarea').val(comments);
                $('#question-hint-container').show();
                $('#question-hint-container').find('textarea').val(hints);
                $("label[for=question-comment]").show();
            }
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
    <script>
        $(function () {
            $('#question_type_id').on('change', function () {
                var id = $(this).children('option:selected').val();
                if (id === "<?= $TYPE_ASSOC_TABLE ?>") {
                    $("div[id^='question-comment-container']:visible").each(function () {
                        $(this).hide();
                    });
                    $("div[id^='question-hint-container']:visible").each(function () {
                        $(this).hide();
                    });
                    $("label[for=question-comment]").hide();
                    $('#question-hint-container').show();
                } else if (id === "<?= $TYPE_THREE_QUESTION ?>") {
                    $("div[id^='question-comment-container']:hidden").each(function () {
                       $(this).show();
                    });
                    $("div[id^='question-hint-container']:hidden").each(function () {
                        $(this).show();
                    });
                    $("label[for=question-comment]").show();
                } else {
                    $("div[id^='question-comment-container']:visible").each(function () {
                        $(this).hide();
                    });
                    $("div[id^='question-hint-container']:visible").each(function () {
                        $(this).hide();
                    });
                    $('#question-comment-container').show();
                    $('#question-hint-container').show();
                    $("label[for=question-comment]").show();
                }
            });
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
                'attribute' => 'comment[]',
            ]) ?>
            <br />

            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'comment[]',
                'containerOptions' => ['style' => ['display' => 'none']]
            ]) ?>
            <br />

            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'comment[]',
                'containerOptions' => ['style' => ['display' => 'none']]
            ]) ?>
            <br />

            <?= $form->field($model, 'hint', ['template' => '{label}']) ?>
            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'hint[]',
            ]) ?>
            <br />

            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'hint[]',
                'containerOptions' => ['style' => ['display' => 'none']]
            ]) ?>
            <br />

            <?= MarkdownEditor::widget([
                'model' => $model,
                'attribute' => 'hint[]',
                'containerOptions' => ['style' => ['display' => 'none']]
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
