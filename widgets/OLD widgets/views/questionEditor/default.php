<?php

use Yii\helpers\Json;

$id = uniqid('qe');

?>

<div id="<?= $id ?>" class="question-editor well">
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
        $('#<?= $id ?>').questionEditor({
            input: '#input-<?= $id ?>',
            switcher: "<?= $switcher ?>",
            types: <?= Json::encode($types) ?>,
        });
    });
</script>
