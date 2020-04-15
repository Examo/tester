<?php

use app\widgets\CleanWidget;
use app\widgets\LearnWidget;
use yii\helpers\Html;
use yii\grid\GridView;
use app\widgets\FoodWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ar\FeedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Feed');
$this->params['breadcrumbs'][] = $this->title;
//$foodTest = "Моя первая ноdfdfdfdfвость";
?>
<div class="feed-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php // Html::a(Yii::t('app', 'Create Feed'), ['create'], ['class' => 'btn btn-success']) ?>

    <?php /* GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'course_subscription_id',
            'user_id',
            'week_id',
            'challenges_done:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
</div>

<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            <?= Html::encode($this->title) ?> <font face="webdings" title="Покорми кошку – выбери любое блюдо и выполни тест. Шкала Еды будет вырастать в конце теста, а кошка будет довольна.
Каждую минуту шкала Еды уменьшается на 1%, а когда опустится до 0% –  кошка Лиза проголодается и расстроится…
Не расстраивай кошку, пожалуйста!
"> i </font>
        </h4>
    </div>
    <center><img src="/i/refrigerator.png" width="400" height="auto" /></center>
    <div class="feeding">

        <?php $classes = []; ?>

        <?php if(!empty($feedingTests)):?>
            <?php if(!empty($challenges)):?>
                <?php $number = 0; ?>
                <?php foreach ($challenges as $challenge): ?>
                    <?php if(!empty($feedingTests->getChallengeFood($challenge->id)->name)):?>
                        <?php if($challenge->element_id == 1):?>
                        <?php $class = $challenge->getChallengeFood($challenge->id)->name; ?>
                        <?php $all = $feedingTests->getClass($classes, $class); ?>
                        <?php $classes = $all['classes']; ?>

                        <a href="/challenge/start?id=<?= $challenge->id; ?>"><img src="<?= $feedingTests->getImageFeeding($feedingTests->getChallengeFood($challenge->id)->name); ?>" title="Тест <?= $challenge->id; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $all['currentClass']; ?>" /></a>

                        <?php $number++; ?>

                        <?php else: ?>
                        Добавить продукт в тест №<?= $challenge->id; ?><br>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif;?>
    </div>


    <div class="row">
        <div class="exercise-wrapper">


        <form class="exercise-block">

            <div class="button-wrapper">
                <div>
                    <center>
                        <!--<a href="#"><div class="bar-wrapper"><p>Учёба</p>
                                <div class="training-progress-bar-block">
                                    <div class="training-progress-bar-fill" style="height: 50%; width:100%;"><center><p><b>50%</b></p></center></div>
                                </div>
                            </div></a>-->

                        <?= LearnWidget::widget(); ?>

                        <br>
                        
                        <?= FoodWidget::widget(); ?>

                        <?= CleanWidget::widget(); ?>

                        <!--<a href="#"><div class="bar-wrapper"><p>Еда</p>
                                <div class="feeding-progress-bar-block" style="<?php //if ($scaleValue <= 10) {echo 'background-color: red;';}?>">
                                    <div class="feeding-progress-bar-fill" style="height: <?php // 100 - $scaleValue; ?>%; width:100%;"><center><p><b><?php // $scaleValue; ?>%</b></p></center></div>
                                </div>
                            </div></a> -->
                        <!--<a href="#"><div class="bar-wrapper"><p>Уборка</p>
                                <div class="cleaning-progress-bar-block">
                                    <div class="cleaning-progress-bar-fill" style="height: 77%; width:100%;"><center><p><b>23%</b></p></center></div>
                                </div>
                            </div></a>

                        <a href="#"><div class="bar-wrapper"><p>Игры</p>
                                <div class="gaming-progress-bar-block">
                                    <div class="gaming-progress-bar-fill" style="height: 67%; width:100%;"><center><p><b>33%</b></p></center></div>
                                </div>
                            </div></a>-->
                    </center>
                </div>
            </div>

        </form>

        </div>
    </div>
 </div>
<!--<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>-->
<?php //\yii\helpers\VarDumper::dump($scaleTwist, 10, true)?>
<?php //\yii\helpers\VarDumper::dump($scale->last_time, 10, true)?>
<?php //\yii\helpers\VarDumper::dump($scale->points, 10, true)?>
<?php //\yii\helpers\VarDumper::dump($allLastChallengeQuestionsCost, 10, true)?>
<?php //\yii\helpers\VarDumper::dump($finishCostAmount, 10, true)?>

<?php //\yii\helpers\VarDumper::dump($newFeedChallenges, 10, true)?>

<?php //\yii\helpers\VarDumper::dump(Yii::$app->getFormatter()->asTimestamp($finishTime), 10, true)?>

<?php //\yii\helpers\VarDumper::dump(Yii::$app->getFormatter()->asTimestamp(time()) - Yii::$app->getFormatter()->asTimestamp(scaleTwist), 10, true)?>





