<div class="panel panel-default element-container" xmlns="http://www.w3.org/1999/html">
    <div class="panel-heading">
        <h4 class="panel-title">
             Еда
        </h4>
    </div>
    <center><img src="/i/refrigerator.png" width="600" height="auto" /></center>
    <div class="feeding">

    <?php if(!empty($feedingTests)):?>
                <a href="/challenge/start?id=1"><img src="<?= $feedingTests->getImageFeeding(0); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(0); ?>" /></a>
                <a href="/challenge/start?id=2"><img src="<?= $feedingTests->getImageFeeding(1); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(1); ?>" /></a>
                <a href="/challenge/start?id=3"><img src="<?= $feedingTests->getImageFeeding(2); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(2); ?>" /></a>
                <a href="/challenge/start?id=4"><img src="<?= $feedingTests->getImageFeeding(3); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(3); ?>" /></a>
                <a href="/challenge/start?id=5"><img src="<?= $feedingTests->getImageFeeding(4); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(4); ?>" /></a>
                <a href="/challenge/start?id=6"><img src="<?= $feedingTests->getImageFeeding(5); ?>" title="Тест <?= $feedingTests->title; ?> на <?= $feedingTests->time; ?> минут, прибавляет <?= $feedingTests->percent; ?> % к шкале" class="<?= $feedingTests->getFeedingConst(5); ?>" /></a>
    <?php endif;?>

    </div>

</div>