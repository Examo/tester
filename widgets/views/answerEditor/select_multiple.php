<?php $id = uniqid('ae_select_multiple') ?>

<div id="<?= $id ?>" class="select-multiple answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
    </div>
    <div class="template item-template item">
        <label>
            <input type="checkbox" class="pull-right">
            <span class="text"></span>
        </label>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorSelectMultiple();
    });
</script>
