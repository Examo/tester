<?php $id = uniqid('qse_text_long') ?>

<div id="<?= $id ?>" class="select-one question-settings-editor-extension">
    <div class="content"></div>

    <div class="template content-template row">
        <div class="сol-xs-12 col-md-12">
            <textarea class="form-control block-content"></textarea>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#<?= $id ?>').questionSettingsEditorTextLong();
    });
</script>
