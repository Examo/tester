<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $searchModel app\models\search\ResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'All essays');
?>

<div class="page-container">
<div class="result-index">

    <?php echo Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]); ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
                'template' => '{update}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

    <table id="w0" class="table table-striped table-bordered detail-view">
        <tbody>
        <tr>
            <th class="col-md-2">Автор сочинения</th>
            <th class="col-md-3">Курс</th>
            <th class="col-md-1">Номер теста</th>
            <th class="col-md-2">Действия</th>

        </tr>
        <?php
        foreach ($userData as $esseyId => $data){
            print '
            <tr>
            <td>'. $data['username'] .'</td>
            <td>'. $data['course_name'] .'</td>
            <td>'. $data['challenge_id'] .'</td>
            <td><center><a href='. \yii\helpers\Url::to(['result/update', 'id' => $esseyId]) . ' " class="btn btn-xs btn-success">Проверить</a></center>
            <br>
            <center><a href='. \yii\helpers\Url::to(['result/view', 'id' => $esseyId]) . ' " class="btn btn-xs btn-success">Посмотреть</a></center></td>
            </tr>';
        }
        ?>

        </tbody>
    </table>