<?php
/**
 * @var \app\models\Course $course
 * @var \yii\web\View $this
 *
 */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <label>Курс:</label>
        <strong><?= $course->name ?></strong>
    </div>
    <div class="panel-body">
        <p><?= $course->description ?></p>
        <?php if( $course->isSubscribed(Yii::$app->user->id) ): ?>
        <div class="pull-right">
                <p><a href="<?= \yii\helpers\Url::to(['subscription/unsubscribe', 'id' => $course->id]) ?>" class="btn btn-default">Отписаться и не получать новые тесты по курсу</a></p>
        </div>
            <?php else: ?>
        <div class="pull-left">
                <p><a href="<?= \yii\helpers\Url::to(['subscription/subscribe', 'id' => $course->id]) ?>" class="btn btn-primary">Подписаться и получать новые тесты</a></p>
        </div>
        <?php endif; ?>

        <br>

        <table class="table table-striped table-hover">
            <tr>
                <th class="col-md-5 text-left">Тест</th>
                <th class="col-md-3 text-center">Попытки</th>
                <th class="col-md-3 text-center">Пройти тест</th>
                <th class="col-md-3 text-center">Крайняя оценка</th>
            </tr>
            <?php foreach( $course->getChallenges()->all() as $challenge ): ?>
                <tr>
                    <td><?= $challenge->name ?></td>
                    <td class="text-center"><?= $challenge->getAttemptsCount(Yii::$app->user->id) ?></td>
                    <td class="text-center">
                        <a href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $challenge->id])?>" class="btn btn-xs btn-success">Пройти тест</a>
                    </td>
                    <td class="text-center">
                        <?php if ($challenge->getMarks(Yii::$app->user->id, $challenge->id)):?>
                        <?php foreach( $challenge->getMarks(Yii::$app->user->id, $challenge->id) as $markContainer):?>
                        <?php endforeach;?>
                            <strong><?= $markContainer->mark?></strong>
                        <?php endif;?>
                        <?php if (!($challenge->getMarks(Yii::$app->user->id, $challenge->id))):?>
                            <?= Yii::t('challenge', 'Nothing was found') ?>
                        <?php endif;?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>