<?php $id = uniqid('ae_text_short') ?>

<div id="<?= $id ?>" class="text-short answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <ul class="items"></ul>
        <input type="text" class="form-control" placeholder="Ответ">
    </div>
    <div class="template item-template item row">
        <li class="col-xs-12 col-md-12">
            <span class="text"></span>
        </li>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorTextShort();
    });
</script>
