<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ar\Food */

$this->title = Yii::t('app', 'Create Food');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Foods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="food-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
