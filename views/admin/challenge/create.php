<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Challenge */

 
?>
<div class="challenge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'food' => $food
    ]) ?>

</div>
