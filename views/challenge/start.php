
<div class="panel panel-default">
    <div class="panel-heading">
        Информация о тесте
    </div>
    <div class="panel-body">
    <center>
            <h1><?= $challenge->name ?></h1>
        <p class="lead"><?= $challenge->description ?></p>
        <p>Курс: <?= $challenge->course->name ?></p>
        <p>Тема: <?= $challenge->subject->name ?></p>
        <?php if( $challenge->settings->limit_time ): ?>
            <p>Ограничение времени: <?= round($challenge->settings->limit_time / 60) ?> мин</p>
        <?php endif; ?>
        <p>Количество заданий: <?= $challenge->getQuestionsCount() ?></p>

        <p><a href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>"><img src="/i/<?= $challengeFood->food_name; ?>.png" /></a></p>

        <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['start', 'id' => $challenge->id, 'confirm' => true]) ?>">Начать этот тест</a></p>
    </center>
    </div>
</div>