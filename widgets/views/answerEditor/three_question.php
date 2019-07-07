<?php
/**
 * @var int $countThreeQuestions
 */
?>
<?php $id = uniqid('ae_three_question') ?>

<div id="<?= $id ?>" class="three-question answer-editor-extension">
    <ol type="1" start="<?= ($countThreeQuestions * 3) + 1 ?>"><div class="content"></div></ol>
    <div class="template content-template">
        <ul class="items"></ul>
        <input type="text" class="form-control" placeholder="Ответ 1">
        <input type="text" class="form-control" placeholder="Ответ 2">
        <input type="text" class="form-control" placeholder="Ответ 3">
    </div>
    <div class="template item-template item row">
        <li class="col-xs-12 col-md-12">
            <span class="text"></span>
        </li>
    </div>
</div>

<script>
    $(function () {
        $('#<?= $id ?>').answerEditorThreeQuestion();
    });
</script>
