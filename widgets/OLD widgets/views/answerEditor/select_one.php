<?php $id = uniqid('ae_select_one') ?>

<div id="<?= $id ?>" class="select-one answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
    </div>
    <div class="template item-template item row">
        <label>
            <input type="radio" name="<?= $id ?>" class="pull-right">
            <span class="text"></span>
        </label>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorSelectOne();
    });
</script>
