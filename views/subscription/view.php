<?php
/**
 * @var \app\models\Course $course
 * @var \yii\web\View $this
 *
 */
$this->title = Yii::t('app', 'Program of course') . ' ' . $course->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <img src="/i/testcourse.jpg" style="width: 300px; margin-top: -135px; margin-left: -5px" />
        <label style="padding: 20px">Курс: <strong style="font-size: large"><?= $course->name ?></strong>
        <br>***** <strong style="font-size: large">(123 оценки)</strong>
        <br>Учеников: <strong style="font-size: large"><?php if (isset($numberOfPupils[$course->id])): ?>
                    <?= $numberOfPupils[$course->id]; ?>
                <?php else: ?>
                    <?= 'Пока что 0...'; ?>
                <?php endif; ?></strong>
            <br>
    <?php foreach( $testLecturer as $lecturer ): ?>
        <?php if ($lecturer->course_id == $course->id):?>
            <?php foreach ($users as $user): ?>
                <?php if ($lecturer->user_id == $user->id): ?>
                    <center>
                        Преподаватель:
                        <br><img src="/i/hintemoticon.jpg">
                        <br><strong><?= $user->username; ?></strong>
                    </center>
                <?php endif; ?>
            <?php endforeach;?>
        <?php endif; ?>
    <?php endforeach;?>
    </label></div>
        <div class="panel-heading">
            <center>
            <?php if (isset($courseTime['courseStartTime'])):?>
            <strong style="font-size: large">Дата начала курса</strong>: <?= $courseTime['courseStartTime']; ?>,<strong> уже прошло</strong>: <?= $courseTime['daysAfterCourseStart']; ?> д. <?= $courseTime['monthsAfterCourseStart'] ? '(или '. $courseTime['monthsAfterCourseStart'] . 'мес. и приблизительно ' . $courseTime['daysAfterMonthsAfterCourseStart'] . 'д.)': ''; ?>
            <br>
            <?php else: ?>
                <?= '<strong style="font-size: large">Дата начала курса ещё не установлена!</strong><br>';?>
            <?php endif; ?>
                <?php if (isset($courseTime['courseEndTime'])):?>
                    <strong style="font-size: large">Дата конца курса</strong>: <?= $courseTime['courseEndTime']; ?>,<strong> ещё остаётся</strong>: <?= $courseTime['daysBeforeCourseEnd']; ?> д. <?= $courseTime['monthsBeforeCourseEnd'] ? '(или '. $courseTime['monthsBeforeCourseEnd'] . 'мес. и приблизительно ' . $courseTime['daysAfterMonthsBeforeCourseEnd'] . 'д.)': ''; ?>
                    <br>
                <?php else: ?>
                    <?= '<strong style="font-size: large">Дата конца курса ещё не установлена!</strong><br>';?>
                <?php endif; ?>
                <strong style="font-size: large">Программа курса</strong>: тестов <strong><?= $challengesCount; ?></strong>, занятий с преподавателем <strong><?= $webinarsCount; ?></strong>, домашних работ <strong><?= $homeworksCount; ?></strong>, экзаменов <strong><?= $examsCount; ?></strong>
            <br><strong style="font-size: large">Уже учеников</strong>: <strong style="font-size: large"><?php if (isset($numberOfPupils[$course->id])): ?>
                        <?= $numberOfPupils[$course->id]; ?>
                    <?php else: ?>
                        <?= 'Пока что 0...'; ?>
                <?php endif; ?></strong></center>
        </div>
    <div class="panel-heading">
        <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
            <div>
                <center><a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default" style="padding: 20px; font-size: large">Отписка! (Не получать новые тесты по курсу)</a></center>
            </div>
        <?php else: ?>
            <div>
                <center><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success" style="padding: 20px; font-size: large">Подписаться и получать новые тесты</a></center>
            </div>
        <?php endif; ?>
    </div>

    <div class="panel-body">
        <div class="panel-heading">
            <strong style="font-size: large">Полное описание курса</strong>
        </div>
        <p><?= $course->description ?></p>
        <table class="table table-striped table-hover">
            <tr>
                <th class="col-md-5 text-left">Тест</th>
                <th class="col-md-1 text-center">Попытки</th>
                <th class="col-md-2 text-center">Последняя оценка</th>
                <th class="col-md-2 text-center">Пройти тест</th>
            </tr>
            <?php foreach( $course->getChallenges()->all() as $challenge ): ?>
                <tr>
                    <td><?= $challenge->name ?></td>
                    <td class="text-center"><?= $challenge->getAttemptsCount(Yii::$app->user->id) ?></td>
                    <td class="text-center">
                        <?php if ($challenge->getMarks(Yii::$app->user->id, $challenge->id)):?>
                            <?php foreach( $challenge->getMarks(Yii::$app->user->id, $challenge->id) as $markContainer):?>
                            <?php endforeach;?>
                            <strong><?= $markContainer->mark?></strong>
                        <?php endif;?>
                        <?php if (!($challenge->getMarks(Yii::$app->user->id, $challenge->id))):?>
                            <?= Yii::t('challenge', 'Nothing was found') ?>
                        <?php endif;?>
                    </td>
                    <td class="text-center">
                        <a href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $challenge->id])?>" class="btn btn-xs btn-success">Пройти тест</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
            <div class="pull-right">
                <p><a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default">Отписаться и не получать новые тесты по курсу</a></p>
            </div>
        <?php else: ?>
            <div class="pull-left">
                <p><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-primary">Подписаться и получать новые тесты</a></p>
            </div>
        <?php endif; ?>
    </div>

</div>

<?php //\yii\helpers\VarDumper::dump($courseTime, 10, true); ?>