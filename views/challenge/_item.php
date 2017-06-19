<div class="panel panel-default">
    <div class="panel-body">
        <p><?= $challenge->name ?></p>

        <div class="progress">
            <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                <span class="sr-only">20% Complete</span>
            </div>
        </div>

        <div class="pull-left">
            <?= $challenge->description ?>
        </div>

        <div class="pull-right">
            <a href="<?= \yii\helpers\Url::to(['challenge/start', 'id' => $challenge->id]) ?>" class="btn btn-default">Начать</a>
        </div>
    </div>
</div>