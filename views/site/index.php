<?php
/* @var $this yii\web\View */
$this->title = \Yii::$app->name;
?>

<div class="site-index">

    <div class="text-center"><h1>Учишься в 11-м классе и скоро сдаёшь ЕГЭ по русскому языку?</h1>
        <p class="lead"><strong>Значит, ты на верном пути!</strong></p>
        <p class="lead"><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['user/register']) ?>">Регистрация в Экзамо</a></p>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4 text-justify">
                <h2>Зачем всё это тебе надо?</h2>
                <center><img src="/i/catlanding1.png" /></center>
                <p>Ты сможешь выполнять тесты для ЕГЭ по русскому языку на все нужные и ненужные темы, сможешь выбрать свои "любимые" темы, чтобы их проработать.</p>
				<p>Сможешь тестироваться и на компьютере, и на планшете, и на мобильном телефоне.</p>
                <p>И всё это, разумеется, бесплатно!</p>
            </div>
            <div class="col-lg-4 text-justify">
                <h2>Почему именно в Экзамо?</h2>
                <center><img src="/i/catlanding2.png" /></center>
                <p>У нас есть кошка, которая хочет кушать, играть, делать уборку и учиться, — и всё это с помощью тестов для ЕГЭ по русскому языку.</p>
                <p>Заведи себе такую кошку — и ты уже не сможешь её не кормить постоянно, не сможешь с ней не играть, не сможешь не делать уборку, — тебе же совесть не позволит, ага!</p>
				<p>Поэтому ты будешь постоянно заходить в Экзамо и выполнять маленькие тестики для ЕГЭ по русскому на 5-10 минут, будешь каждый день понемногу тренироваться.</p>
            </div>
            <div class="col-lg-4 text-justify">
                <h2><strong>"А как же это всё попробовать?! Я хочу немедленно всё это попробовать!!1"</strong></h2>
                <center><img src="/i/catlanding3.png" /></center>
                <p>Так зарегистрируйся и начни кормить кошку, играть с ней, делать с ней уборку и учиться.</p>
                <p>Бесплатно же!</p>
				<p>Ну или попробуй первый тест вообще без регистрации:</p>
				<p><center><a class="btn btn-success" href="/challenge/progress?id=1">Начать пробный тест</a></center></p>
            </div>
        </div>

    </div>
</div>

<?php $isGuest = Yii::$app->user->id; ?>
<?php \yii\helpers\VarDumper::dump($isGuest, 10, true); ?>
