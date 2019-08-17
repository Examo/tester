<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\Menu;

/** @var dektrium\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>
<?php
// Old menu
/*
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= Html::img($user->profile->getAvatarUrl(24), [
                'class' => 'img-rounded',
                'alt'   => $user->username,
            ]) ?>
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('home', 'Home'), 'url' => ['/home/index']],
                ['label' => Yii::t('home', 'Subscriptions'), 'url' => ['/subscription/index']],
                ['label' => Yii::t('home', 'Courses'), 'url' => ['/subscription/all']],
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
                ['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible],
            ],
        ]) ?>
    </div>
</div>
   ?>

<?php /* Metronic menu */ ?>
<?= Menu::widget([
    'options' => [
        'class' => 'page-sidebar-menu',
        'data-keep-expanded' => 'false',
        'data-auto-scroll' => 'true',
        'data-slide-speed' => '1000',
    ],
    'items' => [
        ['label' => '<i class="fa fa-home"> </i><span class="title"> ' . Yii::t('home', 'Home') . '</span>', 'url' => ['/home']],
        ['label' => '<i class="fa fa-tasks"> </i><span class="title"> ' . Yii::t('home', 'Learn') . '</span>', 'url' => ['/learn']],
        ['label' => '<i class="fa fa-cutlery"> </i><span class="title"> ' . Yii::t('home', 'Feed') . '</span>', 'url' => ['/feed']],
        ['label' => '<i class="fa fa-trash"> </i><span class="title"> ' . Yii::t('home', 'Clean') . '</span>', 'url' => ['/clean']],
        ['label' => '<i class="fa fa-gamepad"> </i><span class="title"> ' . Yii::t('home', 'Gamer') . '</span>', 'url' => ['/gamer']],
        ['label' => '<i class="fa fa-book"> </i><span class="title"> ' . Yii::t('home', 'Subscriptions') . '</span>', 'url' => ['/subscription']],
        ['label' => '<i class="fa fa-sitemap"> </i><span class="title"> ' . Yii::t('home', 'Courses') . '</span>', 'url' => ['/subscription/all']],

    ],
    'encodeLabels' => false,
    'linkTemplate' => '<a href="{url}">{label}</a>',
]) ?>

