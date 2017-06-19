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
        'data-slide-speed' => '200',

    ],
    'items' => [
        ['label' => Yii::t('home', 'Home'), 'url' => ['/home/index']],
        ['label' => Yii::t('home', 'Learning'), 'url' => ['/learning']],
        ['label' => Yii::t('home', 'Feeding'), 'url' => ['/feeding']],
        ['label' => Yii::t('home', 'Cleaning'), 'url' => ['/cleaning']],
        ['label' => Yii::t('home', 'Gaming'), 'url' => ['/gaming']],
        ['label' => Yii::t('home', 'Subscriptions'), 'url' => ['/subscription/index']],
        ['label' => Yii::t('home', 'Courses'), 'url' => ['/subscription/all']],
        ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
        ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
        ['label' => Yii::t('user', 'Networks'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible],
    ],

    'linkTemplate' => '<a href="{url}"><span class="title">{label}</span></a>',
]) ?>