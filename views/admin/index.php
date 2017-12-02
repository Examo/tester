<?php

/* @var $this yii\web\View */

$this->title = \Yii::$app->name;
?>
<div class="site-index">
    <div class="body-content">
        <hr>
        <div class="row">
            <h1>СОЗДАНИЕ</h1>
            <div class="col-lg-4">
                <h2>Предметы</h2>
                <p>Создание предметов обучения для использования в курсах, тестах и заданиях</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/discipline/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/discipline/create') ?>">Создать</a>
                </p>
            </div>
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
                <p>Создание тестов, настройка параметров их генерации на основе базы заданий, создание типов тестов, "Тесты по неделям"</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/create') ?>">Создать</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challengetype/index') ?>">Типы тестов</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/challenge/weeks') ?>">Тесты по неделям</a>
                </p>
            </div>
            </div>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <h2>Задания</h2>
                <p>Управление базой заданий</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/index') ?>">Список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/question/create') ?>">Создать</a>
                </p>
            </div>
            <div class="col-lg-4">
                <h2>Игровые элементы</h2>
                <p>Управление настройками игровых элементов обучения: "Еда", "Учёба", "Уборка"...</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/element/index') ?>">Элементы</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/elements-item/index') ?>">Предметы</a>
                </p>
            </div>
        </div>
            <hr>
            <div class="row">
                <h1>СИСТЕМА И ВСЕ ПОЛЬЗОВАТЕЛИ</h1>
                <div class="col-lg-4">
                    <h2>Система</h2>
                    <p>Системные настройки</p>
                    <p>
                        <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/role/index') ?>">Роли</a>
                        <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('rbac/permission/index') ?>">Разрешения</a>
                    </p>
                </div>
            <div class="col-lg-4">
                <h2>Пользователи</h2>
                <p>Управление пользователями, блокировка, редактирование профиля и т.п.</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/index') ?>">Общий список</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('user/admin/create') ?>">Создать пользователя</a>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/course/lecturers') ?>">Преподаватели курсов</a>
                </p>
            </div>
            </div>
                <hr>
                <div class="row">
                    <h1>СТАТИСТИКА И УСПЕВАЕМОСТЬ</h1>
                    <div class="col-lg-4">
                <h2>Статистика курсов (успеваемость)</h2>
                <p>Общая статистика работы курса, статистика выполнения конкретных тестов по курсам, личная статистика успеваемости учеников</p>
                <p>
                    <a class="btn btn-default" href="<?= yii\helpers\Url::toRoute('admin/course/stats') ?>">Список курсов для просмотра статистики</a>
                </p>
            </div>
            </div>
        </div>
    </div>
</div>
