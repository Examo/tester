<?php $id = uniqid('qe_three_question') ?>

<div id="<?= $id ?>" class="three_question question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <label>1</label>
        <div class="col-md-12"><textarea rows="5" class="form-control question"></textarea></div>
        <label>2</label>
        <div class="col-md-12"><textarea rows="5" class="form-control question"></textarea></div>
        <label>3</label>
        <div class="col-md-12"><textarea rows="5" class="form-control question"></textarea></div>
        <label>Ответы:</label>
        <label>1</label>
        <div class="col-md-12"><input type="text" class="form-control answer"></div>
        <label>2</label>
        <div class="col-md-12"><input type="text" class="form-control answer"></div>
        <label>3</label>
        <div class="col-md-12"><input type="text" class="form-control answer"></div>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionEditorThreeQuestion();
    });
</script>
