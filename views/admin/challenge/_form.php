<?php

use app\models\ar\Food;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use app\models\Course;
use app\models\Subject;
use app\models\ChallengeType;
use app\models\Element;
use app\models\ElementsItem;
use app\models\ChallengeMark;
use app\models\ChallengeGeneration;
use app\models\QuestionType;
use app\widgets\SubsetWidget;
use app\widgets\QuestionsList;

/* @var $this yii\web\View */
/* @var $model app\models\Challenge */
/* @var $form yii\widgets\ActiveForm */

$modelSettings = $model->settings ? $model->settings : new \app\models\ChallengeSettings();
?>

<div class="challenge-form">

    <?php echo \app\widgets\QuestionSelection::widget( ['id' => 'questions_popup'] ) ?>

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
        <li role="presentation" class="active"><a href="#challenge"><?= Yii::t('challenge', 'Challenge') ?></a></li>
        <li role="presentation"><a href="#questions"><?= Yii::t('question', 'Questions') ?></a></li>
        <li role="presentation"><a href="#settings"><?= Yii::t('challenge', 'Settings') ?></a></li>
        <li role="presentation"><a href="#marks"><?= Yii::t('challengeMark', 'Marks') ?></a></li>
    </ul>

    <br>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="challenge">
            <?= $form->field($model, 'name')->textarea(['rows' => 2]) ?>

            <?= $form->field($model, 'course_id')->widget(Select2::className(), [
                'data' => Course::getList(),
                'options' => [
                    'id' => 'course_id',
                    'multiple' => false
                ],
            ]) ?>

            <?= $form->field($model, 'subject_id')->widget(Select2::className(), [
                'data' => Subject::getList(),
                'options' => [
                    'id' => 'subject_id',
                    'multiple' => false
                ],
            ]) ?>

            <?= $form->field($model, 'challenge_type_id')->widget(Select2::className(), [
                'data' => ChallengeType::getList(),
                'options' => [
                    'id' => 'challenge_type_id',
                    'multiple' => false
                ],
            ]) ?>

            <?= $form->field($model, 'element_id')->widget(Select2::className(), [
                'data' => Element::getList(),
                'options' => [
                    'id' => 'element_id',
                    'multiple' => false,
                    'placeholder' => 'Выбери элемент'
                ],
            ]) ?>

            <?= $form->field($model, 'elements_item_id')->widget(DepDrop::className(), [
                'data' => ElementsItem::getList(),
                'options'=>['id'=>'elements_item-id'],
                'pluginOptions'=>[
                    'depends'=>['element_id'],
                    'placeholder'=>'Выбрать...',
                    'url'=>Url::to(['/admin/challenge/elements'])
                ]
            ]) ?>

            <?php // $form->field($model, 'food_id')->label('Продукт')->widget(\kartik\select2\Select2::className(), [
                //'data' => Food::getList(),
                //'options' => [
                //    'id' => 'id',
                //    'multiple' => false
                //],
            //]) ?>

            <?= $form->field($model, 'grade_number')->textInput() ?>

            <?= $form->field($model, 'week')->label('Неделя') ?>

            <?= $form->field($model, 'exercise_number')->textInput() ?>

            <?= $form->field($model, 'exercise_challenge_number')->textInput() ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="questions">
            <script>
                $(function(){
                    function updateMode() {
                        $('.mode').hide();
                        $('.mode.' + $(this).val()).show();
                    }

                    $('select[name="mode"]').change(updateMode);
                    updateMode.apply( $('select[name="mode"]') );
                });
            </script>

            <div class="form-group">
                <label class="control-label">Укажите метод составления теста</label>
                <?= yii\helpers\Html::dropDownList( 'mode', $model->mode, $model->modeLabels(), ['class' => 'form-control'] ) ?>
            </div>

            <div class="mode dynamic random">
                <?= $form->field($model, 'challengeGenerations')->widget(SubsetWidget::className(), [
                    'form' => $form,
                    'child' => ChallengeGeneration::className(),
                    'fields' => [
                        'id' => 'hiddenInput',
                        'question_type_id' => ['dropDownList',[QuestionType::getList()]],
                        'question_count' => 'textInput',
                    ]
                ]) ?>
            </div>
            <div class="mode dynamic">
                <a href="#" class="btn btn-warning generate" style="margin-top:-90px">Сгенерировать</a>
                <script>
                    $('.generate').click( function() {
                        var questions = $('#questions').data('plugin_questionsList');

                        var types = $('[name="ChallengeGeneration[question_type_id][]"]');
                        var counts = $('[name="ChallengeGeneration[question_count][]"]');

                        var rules = [];
                        types.each( function(i) {
                            rules.push({
                                type: $(types[i]).val(),
                                count: $(counts[i]).val()
                            });
                        } );

                        questions.clear();
                        $.post( '/admin/challenge/generate', {rules: rules}, function( resp ) {
                            for ( var id in resp ) {
                                questions.add( id, resp[id] );
                            }

                            $(this).prop('disabled', false);
                        } );

                        $(this).prop('disabled', true);

                        return false;
                    } );
                </script>
            </div>
            <div class="mode static dynamic">
                <?= $form->field($model, 'challengeHasQuestions')->widget( QuestionsList::className(), [
                    'id' => 'questions',
                    'name' => 'ChallengeHasQuestion',
                    'modalSelector' => '#questions_popup'
                ] ) ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="settings">
            <?= $form->field($modelSettings, 'immediate_result')->checkbox() ?>
            <?= $form->field($modelSettings, 'retries_enabled')->checkbox() ?>
            <?= $form->field($modelSettings, 'registration_required')->checkbox() ?>
            <?= $form->field($modelSettings, 'subscription_required')->checkbox() ?>
            <?= $form->field($modelSettings, 'start_time')->textInput() ?>
            <?= $form->field($modelSettings, 'finish_time')->textInput() ?>
            <?= $form->field($modelSettings, 'limit_time')->textInput() ?>
            <?= $form->field($modelSettings, 'limit_stop')->checkbox() ?>
            <?= $form->field($modelSettings, 'autostart')->checkbox() ?>
        </div>
        <div role="tabpanel" class="tab-pane fade" id="marks">
		
            <?= $form->field($model, 'challengeMarks')->widget(SubsetWidget::className(), [
                'form' => $form,
                'child' => ChallengeMark::className(),
                'fields' => [
                    'id' => 'hiddenInput',
                    'value_from' => 'textInput',
                    'value_to' => 'textInput',
                    'mark' => 'textInput',
                ]
            ])->label(false) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php //\yii\helpers\VarDumper::dump($challengesFood, 10, true)?>
