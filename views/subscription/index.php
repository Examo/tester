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
        <?php else: ?>

            <?php $ratingData = new \app\models\CourseSubscription();?>

            <?php //\yii\helpers\VarDumper::dump($ratingData->getCourseRating(3), 10, true); ?>

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

                    <?php $courseRating = $ratingData->getCourseRating($course->id); ?>

                    <div class="portlet-title" style="margin-top: -13px">
                        <center><div class="caption caption-md">
                                <i class="icon-bar-chart theme-font-color hide"></i>
                                <span class="caption-subject theme-font-color bold uppercase"><br>Рейтинг учащихся</span>

                            </div></center>

                    </div>
                    <div class="portlet-body">

                        <div class="table-scrollable table-scrollable-borderless">
                            <table class="table table-hover table-light">
                                <thead>
                                <tr class="uppercase">
                                    <th colspan="2">
                                        Учащийся
                                    </th>
                                    <th>
                                        "Еда"
                                    </th>
                                    <th>
                                        "Уборка"
                                    </th>
                                    <th>
                                        "Игра"
                                    </th>
                                    <th>
                                        "Учёба"
                                    </th>
                                    <th>
                                        Всего
                                    </th>
                                    <th>
                                        Место
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $number = 1; ?>
                                <?php foreach ($courseRating['rating'] as $userId => $userPoints):?>
                                    <tr<?php foreach ($courseRating['data'] as $userData): ?>
                                        <?php if ($userData['user_id'] == $userId && $userData['isSelf'] == true): ?>
                                            <?= ' style="border-top: dashed; border-bottom: dashed; border-left: dashed; border-color: #1b7e5a;"'; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>>
                                        <td class="fit">
                                            <img class="user-pic" src="/i/hintemoticon.jpg">
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="primary-link"><?php foreach ($courseRating['data'] as $userData): ?>
                                                    <?php if ($userData['user_id'] == $userId && $userData['element_id'] == 1): ?>
                                                        <?= $userData['username']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?></a>
                                        </td>
                                        <td>
                                            <?php foreach ($courseRating['data'] as $userData): ?>
                                                <?php if ($userData['user_id'] == $userId && $userData['element_id'] == 1): ?>
                                                    <?= $userData['points']; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </td>
                                        <td>
                                            <?php foreach ($courseRating['data'] as $userData): ?>
                                                <?php if ($userData['user_id'] == $userId && $userData['element_id'] == 2): ?>
                                                    <?= $userData['points']; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>

                                        </td>
                                        <td>
                                            <?php foreach ($courseRating['data'] as $userData): ?>
                                                <?php if ($userData['user_id'] == $userId && $userData['element_id'] == 3): ?>
                                                    <?= $userData['points']; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            -
                                        </td>
                                        <td>
                                            -
                                        </td>
                                        <td>
                                            <?= $userPoints; ?>
                                        </td>

                                        <td>
                                            <span class="bold theme-font-color">
                                                <?php foreach ($courseRating['data'] as $userData): ?>
                                                    <?php if ($userData['user_id'] == $userId && $userData['element_id'] == 2): ?>
                                                        <?= $userData['position']; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?></span>
                                        </td>
                                    </tr>
                                    <?php $number++; ?>
                                <?php endforeach; ?>

                                </tbody></table>
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
    <?php endif; ?>
    </div>
</div>

<?php //\yii\helpers\VarDumper::dump($homeworksCount[1], 10, true); ?>