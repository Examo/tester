<?php $id = uniqid('ae_assoc') ?>

<div id="<?= $id ?>" class="assoc answer-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="row">
            <div class="col-xs-6 col-md-6">
                <div class="options-left"></div>
            </div>
            <div class="col-xs-6 col-md-6">
                <div class="options-right"></div>
            </div>
        </div>
    </div>
    <div class="template item item-template">
        <span class="text"></span>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorAssoc();
    });
</script>
