<?php
use yii\helpers\Html;
$this->title = Yii::t('grades', 'Pupil\'s Grades List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['admin/course/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses stats'), 'url' => ['/admin/course/stats']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?> <strong><?= $user->username?></strong>, ID<?= $user->id ?>: </h1>
    </div>
    <div class="panel-body">
        <?php if ($courseSubscriptions):?>
        <?php foreach ($courseSubscriptions as $courseSubscription): ?>
            <?php if ($courseSubscription->user_id == $user->id):?>
                <?php foreach ($courses as $course):?>
                    <?php if ($course->id == $courseSubscription->course_id):?>
                        <div class="panel panel-default">
                        <div class="panel-heading">
                            Курс: <strong><?= $course->name?></strong>
                        </div>
                        <div class="panel-body">
                            <?php $progress = $course->getProgress($user->id) ?>
                            <label>Выполнено по курсу:</label>
                            <strong><?= $progress ?>%</strong>
                            <div class="progress">
                                <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress ?>%">
                                </div>
                            </div>

                            <?php if ($progress != 0):?>

                            <table class="table table-striped table-bordered">
                                <tbody>
                                <th class="col-md-1 text-center">Тест</th>
                                <th class="col-md-1 text-center">Попытки</th>
                                <th class="col-md-1 text-center">Последняя оценка</th>
                                <th class="col-md-1 text-center">Средняя оценка</th>

                                <?php foreach ($course->getChallenges()->all() as $challenge):?>
                                    <?php if ($challenge->getAttemptsCount($user->id)):?>

                                    <tr>
                                        <!-- Тест -->
                                        <td class="text-center">
                                            №<?= $challenge->id ?>
                                        </td>

                                        <!-- Попытки -->
                                        <td class="text-center">
                                            <?= $challenge->getAttemptsCount($user->id)?>
                                        </td>
                                    <?php endif;?>
                                    <?php if ($challenge->getMarks($user->id, $challenge->id) && $challenge->getAttemptsCount($user->id)):?>

                                        <!-- Последняя оценка -->
                                        <td class="text-center">
                                            <?php foreach( $challenge->getMarks($user->id, $challenge->id) as $markContainer):?>
                                            <?php endforeach;?>
                                            <strong><?= $markContainer->mark?></strong>
                                        </td>

                                        <!-- Средняя оценка -->
                                        <td class="text-center">
                                            <?php $averageMark = 0; $number = 0; ?>
                                            <?php foreach( $challenge->getMarks($user->id, $challenge->id) as $markContainer):?>
                                                <?php $averageMark += $markContainer->mark; $number++; ?>
                                            <?php endforeach;?>
                                            <strong><?= $averageMark / $number; ?></strong>
                                        </td>
                                    </tr>
                                    <?php endif;?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>

                            <?php endif; ?>

                        </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
