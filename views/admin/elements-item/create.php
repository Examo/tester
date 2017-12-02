<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ElementsItem */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('elements_item', 'Element_item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('elements_item', 'Elements_item'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="element-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
