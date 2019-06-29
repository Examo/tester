<?php

use yii\helpers\Json;

$id = uniqid('ae');

?>

<div id="<?= $id ?>" class="answer-editor">
    <?php foreach ($types as $sysname): ?>
        <?= $this->render($sysname) ?>
    <?php endforeach; ?>
</div>

<input id="input-<?= $id ?>"
       type="hidden"
       name="<?= $name ?>"
/>
<script>
    $(function () {
        $('#<?= $id ?>').answerEditor({
            input: '#input-<?= $id ?>',
            types: <?= Json::encode($types) ?>,
            type: '<?= $type ?>',
            data: <?= Json::encode($data) ?>,
            current: '<?= $current ?>',
            answer: '<?= $answer ?>',
            immediate_result: '<?= $immediate_result ?>',
            with_shuffle: <?= (int)$with_shuffle ?>,
            comment: <?php if (json_decode($comment)) { ?> JSON.parse('<?= $comment ?>') <?php } else { ?> '<?= $comment ?? '' ?>' <?php } ?>
        });
    });
</script>
