<?php
/**
 * @var \app\models\Course $course
 * @var \yii\web\View $this
 *
 */
use app\widgets\RatingWidget;

$this->title = Yii::t('app', 'Program of course') . ' ' . $course->name;
$this->params['breadcrumbs'][] = $this->title;
setlocale(LC_ALL, 'ru_RU.UTF8');
?>

<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <img src="/i/course<?= $course->id; ?>.jpg" style="width: 300px; margin-top: -135px; margin-left: -5px" />
        <label style="padding: 20px">Курс: <strong style="font-size: large"><?= $course->name ?></strong>
        <br><!--***** <strong style="font-size: large">(123 оценки)</strong>-->
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
            <br><strong style="font-size: large">Уже учеников</strong>: <strong style="font-size: large">
                    <?php if (isset($numberOfPupils[$course->id])): ?>
                        <?= $numberOfPupils[$course->id]; ?>
                    <?php else: ?>
                        <?= 'Пока что 0...'; ?>
                    <?php endif; ?></strong></center>
        </div>

        <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
        <?php else: ?>
        <div class="panel-heading">
            <div>
                <center><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success" style="padding: 20px; font-size: large">Подписаться и получать новые тесты</a></center>
            </div>
        </div>
        <?php endif; ?>

    <?= RatingWidget::widget(['courseId' => 1]); ?>

    <div class="panel-body">
        <div class="panel-heading text-center" style="margin-top: -40px">
            <strong style="font-size: large" class="caption-subject theme-font-color bold uppercase">Полное описание курса</strong>
        </div>
        <p style="font-size: large"><?= $course->description ?></p>

        <div class="portlet-title text-center"><strong style="font-size: large" class="caption-subject theme-font-color bold uppercase">Все вебинары по этому курсу:</strong><br><br></div>

        <center><label>Выполнено вебинаров <strong><?= $latestData['counted']; ?> из <?= count($latestData['data']) ?></label></center>
        <?php
        if (isset($latestData['data']) && $latestData['data'] != []) {
            $oneWebinarPercents = 100 / count($latestData['data']);
            $doneWebinars = $latestData['counted'] * $oneWebinarPercents;
            $notDoneWebinars = 100 - $doneWebinars;
        } else {
            $doneWebinars = $notDoneWebinars = 0;
        }
        ?>
        <div class="progress">
            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="74.1" aria-valuemin="10" style="width: <?= $doneWebinars; ?>%">
            </div>
            <div class="progress-bar progress-bar-info progress-bar-danger" role="progressbar" aria-valuenow="25.9" aria-valuemin="10" style="width: <?= $notDoneWebinars; ?>%">
            </div>
        </div>
        <br>
        <div class="todo-tasklist">
        <?php foreach ($latestData['data'] as $key => $webinar): ?>
                <?php if (isset($webinar['webinar_done']) && $webinar['webinar_done'] == 1):?>
                    <?php $borderColor = 'green'; ?>
                <?php endif; ?>
                <?php if (isset($webinar['webinar_done']) && $webinar['webinar_done'] == 0):?>
                    <?php $borderColor = 'red'; ?>
                <?php endif; ?>
                <?php if (!isset($webinar['webinar_done'])):?>
                    <?php $borderColor = 'yellow'; ?>
                <?php endif; ?>
            <div class="todo-tasklist-item todo-tasklist-item-border-<?= $borderColor; ?>">
                <img class="todo-userpic pull-left" src="/i/hintemoticon.jpg" width="27px" height="27px">
                <div class="todo-tasklist-item-title">
                    Вебинар №<?= $webinar['webinar_number']; ?>
                </div>
                <div class="todo-tasklist-item-text">
                    <strong><?= $webinar['webinar_description']; ?></strong>
                </div>
                <div class="todo-tasklist-item-text">
                    <?= $webinar['webinar_week']; ?>-я неделя курса
                </div>
                <div class="todo-tasklist-controls">
                    <span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?= $webinar['webinar_start']; ?></span>
                    <p>
                    <?php if (isset($webinar['webinar_done']) && $webinar['webinar_done'] == 1):?>
                    <center><a href="<?= \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_number']])?>"<button type="button" class="btn green">Вебинар пройден, все тесты выполнены</button></a></center>
                    <?php endif; ?>
                    <?php if (isset($webinar['webinar_done']) && $webinar['webinar_done'] == 0):?>
                    <center><a href="<?= \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_number']])?>"<button type="button" class="btn red-sunglo">Вебинар не пройден! Нажми и выполни тесты!</button></a></center>
                    <?php endif; ?>
                    <?php if (!isset($webinar['webinar_done'])):?>
                    <center><button type="button" class="btn yellow">Вебинар ещё не проводился</button></center>
                    <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
        <br>
        <div class="portlet-title text-center"><strong style="font-size: large" class="caption-subject theme-font-color bold uppercase">Все тесты по курсу:</strong><br><br></div>
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
    </div>
</div>
<?php //\yii\helpers\VarDumper::dump($latestData, 10, true); ?>


