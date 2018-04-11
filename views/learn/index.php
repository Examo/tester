<?php
use app\widgets\CleanWidget;
use app\widgets\FoodWidget;
use app\widgets\LearnWidget;
?>
<div class="panel panel-default element-container">
    <div class="panel-heading">
        <h4 class="panel-title">
            Учёба
        </h4>
    </div>
    <center><img src="/i/learn.png" width="600" height="auto" /></center>
    <div class="learning">
    </div>
    <div class="row">
        <div class="exercise-wrapper">
            <form class="exercise-block">
                <div class="button-wrapper">
                    <div>
                        <center>

                            <?= LearnWidget::widget(); ?>

                            <?= FoodWidget::widget(); ?>

                            <?= CleanWidget::widget(); ?>

                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br>

<div class="panel panel-default required-container">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#required">Обязательные тесты</a>
        </h4>
    </div>
    <div id="required" class="collapse">
    <div class="panel-body">
        <?php if( !count($challenges) ): ?>
            <p class="text-center">
                Тут пока что ничего нет :(
            </p>
        <?php else: ?>
            <h3>
                Свежие задания
            </h3>
        <?php endif; ?>

        <?php foreach( $challenges as $challenge ): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $challenge->name ?>
                </div>
                <div class="panel-body">
                    <p><?= $challenge->description ?></p>
                    <p>
                        <label>Курс:</label>
                        <strong><?= $challenge->course->name ?></strong>
                    </p>
                    <p>
                        <label>Заданий:</label>
                        <strong><?= $challenge->getQuestionsCount() ?></strong>
                    </p>
                    <p>
                        <label>Время выполнения:</label>
                        <strong>5 минут</strong>
                    </p>
                    <div class="pull-left">
                        <a href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $challenge->id]) ?>" class="btn btn-success">Перейти к тесту</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>

</div>

<div class="panel panel-default passed-container">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" href="#passed">Пройденные тесты по курсам</a>
        </h4>
    </div>
    <div id="passed" class="collapse">
    <div class="panel-body">
        <?php if( !$dataProvider->getCount() ): ?>
            <p class="text-muted text-center">
                Здесь вообще пусто!
            </p>
            <p class="text-muted text-center">
                Потому что у тебя ещё нет курсов! <strong><a href="<?= \yii\helpers\Url::to(['subscription/all']) ?>">Выбери себе какой-нибудь курс — и всё появится!</a></strong>.
            </p>
        <?php endif; ?>

        <?php foreach( $dataProvider->getModels() as $course ): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <label>Курс:</label>
                    <strong><?= $course->name ?></strong>
                </div>
                <div class="panel-body">
                    <?php $progress = $course->getProgress( Yii::$app->user->id ) ?>
                    <label>Выполнено по курсу:</label>
                    <strong><?= $progress ?>%</strong>
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%">
                            <span class="sr-only"><?= $progress ?>% Complete</span>
                        </div>
                    </div>
                    <div class="pull-left">
                        <a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary">Посмотреть все выполненные тесты</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    </div>
</div>