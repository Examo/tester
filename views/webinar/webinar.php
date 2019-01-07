<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Вебинар';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($data):?>
    <p>
        <strong><?= $data['webinar_description']; ?></strong><br>
        Неделя в курсе: <strong><?= $data['webinar_week']; ?></strong><br>
        Начало: <?= $data['webinar_start']; ?><br>
        Окончание: <?= $data['webinar_end']; ?><br>
        Ссылка на YouTube для встраивания в страницу: <?= $data['webinar_link']; ?><br>
        Курс <strong><?= $data['course_name']; ?></strong>,
        <?= $data['isSubscribed'] ? 'подписка на курс оформлена!': 'не забудь подписаться на этот курс!'; ?>
    </p>
    <?php else: ?>
    <p>
        Такого вебинара пока что нет!
    </p>
    <?php endif; ?>

</div>
<div class="panel panel-default">
<div class="panel-body">
<center><label>Выполняли задание:</label>
    <strong>12 человек</strong></center>
    <center><label>Правильно: 2
    <br>Неправильно: 10</label></center>
<div class="progress">
    <div class="progress-bar progress-bar-info progress-bar-danger" role="progressbar" aria-valuenow="25.9" aria-valuemin="10" style="width: 25.9%">
    </div>
    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="74.1" aria-valuemin="10" style="width: 74%">
    </div>
</div>
    </div>
</div>



<div class="challenge-form">
    <script>
        $(function(){
            $('#tabs a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })
        });
    </script>

    <ul class="nav nav-tabs" id="tabs">
        <li role="presentation" class="active"><a href="#info">О вебинаре</a></li>
<?php if ($cleanWebinarChallenges): ?>
    <?php $numberOfChallenge = 0; ?>
    <?php foreach ($cleanWebinarChallenges['challenge'] as $cleanWebinarChallengeNumber => $cleanWebinarChallenge): ?>
        <?php $numberOfChallenge += 1; ?>
        <li role="presentation"><a href="#<?= $numberOfChallenge ?>">Тест №<?= $numberOfChallenge ?></a></li>
    <?php endforeach; ?>
<?php endif; ?>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active fade in" id="info">
            Тут общая информация о вебинаре
        </div>
        <?php if ($cleanWebinarChallenges): ?>
            <?php $numberOfChallenge = 0; ?>
    <?php foreach ($cleanWebinarChallenges['challenge'] as $cleanWebinarChallengeNumber => $cleanWebinarChallenge): ?>
                <?php $numberOfChallenge += 1; ?>
        <div role="tabpanel" class="tab-pane fade" id="<?= $numberOfChallenge ?>">
            <?php if ($cleanWebinarChallenges['isDone'][$cleanWebinarChallengeNumber] == 0): ?>
            Тест №<?= $numberOfChallenge ?> (В системе №<?= $cleanWebinarChallengeNumber ?>)<br>
            <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $cleanWebinarChallengeNumber, 'confirm' => true]) ?>" target="_blank">Начать тест №<?= $numberOfChallenge ?></a></p>
            <?php else: ?>
            Тест №<?= $numberOfChallenge ?> (В системе №<?= $cleanWebinarChallengeNumber ?>)<br>
            Уже выполнен!
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?php \yii\helpers\VarDumper::dump($data, 10, true) ?>

<?php //\yii\helpers\VarDumper::dump($webinar->getChallengesStatistic(3), 10, true); ?>
<?php
//$webinars = $webinar->getWebinarChallenges($data['webinar_week'], $data['webinar_exercise_id']);
//$webinars = $webinar->getChallengesStatistic($webinars);

//\yii\helpers\VarDumper::dump($cleanWebinarChallenges, 10, true);
?>