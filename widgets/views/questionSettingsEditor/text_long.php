<?php $id = uniqid('qse_text_long') ?>

<div id="<?= $id ?>" class="select-one question-settings-editor-extension">
    <div class="content"></div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionSettingsEditorTextLong();
    });
</script>
