<?php $id = uniqid('qe_assoc_table') ?>

<div id="<?= $id ?>" class="assoc-table question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="items"></div>
        <div class="row">
            <div class="col-md-12">
                <br>
                <a class="btn btn-default add">Добавить пару</a>
            </div>
        </div>
    </div>
    <div class="template item-template item row">
        <div class="col-xs-4 col-md-4"><input type="text" class="form-control"></div>
        <div class="col-xs-3 col-md-4"><input type="text" class="form-control"></div>
        <div class="col-xs-3 col-md-3"><input type="text" class="form-control" placeholder="Комментарий"></div>
        <div class="col-xs-2 col-md-1"><a class="btn btn-danger btn-xs remove pull-left">Удалить</a></div>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionEditorAssocTable();
    });
</script>
