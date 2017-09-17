<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ar\FeedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Feed');
$this->params['breadcrumbs'][] = $this->title;
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
            <?= Html::encode($this->title) ?>
        </h4>
    </div>
    <center><img src="/i/refrigerator.png" width="600" height="auto" /></center>
    <div class="feeding">

        <?php $classes = []; ?>

        <?php if(!empty($feedingTests)):?>
            <?php if(!empty($challenges)):?>
                <?php $number = 0; ?>
                <?php foreach ($challenges as $challenge): ?>
                    <?php if(!empty($feedingTests->getChallengeFood($challenge->id)->food_name)):?>

                        <?php $class = $challenge->getChallengeFood($challenge->id)->food_name; ?>
                        <?php $all = $feedingTests->getClass($classes, $class); ?>
                        <?php $classes = $all['classes']; ?>

                    <a href="/challenge/start?id=<?= $challenge->id; ?>"><img src="<?= $feedingTests->getImageFeeding($feedingTests->getChallengeFood($challenge->id)->food_name); ?>" title="Тест <?= $challenge->id; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $all['currentClass']; ?>" /></a>

                        <?php $number++; ?>

                    <?php else: ?>
                        <?= $challenge->id; ?> - добавить продукт в тест <br>
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
                        <a href="#"><div class="bar-wrapper"><p>Учёба</p>
                                <div class="training-progress-bar-block">
                                    <div class="training-progress-bar-fill" style="height: 50%; width:100%;"><center><p><b>50%</b></p></center></div>
                                </div>
                            </div></a>

                        <a href="#"><div class="bar-wrapper"><p>Еда</p>
                                <div class="feeding-progress-bar-block">
                                    <div class="feeding-progress-bar-fill" style="height: 60%; width:100%;"><center><p><b>40%</b></p></center></div>
                                </div>
                            </div></a>

                        <a href="#"><div class="bar-wrapper"><p>Уборка</p>
                                <div class="cleaning-progress-bar-block">
                                    <div class="cleaning-progress-bar-fill" style="height: 77%; width:100%;"><center><p><b>23%</b></p></center></div>
                                </div>
                            </div></a>

                        <a href="#"><div class="bar-wrapper"><p>Игры</p>
                                <div class="gaming-progress-bar-block">
                                    <div class="gaming-progress-bar-fill" style="height: 67%; width:100%;"><center><p><b>33%</b></p></center></div>
                                </div>
                            </div></a>
                    </center>
                </div>
            </div>

        </form>

        </div>
    </div>
 </div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php \yii\helpers\VarDumper::dump($scaleTwist, 10, true)?>
<?php \yii\helpers\VarDumper::dump($scale->last_time, 10, true)?>
<?php \yii\helpers\VarDumper::dump($scalePoints, 10, true)?>
<?php \yii\helpers\VarDumper::dump($allLastChallengeQuestionsCost, 10, true)?>
<?php \yii\helpers\VarDumper::dump($finishCostAmount, 10, true)?>

<?php //\yii\helpers\VarDumper::dump($scaleValue, 10, true)?>

<?php //\yii\helpers\VarDumper::dump(Yii::$app->getFormatter()->asTimestamp($finishTime), 10, true)?>

<?php //\yii\helpers\VarDumper::dump(Yii::$app->getFormatter()->asTimestamp(time()) - Yii::$app->getFormatter()->asTimestamp(scaleTwist), 10, true)?>





