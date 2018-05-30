<?php
use app\models\ar\LearnObject;
use app\widgets\CleanWidget;
use app\widgets\FoodWidget;
use app\widgets\LearnWidget;
?>
<div class="panel panel-default element-container">
    <div class="panel-heading">
        <h4 class="panel-title">
            <?php if (isset($allCourses['number'])):?>
            Учёба. Количество твоих курсов: <strong><?= $allCourses['number']; ?></strong>
            <?php else: ?>
            Учёба. Количество твоих курсов: ноль целых ноль десятых...
            <?php endif; ?>
        </h4>
    </div>
    <div class="learning">
        <?php if ($all != null):?>
        <?php $learn = new \app\models\Learn();?>
        <?php //\yii\helpers\VarDumper::dump($all, 10, true); ?>
        <?php foreach ($all as $week):?>
            <?php $object = $learn->getObjectClass($week); ?>
            <?php $span = $learn->getTopLeftStyleNumber($week); ?>
            <?php if ($object):?>
                <span style="top: <?= $span['top']?>px; left: <?= $span['left']?>px; font-size:11px" title="<?= $week['week']?>-я неделя (<?= Yii::t('learnObjects', $week['object']);?>)"><center><strong><?= $week['week']?><br><?= $week['value']?>%</strong></center></span>
                    <div class="learning-progress-bar-object-block <?=$week['object']?>" style="background-image: url(/i/<?=$week['object']?>.png); background-repeat: no-repeat; background-size: <?= $object['background-size']?>px; height: <?= $object['height']?>px; width: <?= $object['width']?>px;  " title="<?= Yii::t('learnObjects', $week['object']);?> и <?= $week['week']?>-я неделя, <?= $week['value']?>% заполнено из 100%">
                        <div class="learning-progress-bar-object-fill" style="background-image: url(/i/<?=$week['object']?>negative.png); background-repeat: no-repeat;  background-size:  <?= $object['background-size']?>px; height:<?=$week['heightScaleValue']?>%; width:100%;  " title="<?= Yii::t('learnObjects', $week['object']);?> и <?= $week['week']?>-я неделя, <?= 100 - $week['value']?>% не заполнено из 100%"></div>
                    </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php else: ?>
            <p><br><br><br><br><center>Тут пока что ничего нет :(<br>А потому что <strong><a href="<?= \yii\helpers\Url::to(['subscription/all']) ?>">надо выбрать себе какой-нибудь курс!</strong></center></p>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="exercise-wrapper">
            <form class="exercise-block">
                <div class="button-wrapper">
                    <div>
                        <center>

                            <?= LearnWidget::widget(); ?>

                            <br>

                            <?= FoodWidget::widget(); ?>

                            <?= CleanWidget::widget(); ?>

                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

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

<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Прошедшие недели и обязательные тесты за целые недели
        </h4>
    </div>
    <div class="feading">
        <?php if (isset($data)):?>

        <?php $learn = new \app\models\Learn();?>
        <?php foreach ($data as $key => $value): ?>
        <div class="panel-body">
            <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                 <div class="panel-heading">
                    <div class="item">
                        <div class="item-head">
                            <div class="item-name primary-link">
                            <strong>Курс: <?= $key; ?><br></strong>
                            Текущая неделя: <?= $data[$key]['currentWeek']; ?><br><br>
                                </div>
            <?php for ($i = 0; $i <= count($value) - 2; $i++): ?>
                <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                    <div class="panel-heading">
                        <div class="item">
                            <div class="item-head">
                                <a href="/challenge/start?id=<?= $value[$i]['test']; ?>">
                                   <div class="item-name primary-link">
                                       Прошедшая неделя: <strong><?= $value[$i]['week']; ?></strong><br>
                                       <strong>Пройти общий тест для недели <?= $value[$i]['week']; ?></strong> на 10 минут
                                       <?php if (isset($value[$i]['realChallengeWeek'])):?>
                                       (<?= $value[$i]['realChallengeWeek'];?>)
                                       <?php endif; ?>
                                  </div></a>
                            </div>
                        </div>

                        <?php //\yii\helpers\VarDumper::dump($all, 10, true); ?>
                        <?php foreach ($all as $week):?>
                            <?php $object = $learn->getObjectClass($week) ?>
                            <?php $span = $learn->getTopLeftStyleNumber($week); ?>
                            <?php if ($object):?>
                                <?php if ($week['week'] == $value[$i]['week']):?>
                                    <p><center><strong><?= Yii::t('learnObjects', $week['object']);?></strong></center></p>
                                <span style="font-size:11px" title="<?= $week['week']?>-я неделя (<?= Yii::t('learnObjects', $week['object']);?>)"><center><strong>Заполнено:<br><?= $week['value']?>%</strong></center></span>
                                    <div style="color: <?= $value[$i]['isAttempt'] ? 'green' : 'red';?>; margin: 10px; "><center><strong><?= $value[$i]['isAttempt'] ? 'Тест УЖЕ ВЫПОЛНЕН!' : 'Тест ЕЩЁ НЕ ВЫПОЛНЕН!';?></strong></center></div>
                                <center><img src="/i/<?= $value[$i]['object'] ? $value[$i]['object'] : 'no_image' ?>.png" /></center><br>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                     <?php endfor; ?>
            <?php endforeach; ?>
            <?php endif;?>
    </div>
</div>

<?php// \yii\helpers\VarDumper::dump($all, 10, true); ?>

