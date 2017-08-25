<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ar\FeedSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Feeds');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feed-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Feed'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
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
    ]); ?>
</div>

<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Еда
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
                        <?= $challenge->id; ?> - исправить <br>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif;?>
    </div>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php //\yii\helpers\VarDumper::dump($challenges, 10, true)?>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>




