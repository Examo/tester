<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CourseLecturerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('lecturer', 'Courses of the Lecturer') . ' ' . $user->username . ' (ID ' . $user->id . ')';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('lecturer', 'Course Lecturers'), 'url' => ['admin/course/lecturers']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="course-lecturer-index">

        <h1><?= Html::encode($this->title) ?></h1>

    </div>

<?php //\yii\helpers\VarDumper::dump($lecturer, 10, true)?>

<?php foreach ($courses as $course): ?>
    <?php foreach ($lecturer as $item): ?>
        <?php if ($course->id == $item->course_id):?>
            <table class="table table-striped table-bordered">
                <tbody>
                <th class="col-md-1 text-center">ID курса</th>
                <th class="col-md-2 text-center">Предмет</th>
                <th class="col-md-2 text-center">Название</th>
                <th class="col-md-2 text-center">Описание</th>
                <th class="col-md-2 text-center">Дата начала</th>
                <th class="col-md-1 text-center">К статистике курса</th>

                <tr>
                    <!-- ID ученика -->
                    <td class="text-center">
                        <?= $item->course_id; ?>
                    </td>

                    <!-- Предмет -->
                    <td class="text-center">
                    <?php foreach ($disciplines as $discipline): ?>
                        <?php if ($discipline->id == $course->discipline_id):?>
                            <?= $discipline->name; ?>
                        <?php endif; ?>
                    <?php endforeach;?>
                    </td>

                    <!-- Название -->
                    <td class="text-center">
                        <?= $course->name; ?>
                    </td>

                    <!-- Описание -->
                    <td class="text-center">
                        <?= $course->description; ?>
                    </td>

                    <!-- Дата начала -->
                    <td class="text-center">
                        <?= $course->start_time; ?>
                    </td>

                    <!-- К статистике курса -->
                    <td class="text-center">
                        <a href="<?= \yii\helpers\Url::to(['admin/course/stat', 'course_id' => $course->id])?>" class="btn btn-xs btn-success">Перейти</a>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>



