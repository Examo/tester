<div class="panel panel-default">
    <div class="panel-heading">
        <?= $challenge->name ?>
    </div>
    <div class="panel-body">

        <h1>Завершить тестирование</h1>

        <p>Вы уверены, что хотите досрочно завершить тестирование?</p>
        <p>Оценка за данное тестирование будет недоступна.</p>

        <div class="row">
            <div class="col-xs-6 col-md-6 text-left">
                <a href="<?= \yii\helpers\Url::toRoute(['challenge/progress', 'id' => $challenge->id]) ?>" class="btn btn-primary">Отмена</a>
            </div>
            <div class="col-xs-6 col-md-6 text-right">
                <a href="<?= \yii\helpers\Url::toRoute(['challenge/finish', 'id' => $challenge->id, 'confirm' => true]) ?>" class="btn btn-danger">Завершить</a>
            </div>
        </div>

    </div>
</div>