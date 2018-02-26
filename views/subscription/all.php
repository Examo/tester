<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
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
        <?php endif; ?>

        <?php foreach( $dataProvider->getModels() as $course ): ?>
            <?php foreach( $testLecturer as $lecturer ): ?>
                <?php if ($lecturer->course_id == $course->id):?>
                    <?php foreach ($users as $user): ?>
                        <?php if ($lecturer->user_id == $user->id): ?>


                            <div class="col-md-4 ">
                                <!-- BEGIN Portlet PORTLET-->
                                <div class="portlet box blue-hoki">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <?= $course->name ?>
                                        </div>

                                    </div>
                                    <div class="portlet-body">
                                        <p><?= $course->description ?>
                                        <div class="panel panel-default" style=" border-color: gainsboro">
                                            <div class="panel-heading">
                                                <div class="general-item-list">
                                                    <div class="item">
                                                        <div class="item-head">
                                                            <div class="item-details">
                                                                <img class="item-pic" src="/i/hintemoticon.jpg">
                                                                <div class="item-name primary-link">Преподаватель:<br><strong><?= $user->username; ?></strong></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <br>Дата начала курса: <?= $course->start_time; ?>
                                            <br>Программа курса: 1150 тестов, 35 занятий с преподавателем, 35 домашних работ, 8 экзаменов
                                            <br>Количество учеников: 123
                                        </p>

                                        <div>
                                            <center><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success">Подписаться и получать новые тесты</a></center>
                                        </div>

                                        <br>

                                        <div>
                                            <center><a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary">Просто перейти к тестам</a></center>
                                        </div>

                                    </div>

                                </div>
                                <!-- END Portlet PORTLET-->
                            </div>
                    <?php endif; ?>
        <?php endforeach;?>
                <?php endif; ?>
            <?php endforeach;?>

        <?php endforeach;?>

    </div>
</div>

    <?php //\yii\helpers\VarDumper::dump($testLecturer, 10, true); ?>
