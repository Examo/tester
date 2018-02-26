
<?php
use app\widgets\CleanWidget;
use app\widgets\FoodWidget;

$this->title = Yii::t('app', 'Clean');
$this->params['breadcrumbs'][] = $this->title;
//$foodTest = "Моя первая ноdfdfdfdfвость";
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

                            <?= FoodWidget::widget(); ?>

                            <?= CleanWidget::widget(); ?>

                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<?php //\yii\helpers\VarDumper::dump($challenges, 10, true); ?>

<?php foreach ($challenges as $challenge): ?>
<?php if ($challenge->element_id == 2): ?>
        <?php // \yii\helpers\VarDumper::dump($challenge, 10, true); ?>
        <?php endif; ?>
<?php endforeach; ?>
<div class="feeding">
<?php $classes = []; ?>

<?php //\yii\helpers\VarDumper::dump($cleaningTests, 10, true); ?>
<?php //\yii\helpers\VarDumper::dump($difficultSubjects, 10, true); ?>

<?php $rightSubjects = []; ?>
<?php $newRightSubjects = []; ?>
<?php foreach ($difficultSubjects as $difficultSubject): ?>
    <?php foreach ($allSubjects as $allSubject): ?>
        <?php if ($difficultSubject->subject_id == $allSubject->id):?>
            <!--<br>Тема номер-->
            <?php//= $difficultSubject- subject_id; ?> <?php//= $allSubject- name; ?> <!--| Количество очков:--> <?php//= $difficultSubject- points; ?>
            <?php $rightSubjects[$difficultSubject->subject_id] = $allSubject->name; ?>
            <?php $newRightSubjects[] = [
                'points' => $difficultSubject->points,
                'subject_id' => $difficultSubject->subject_id,
                'name' => $allSubject->name,
            ]; ?>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endforeach; ?>

<?php foreach ($newRightSubjects as $key => $row) {
    $subjectPoints[$key] = $row['points'];
   // $subject[$key]  = $row['subject_id'];
    //$name[$key]  = $row['name'];
}
array_multisort($subjectPoints, SORT_ASC, $newRightSubjects);
?>

    <?php $number = 1; ?>
    <?php $subjectsOfRating = []; ?>
<?php foreach ($newRightSubjects as $key => $oneSubject): ?>
    <?php if(!empty($cleaningTests)):?>
        <?php if(!empty($challenges)):?>
            <?php $subjectNumber = []; ?>
            <?php foreach ($challenges as $challenge): ?>
                <?php if(!empty($cleaningTests->getChallengeClean($challenge->id)->name)):?>
                    <?php if ($challenge->element_id == 2):?>
                        <?php if ($challenge->subject_id == $oneSubject['subject_id'] && !in_array($oneSubject['subject_id'], $subjectNumber)):?>
                            <?php $subjectNumber[] = $oneSubject['subject_id']; ?>
                            <?php $subjectsOfRating[$number]['id'] = $challenge->id; ?>
                            <?php $subjectsOfRating[$number]['subject'] = $oneSubject['subject_id']; ?>
                        <?php $class = $challenge->getChallengeClean($challenge->id)->name; ?>
                        <?php $all = $cleaningTests->getClass($classes, $class); ?>
                        <?php $classes = $all['classes']; ?>
                            <?php $top = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['top'];?>

                                <a href="/challenge/start?id=<?= $challenge->id; ?>"><img src="<?= $cleaningTests->getImageCleaning($cleaningTests->getChallengeClean($challenge->id)->name); ?>" title="Тест по порядку: <?= $number; ?>, №<?= $challenge->id; ?> по теме <?= $oneSubject['name']; ?> на <?= $cleaningTests->time; ?> минут, прибавляет <?= $cleaningTests->percent; ?> % к шкале Уборки, ID темы: <?= $oneSubject['subject_id']; ?>, Тема по сложности: <?= $number; ?>" class="<?= $all['currentClass']; ?> " /><span style="top:<?= $top = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['top']; ?>px; left: <?= $top = $cleaningTests->getTopLeftStyleNumber($all['currentClass'])['left']?>px;">№<?= $number; ?></span></a>

                        <?php $number++; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    Добавить элемент уборки в тест №<?= $challenge->id; ?><br>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif;?>
<?php endforeach; ?>

</div>
<br><br><br><br><br>
<?php //\yii\helpers\VarDumper::dump($subjectsOfRating, 10, true); ?>
<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
            Рейтинг сложных тем
        </h4>
    </div>

    <div class="learning">

        <div class="panel-body">
            <?php foreach ($newRightSubjects as $key => $oneSubject): ?>
                <?php foreach ($subjectsOfRating as $subjectOfRating): ?>
                    <?php if ($subjectOfRating['subject'] == $oneSubject['subject_id']):?>
                        <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                            <div class="panel-heading">
                                <div class="item">
                                    <div class="item-head">
                                        <div class="item-name primary-link">
                                            <strong>№<?= $key + 1; ?> тема по сложности - "<?= $oneSubject['name']; ?>" (ID в системе: <?= $oneSubject['subject_id']; ?>)
                                                <br>(Количество баллов у темы: <?= $oneSubject['points']; ?>)</strong>
                                            <br>Ссылка на тест: <a href="/challenge/start?id=<?= $subjectOfRating['id']; ?>">Тест №<?= $subjectOfRating['id']; ?> по теме <?= $oneSubject['name']; ?> на <?= $cleaningTests->time; ?> минут, прибавляет <?= $cleaningTests->percent; ?> % к шкале Уборки, ID темы: <?= $oneSubject['subject_id']; ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php //unset($newRightSubjects[$key]);?>
                        <?php break; ?>

                    <?php endif; ?>

                <?php endforeach; ?>
                <?php if ($subjectOfRating['subject'] !== $oneSubject['subject_id']):?>
                    <div class="panel panel-default" style="border: normal; border-color: #00a5bb">
                        <div class="panel-heading">
                            <div class="item">
                                <div class="item-head">
                                    <div class="item-name primary-link">
                                        <strong>№<?= $key + 1; ?> тема по сложности - "<?= $oneSubject['name']; ?>" (ID в системе: <?= $oneSubject['subject_id']; ?>)
                                            <br>(Количество баллов у темы: <?= $oneSubject['points']; ?>)</strong>
                                        <br><a href="/challenge/start?id= ">Ссылка на тест №  по теме на <?= $cleaningTests->time; ?> минут, прибавляет <?= $cleaningTests->percent; ?> % к шкале Уборки, ID темы: <?= $oneSubject['subject_id']; ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>



<?php //\yii\helpers\VarDumper::dump($cleaningTests->getTopLeftStyleNumber($all['currentClass'])['top'], 10, true); ?>