<?php

use yii\helpers\Json;

$id = uniqid('ae');

?>

<div id="<?= $id ?>" class="answer-editor">
    <?php foreach ($types as $sysname): ?>
        <?= $this->render($sysname, [
                'countThreeQuestions' => $countThreeQuestions
            ])
        ?>
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
            comment: <?php if (json_decode($comment)) { ?> $.parseJSON('<?= addslashes($comment) ?>') <?php } else { ?> '<?= $comment ? str_replace(array("\r\n", "\r", "\n"), '',  \yii\helpers\Html::encode($comment)) : '' ?>' <?php } ?>
        });
    });
</script>
