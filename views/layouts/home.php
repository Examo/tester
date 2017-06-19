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

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */

$this->title = Yii::t('home', 'Home');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?= $this->render('@dektrium/user/views/_alert', ['module' => Yii::$app->getModule('user')]) ?>

    <div class="row">
        <div class="col-md-3">
            <?= $this->render('@dektrium/user/views/settings/_menu') ?>
        </div>
        <div class="col-md-9">
            <?= $content ?>
        </div>
    </div>
<?php $this->endContent();