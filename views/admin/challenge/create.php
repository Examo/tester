<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Challenge */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('challenge', 'Challenge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
