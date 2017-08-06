<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseLecturerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lecturer', 'Course Lecturers');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-lecturers-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php if (!empty($lecturers)): ?>
    <?php foreach ($lecturers as $lecturer): ?>

        <?php foreach ($users as $user): ?>
            <?php if ($lecturer->user_id == $user->id): ?>
                <p>Имя: <strong><?= $user->username?></strong>, ID: <?= $user->id?></p>
                <p><a href="<?= \yii\helpers\Url::to(['admin/course/lecturer', 'user_id' => $user->id])?>" class="btn btn-xs btn-success">К статистике преподавателя</a></p>

                <table class="table table-striped table-bordered">
                <tbody>
                <th class="col-md-1 text-center">Предмет</th>
                <th class="col-md-2 text-center">Название курса</th>
                <th class="col-md-1 text-center">Дата начала</th>
                <th class="col-md-1 text-center">ID курса</th>
                <th class="col-md-1 text-center">К статистике курса</th>

                <?php foreach ($courses as $course): ?>
                    <?php foreach ($lecturersCourses as $key => $lecturersCourse): ?>
                        <?php for ($i = 0; $i < count($lecturersCourses[$key]); $i++):?>
                            <?php if ($lecturersCourses[$key][$i]->course_id == $course->id && $lecturersCourses[$key][$i]->user_id == $user->id): ?>
                                <tr>

                                <!-- Предмет -->
                                <td class="text-center">
                                    <?php foreach ($disciplines as $discipline): ?>
                                        <?php if ($discipline->id == $course->discipline_id):?>
                                            <?= $discipline->name; ?>
                                        <?php endif; ?>
                                    <?php endforeach;?>
                                </td>

                                <!-- Название курса -->
                                <td class="text-center">
                                    <?= $course->name; ?>
                                </td>

                                <!-- Дата начала -->
                                <td class="text-center">
                                    <?= $course->start_time; ?>
                                </td>

                                <!-- ID курса -->
                                <td class="text-center">
                                    <?= $course->id ?>
                                </td>

                                <!-- К статистике курса -->
                                <td class="text-center">
                                    <a href="<?= \yii\helpers\Url::to(['admin/course/stat', 'course_id' => $course->id])?>" class="btn btn-xs btn-success">Перейти</a>
                                </td>

                                </tr>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                </tbody>
                </table>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>


