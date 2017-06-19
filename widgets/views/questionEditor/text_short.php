<?php $id = uniqid('qe_text_short') ?>

<div id="<?= $id ?>" class="select-one question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
        <div class="row">
            <div class="col-md-12">
                <br>
                <a class="btn btn-default add">Добавить вариант</a>
            </div>
        </div>
        <label>Ответ:</label>
        <input type="text" class="form-control answer">
    </div>
    <div class="template item-template item row">
        <div class="col-xs-10 col-md-11"><input type="text" class="form-control"></div>
        <div class="col-xs-2 col-md-1"><a class="btn btn-danger btn-xs remove pull-left">Удалить</a></div>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionEditorTextShort();
    });
</script>
