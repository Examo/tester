<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ChallengeType */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('challengeType', 'Challenge Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challengeType', 'Challenge Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
