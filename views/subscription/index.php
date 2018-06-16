<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
$this->title = Yii::t('app', 'My courses');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Мои курсы
    </div>
    <div class="panel-body">
        <?php if( !$dataProvider->getCount() ): ?>
            <p class="text-muted text-center">
                Здесь пусто!
            </p>
            <p class="text-muted text-center">
                Мало просто зарегистрироваться, нужно ещё и <strong><a href="<?= \yii\helpers\Url::to(['subscription/all']) ?>">выбрать себе какой-нибудь курс</a></strong>.
            </p>
        <?php endif; ?>

        <?php foreach( $dataProvider->getModels() as $course ): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <center><a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>"><img src="/i/testcourse.jpg" style="width: 300px;" /></a>
                        <label style="padding: 20px">Курс: <strong style="font-size: large"><?= $course->name ?></strong>
                            <br>***** <strong style="font-size: large">(123 оценки)</strong>
                            <br><strong style="font-size: large">Уже учеников</strong>: <strong style="font-size: large"><?= $numberOfPupils[$course->id] ?></strong>
                            <br>
                            <?php if (isset($courseTime[$course->id]['courseStartTime'])):?>
                                <strong style="font-size: large">Дата начала курса</strong>: <?= $courseTime[$course->id]['courseStartTime']; ?>,<strong> уже прошло</strong>: <?= $courseTime[$course->id]['daysAfterCourseStart']; ?> д. <?= $courseTime[$course->id]['monthsAfterCourseStart'] ? '(или '. $courseTime[$course->id]['monthsAfterCourseStart'] . 'мес. и приблизительно ' . $courseTime[$course->id]['daysAfterMonthsAfterCourseStart'] . 'д.)': ''; ?>
                                <br>
                            <?php else: ?>
                                <?= '<strong style="font-size: large">Дата начала курса ещё не установлена!</strong><br>';?>
                            <?php endif; ?>
                            <?php if (isset($courseTime[$course->id]['courseEndTime'])):?>
                                <strong style="font-size: large">Дата конца курса</strong>: <?= $courseTime[$course->id]['courseEndTime']; ?>,<strong> ещё остаётся</strong>: <?= $courseTime[$course->id]['daysBeforeCourseEnd']; ?> д. <?= $courseTime[$course->id]['monthsBeforeCourseEnd'] ? '(или '. $courseTime[$course->id]['monthsBeforeCourseEnd'] . 'мес. и приблизительно ' . $courseTime[$course->id]['daysAfterMonthsBeforeCourseEnd'] . 'д.)': ''; ?>
                                <br>
                            <?php else: ?>
                                <?= '<strong style="font-size: large">Дата конца курса ещё не установлена!</strong><br>';?>
                            <?php endif; ?>
                            <strong style="font-size: large">Программа курса</strong>: тестов <strong><?= $challengesCount[$course->id]; ?></strong>, занятий с преподавателем <strong><?= $webinarsCount[$course->id]; ?></strong>, домашних работ <strong><?= $homeworksCount[$course->id]; ?></strong>, экзаменов <strong><?= $examsCount[$course->id]; ?></strong>
                            </label></center>
                </div>
                <div class="panel-body">
                    <?php $progress = $course->getProgress( Yii::$app->user->id ) ?>
                    <center><label>Выполнено по курсу:</label>
                    <strong><?= $progress ?>%</strong></center>
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%">
                        </div>
                    </div>
                    <div class="pull-left">
                        <a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary">Посмотреть программу курса</a>
                    </div>
                    <div class="pull-right">
                        <a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default">Отписка! (Не получать новые тесты по курсу)</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
</div>

<?php //\yii\helpers\VarDumper::dump($homeworksCount[1], 10, true); ?>