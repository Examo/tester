<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Вебинар';

?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($data):?>
    <p>
        <strong><?= $data['webinar_description']; ?></strong><br>
        Неделя в курсе: <strong><?= $data['webinar_week']; ?></strong><br>
        Начало: <?= $data['webinar_start']; ?><br>
        Окончание: <?= $data['webinar_end']; ?><br>
        Ссылка на YouTube для встраивания в страницу: <?= $data['webinar_link']; ?><br>
        Курс <strong><?= $data['course_name']; ?></strong>,
        <?= $data['isSubscribed'] ? 'подписка на курс оформлена!': 'не забудь подписаться на этот курс!'; ?>
    </p>
    <?php else: ?>
    <p>
        Такого вебинара пока что нет!
    </p>
    <?php endif; ?>

</div>
