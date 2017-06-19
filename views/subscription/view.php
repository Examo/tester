<?php
/**
 * @var \app\models\Course $course
 * @var \yii\web\View $this
 *
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= $course->name ?>
    </div>
    <div class="panel-body">
        <p><?= $course->description ?></p>

        <div class="">
            <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
                <a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default">Отписаться и не получать новые тесты по курсу</a>
            <?php else: ?>
                <a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-primary">Подписаться и получать новые тесты</a>
            <?php endif; ?>
        </div>

        <br>

        <table class="table table-striped table-hover">
            <tr>
                <th class="col-md-10">Тест</th>
                <th class="col-md-1">Попытки</th>
                <th class="col-md-1"></th>
            </tr>
            <?php foreach( $course->getChallenges()->all() as $challenge ): ?>
                <tr>
                    <td><?= $challenge->name ?></td>
                    <td><?= $challenge->getAttemptsCount(Yii::$app->user->id) ?></td>
                    <td>
                        <a href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $challenge->id])?>" class="btn btn-xs btn-success">Пройти тест</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>