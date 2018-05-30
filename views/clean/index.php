<?php
use app\widgets\CleanWidget;
use app\widgets\FoodWidget;
use app\widgets\LearnWidget;

$this->title = Yii::t('app', 'Clean');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Уборка
        </h4>
    </div>

    <center><img src="/i/bath.jpg" width="600" height="auto" /></center>

    <div class="learning">

    </div>

    <div class="row">
        <div class="exercise-wrapper">
            <form class="exercise-block">
                <div class="button-wrapper">
                    <div>
                        <center>

                            <?= LearnWidget::widget(); ?>

                            <?= FoodWidget::widget(); ?>

                            <?= CleanWidget::widget(); ?>

                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<div class="feeding">
<?php $classes = []; ?>
<?php $number = 1; ?>
<?php if ($newCleanChallenges):?>
    <?php foreach ($newCleanChallenges as $challenge): ?>
        <?php if ($challenge['challenge_id']):?>
                <?php $class = $challenge['challenge_clean_item'] ?>
                <?php  $all = $cleaningTests->getClass($classes, $class); ?>
                <?php $classes = $all['classes']; ?>
                <?php //$top = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['top'];?>
            <?php $realNumber = $number; ?>
            <?php if (isset($classes[$class])): ?>
                <?php if (isset($classes[$class][1])): ?>
                    <?php $realNumber = '...'; ?><br>
                <?php  endif; ?>
            <?php  endif; ?>
                <a href="/challenge/start?id=<?= $challenge['challenge_id']; ?>"><img src="/i/<?= $challenge['challenge_clean_item'] ?>.png" title="Тест по порядку: <?= $number; ?>, №<?= $challenge['challenge_id']; ?> по теме <?= $challenge['challenge_name']; ?> на <?= $cleaningTests->time; ?> минут, прибавляет <?= $cleaningTests->percent; ?> % к шкале Уборки, ID темы: <?= $challenge['subject_id']; ?>, Тема по сложности: <?= $number; ?>" class="<?= $all['currentClass']; ?> " /><span style="top:<?= $top = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['top']; ?>px; left: <?= $left = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['left']?>px;">№<?= $realNumber; ?></span></a>
                <?php if (!$challenge['challenge_clean_item']): ?>
                    Добавить элемент уборки в тест №<?= $challenge['challenge_id']; ?><br>
                <?php  endif; ?>
        <?php  endif; ?>
        <?php $number++; ?>
    <?php endforeach; ?>
<?php endif; ?>
</div>
<br><br><br><br><br><br><br><br><br><br><br>
<?php if ($newCleanChallenges):?>
<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Рейтинг сложных тем по всем моим курсам
        </h4>
    </div>
    <div class="feading">
        <div class="panel-body">
            <?php foreach ($newCleanChallenges as $key => $oneSubject): ?>
                <?php if ($oneSubject['challenge_clean_item']):?>
                    <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                        <div class="panel-heading">
                            <div class="item">
                                <div class="item-head">
                                    <a href="/challenge/start?id=<?= $oneSubject['challenge_id']; ?>"><div class="item-name primary-link">
                                        <strong>Курс: <?= $oneSubject['course_name']; ?><br></strong>
                                        Тема: <strong><?= $oneSubject['subject_name']; ?></strong><br>
                                        Место в рейтинге: <strong>№<?= $key + 1; ?></strong> (Количество баллов у темы: <strong><?= $oneSubject['subject_points']; ?></strong>)<br>
                                        Предмет для уборки:<center><img src="/i/<?= $oneSubject['challenge_clean_item'] ? $oneSubject['challenge_clean_item'] : 'no_image' ?>.png" /></center><br>
                                        Пройти тест -> Тест №<?= $oneSubject['challenge_id']; ?> по теме <strong><?= $oneSubject['subject_name']; ?></strong> на <?= $cleaningTests->time; ?> минут, прибавляет <?= $cleaningTests->percent; ?> % к шкале "Уборки"
                                    </div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                        <div class="panel-heading">
                            <div class="item">
                                <div class="item-head">
                                    <div class="item-name primary-link">
                                        <strong>Курс <?= $oneSubject['course_name']; ?><br>
                                            Тема: <strong><?= $oneSubject['subject_name']; ?></strong><br>
                                            Место в рейтинге: <strong>№<?= $key + 1; ?></strong> (Количество баллов у темы: <strong><?= $oneSubject['subject_points']; ?></strong>)<br>
                                            Нет теста :(
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif;?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Нет тестов и рейтинг пропал :(
        </h4>
    </div>
    <div class="learning">
        <div class="panel-body">
            FFFUUUU!!1<br>
            <center><img src="/i/catemoticonbad2.png" /></center>
        </div>
    </div>
</div>
<?php endif; ?>

