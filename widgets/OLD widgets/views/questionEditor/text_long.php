<?php $id = uniqid('qe_text_long') ?>

<div id="<?= $id ?>" class="select-one question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <label>Минимальное кол-во символов:</label>
        <input type="text" class="form-control">
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionEditorTextLong();
    });
</script>
