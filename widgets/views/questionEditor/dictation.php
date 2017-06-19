<?php $id = uniqid('qe_dictation') ?>

<div id="<?= $id ?>" class="dictation question-editor-extension">
    <div class="content"></div>
    <div class="template content-template">
        <div class="editor" contenteditable="true"></div>
    </div>
    <div class="template selection-menu-template selection-menu">
        <a class="btn btn-xs btn-success add">Добавить варианты</a>
    </div>
    <div class="template item-template item">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-xs btn-danger remove">X</button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="value"></span>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                </ul>
            </div>
            <button type="button" class="btn btn-xs btn-success add">+</button>
            <button type="button" class="btn btn-xs btn-primary comment">?</button>
        </div>
    </div>
    <li class="template option-template option">
        <span class="value"></span>
        <button href="#" class="btn btn-xs btn-danger pull-right delete">X</button>
    </li>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').questionEditorDictation();
    });
</script>
