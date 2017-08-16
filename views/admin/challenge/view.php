<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Challenge */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-view">

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
            'course_id',
            'challenge_type_id',
            'element_id',
            'subject_id',
            'grade_number',
            'name:ntext',
            'description:ntext',
            'week',
            'exercise_number',
            'exercise_challenge_number',
        ],
    ]) ?>

    <?php foreach ($food as $product):?>
    <?php if ($product->id == $challengeFood->food_id): ?>

        <table class="table table-striped table-bordered detail-view">
            <tbody>
            <tr>
                <th class="col-md-3">Продукт</th>
                <td><strong><?= $product->food_name; ?></strong>, ID<?= $product->id; ?></td>
            </tr>
            </tbody>
        </table>

    <?php endif; ?>
    <?php endforeach; ?>

</div>
