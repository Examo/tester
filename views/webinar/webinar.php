<?php

/* @var $this yii\web\View */
/* @var $webinarID int*/

use app\widgets\WebinarChallengesWidget;

$this->title = 'Вебинар';
?>

<center><img src="/i/badge.png" width="200" /></center>

<?= WebinarChallengesWidget::widget(['webinarId' => $webinarID]); ?>