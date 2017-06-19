<?php

/* @var $this yii\web\View */

$this->title = \Yii::$app->name;
?>
<div class="site-index">

    <div class="row">
        <div class="col-md-10">
            <!-- PLACEHOLDER!!! -->
            <div style="width: 100%; height: 20em; background: #eee url(/i/donut.png) center center no-repeat; background-size: contain; border: 1px solid #666;"></div>
        </div>
        <!--<div class="col-md-2">
            <div class="btn-group-vertical" role="group" aria-label="...">
                <a href="#" class="btn btn-default disabled active">Statistics Mode 1</a>
                <a href="#" class="btn btn-default disabled">Statistics Mode 2</a>
                <a href="#" class="btn btn-default disabled">Statistics Mode 3</a>
            </div>

        </div>-->
    </div>

    <div class="body-content">
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <h2>Курсы</h2>

                <p>Настройки курсов на основе созданных тестов</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/course/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/course/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Тесты</h2>

                <p>Настройка параметров генерации тестов на основе базы заданий</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Задания</h2>

                <p>Управление базой заданий</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/create') ?>">Создать</a>
                </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <h2>Игра</h2>

                <p>Управление настройками игровых элементов обучения</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/element/index') ?>">Элементы</a>
                    <a class="btn btn-default disabled" href="<?= yii\helpers\Url::toRoute('admin/element/index') ?>">Предметы</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Пользователи</h2>

                <p>Управление пользователями, блокировка, редактирование профиля и т.п.</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Система</h2>

                <p>Системные настройки</p>

                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/role/index') ?>">Роли</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/permission/index') ?>">Разрешения</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challengetype/index') ?>">Типы тестов</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/discipline/index') ?>">Предметы</a>
                </p>
            </div>
        </div>

    </div>
</div>
