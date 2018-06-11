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
                                <div class="portlet box blue">
                                    <img src="/i/testcourse.jpg" style="width: 100% " />
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
                                            <strong>Дата начала курса</strong>: <?= $course->start_time; ?>
                                            <br><strong>Программа курса</strong>: <strong>1150</strong> тестов, <strong>35</strong> занятий с преподавателем, <strong>35</strong> домашних работ, <strong>8</strong> экзаменов
                                            <br><strong>Уже учеников</strong>: <strong style="font-size: large">321</strong>


                                        <div style="margin-top: 10px">
                                            <center><a href="<?= \yii\helpers\Url::to(['subscription/view', 'id' => $course->id]) ?>" class="btn btn-primary" style="font-size: large">Посмотреть курс</a></center>
                                        </div>

                                        <br>

                                        <div>
                                            <center><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-success" style="font-size: large">Подписаться</a></center>
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
