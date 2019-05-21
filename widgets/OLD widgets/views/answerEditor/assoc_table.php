<?php $id = uniqid('ae_assoc_table') ?>

<div id="<?= $id ?>" class="assoc-table answer-editor-extension">
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
        <div class="row">
            <div class="col-xs-12 col-md-12 text-center answers">

            </div>
        </div>
    </div>
    <div class="template item item-template">
        <span class="number"></span>
        <span class="text"></span>
    </div>
    <div class="template answer answer-template">
        <span class="option"></span>
        <span class="association">
            <input type="text" maxlength="2" />
        </span>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorAssocTable();
    });
</script>
