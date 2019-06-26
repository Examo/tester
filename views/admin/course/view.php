<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Course */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('course', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name:ntext',
            'description:ntext',
            'start_time',
            'position'
        ],
    ]) ?>

    <?php foreach ($users as $user):?>
        <?php if (isset($lecturer[0])): ?>
            <?php if ($user->id == $lecturer[0]->user_id): ?>

            <table class="table table-striped table-bordered detail-view">
                <tbody>
                <tr>
                    <th class="col-md-3">Преподователь курса</th>
                    <td><strong><?= $user->username; ?></strong>, ID<?= $user->id; ?></td>
                </tr>
                </tbody>
            </table>

            <?php endif; ?>
        <?php else: ?>

            <table class="table table-striped table-bordered detail-view">
                <tbody>
                <tr>
                    <th class="col-md-3">Преподователь курса</th>
                    <td><strong>Ещё не назначен!</strong></td>
                </tr>
                </tbody>
            </table>
            <?php break; ?>

        <?php endif; ?>
    <?php endforeach; ?>

</div>