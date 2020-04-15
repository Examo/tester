<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
$this->title = Yii::t('app', 'All available courses');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Доступные курсы
    </div>
    <div class="panel-body">
        <?php if( !$dataProvider->getCount() ): ?>
            <p class="text-muted text-center">
                Нет ничего нового, зайди попозже!
            </p>
            <p class="text-muted text-center">
                Или можешь <strong><a href="<?= \yii\helpers\Url::to(['/subscription']) ?>">посмотреть прежние тесты, которые уже прошли по разным курсам. А вдруг что-то было упущено?</a></strong>.
            </p>
        <?php else: ?>
            <?php foreach( $dataProvider->getModels() as $course ): ?>
                <?php foreach( $testLecturer as $lecturer ): ?>
                    <?php if ($lecturer->course_id == $course->id):?>
                        <?php foreach ($users as $user): ?>
                            <?php if ($lecturer->user_id == $user->id): ?>
                                <div class="col-md-4 ">
                                    <!-- BEGIN Portlet PORTLET-->
                                    <div class="portlet box red">
                                        <img src="/i/course<?= $course->id; ?>.jpg" style="width: 100% " />
                                        <div class="portlet-title"style="text-align: center">
                                            <div class="caption" style="text-align: center">
                                                <center><?= $course->name ?></center>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="panel-heading" style="margin-top: -10px">
                                                <center>Преподаватель:<br><img class="item-pic" src="/i/hintemoticon.jpg">
                                                <div class="item-name primary-link" style="margin-bottom: -10px"><strong><?= $user->username; ?></strong></div></center>
                                            </div>
                                <?php if (isset($courseTime[$course->id]['courseStartTime'])):?>
                                <strong>Дата начала курса</strong>: <?= $courseTime[$course->id]['courseStartTime']; ?>,<strong> уже прошло</strong>: <?= $courseTime[$course->id]['daysAfterCourseStart']; ?> д. <?= $courseTime[$course->id]['monthsAfterCourseStart'] ? '(или '. $courseTime[$course->id]['monthsAfterCourseStart'] . 'мес. и приблизительно ' . $courseTime[$course->id]['daysAfterMonthsAfterCourseStart'] . 'д.)': ''; ?>
                                <br>
                            <?php else: ?>
                                <?= '<strong>Дата начала курса ещё не установлена!</strong><br>';?>
                            <?php endif; ?>

                            <?php if (isset($courseTime[$course->id]['courseEndTime'])):?>
                                <strong>Дата конца курса</strong>: <?= $courseTime[$course->id]['courseEndTime']; ?>,<strong> ещё остаётся</strong>: <?= $courseTime[$course->id]['daysBeforeCourseEnd']; ?> д. <?= $courseTime[$course->id]['monthsBeforeCourseEnd'] ? '(или '. $courseTime[$course->id]['monthsBeforeCourseEnd'] . 'мес. и приблизительно ' . $courseTime[$course->id]['daysAfterMonthsBeforeCourseEnd'] . 'д.)': ''; ?>
                                <br>
                            <?php else: ?>
                                <?= '<strong>Дата конца курса ещё не установлена!</strong><br>';?>
                            <?php endif; ?>
                            <strong>Программа курса</strong>: тестов <strong><?= $challengesCount[$course->id]; ?></strong>, занятий с преподавателем <strong><?= $webinarsCount[$course->id]; ?></strong>, домашних работ <strong><?= $homeworksCount[$course->id]; ?></strong>, экзаменов <strong><?= $examsCount[$course->id]; ?></strong>
                                                <br><strong style="font-size: large">Уже учеников</strong>: <strong style="font-size: large"><?php if (isset($numberOfPupils[$course->id])): ?>
                                                    <?= $numberOfPupils[$course->id]; ?>
                                                    <?php else: ?>
                                                    <?= '0+'; ?>
                                                <?php endif; ?></strong>
                                            <div style="margin-top: 10px">
                                                <center><a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary" style="font-size: large">Посмотреть курс</a></center>
                                            </div>
                                            <br>
                                            <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
                                                <div>
                                                    <center><a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default" style="padding: 20px; font-size: large">Отписка! (Не получать новые тесты по курсу)</a></center>
                                                </div>
                                            <?php else: ?>
                                                <div>
                                                    <center><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success" style="font-size: large">Подписаться</a></center>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach;?>
                    <?php endif; ?>
                <?php endforeach;?>
            <?php endforeach;?>
        <?php endif; ?>
    </div>
</div>

<?php //\yii\helpers\VarDumper::dump($numberOfPupils, 10, true); ?>
