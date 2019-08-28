<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Answer */

$this->title = 'Проверенное сочинение в тесте №' . $data['challenge_id'] . ' по курсу ' . $data['course_name'] . ', ученик ' . $data['username'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'All essays'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Essay checked');
?>

<div class="page-container">

    <?php echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>

<div class="result-view">

    <h1><?= 'Проверенное сочинение в тесте <strong>№' . $data['challenge_id'] . '</strong> по курсу <strong>' . $data['course_name'] . '</strong>, ученик <strong>' . $data['username'] . '</strong>';?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

<table id="w0" class="table table-striped table-bordered detail-view">
    <tbody>
    <tr>
        <th>Сочинение в тесте №</th>
        <td><?= $data['challenge_id'] ?></td>
    </tr>

    <tr>
        <th>Курс</th>
        <td><?= $data['course_name'] ?></td>
    </tr>

    <tr>
        <th>Выполнял ученик</th>
        <td><?= $data['username'] ?></td>
    </tr>

    <tr>
        <th>Сочинение</th>
        <td><?= $model->data ?></td>
    </tr>

    <tr>
        <th>Результаты</th>
        <td><?php $result = json_decode($model->result, true) ?>

            <table id="w0" class="table table-striped table-bordered detail-view">
                <tbody>
                <tr>
                    <th class="col-md-6">Критерии</th>
                    <th class="col-md-4">Балл</th>
                </tr>

                <?php foreach ($result as $sections => $criterions): ?>
                    <?php foreach ($criterions as $description => $point): ?>
                <tr>
                    <td><?= $description; ?></td>
                    <td><?= $point[0]; ?></td>
                </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                </tbody>
            </table>

        </td>
    </tr>

    <tr>
        <th>Комментарий проверяющего</th>
        <td>Текст комментария</td>
    </tr>

    </tbody>
</table>
</div>
</div>

<!--<div class="result-view">

    <h1><?= $this->title ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'data',
            'id',
            'result'
        ],
    ]) ?>

</div>-->