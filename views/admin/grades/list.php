<?php
//use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        Успеваемость
    </div>
    <div class="panel-body">
        <?php if ($courseSubscriptions):?>
        <?php foreach ($courseSubscriptions as $courseSubscription): ?>
            <?php if ($courseSubscription->user_id == $user->id):?>
                ID ученика <?= $courseSubscription->user_id; ?>,
                подписан на курс <?= $courseSubscription->course_id; ?>
                <br>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>