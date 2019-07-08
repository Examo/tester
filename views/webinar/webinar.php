<?php

/* @var $this yii\web\View */

use app\models\ChallengeHasQuestion;
use app\widgets\WebinarChallengesWidget;
use yii\helpers\Html;
use app\models\Question;

$this->title = 'Вебинар';
$webinarId = Yii::$app->request->get('id');
?>

<center><img src="/i/badge.png" width="200" /></center>

<?= WebinarChallengesWidget::widget(['webinarId' => $webinarId]); ?>