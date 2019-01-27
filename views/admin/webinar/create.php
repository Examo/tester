<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Webinar */

$this->title = 'Создать вебинар';
$this->params['breadcrumbs'][] = ['label' => 'Вебинары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="webinar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
