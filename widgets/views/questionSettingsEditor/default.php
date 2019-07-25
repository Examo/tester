<?php

use Yii\helpers\Json;

/** @var array $types */
/** @var string $switcher */
/** @var string $attribute */

$id = uniqid('qse');

?>

<div id="<?= $id ?>" class="question-settings-editor well">
    <?php foreach ($types as $sysname): ?>
        <?= $this->render($sysname) ?>
    <?php endforeach; ?>
</div>

<input id="input-<?= $id ?>"
       type="hidden"
       name="<?= \yii\helpers\Html::getInputName($model, $attribute) ?>"
       value="<?= htmlentities($data) ?>"
/>

<script>
    $(function () {
        $('#<?= $id ?>').questionSettingsEditor({
            input: '#input-<?= $id ?>',
            switcher: "<?= $switcher ?>",
            types: <?= Json::encode($types) ?>,
        });
    });
</script>
