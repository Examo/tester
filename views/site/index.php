<?php
/* @var $this yii\web\View */
use app\widgets\RatingWidget;

$this->title = \Yii::$app->name . ' — центр онлайн-обучения, кошка-репетитор';
?>

<div class="site-index">

    <div class="text-center"><h1>Учишься в 10-м или 11-м классе и скоро сдаёшь ЕГЭ по русскому языку?</h1>
        <p class="lead"><strong>Значит, ты на верном пути!</strong>
        <br><center><img src="/i/biglogo-landing.png" width="300" /></center></p>

        <p class="lead"><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['user/register']) ?>">Регистрация в Экзамо</a></p>
    </div>
    <div class="body-content">
        <div class="row">
            <div class="col-lg-4 text-justify">
                <h2><strong>Зачем всё это надо?</strong></h2>
                <center><img src="/i/catlanding1.png" /></center>
                <p>Ты сможешь выполнять тесты для ЕГЭ по русскому языку на все нужные и ненужные темы, сможешь выбирать свои "любимые" темы, чтобы их проработать.</p>
				<p>Сможешь тестироваться и на компьютере, и на планшете, и на мобильном телефоне.</p>
            </div>
            <div class="col-lg-4 text-justify">
                <h2><strong>Почему именно Экзамо?</strong></h2>
                <center><img src="/i/catlanding2.png" /></center>
                <p>У нас есть кошка, которая хочет кушать, играть, делать уборку и учиться, — и всё это с помощью тестов для ЕГЭ по русскому языку.</p>
                <p>Заведи кошку — и ты захочешь её постоянно кормить, захочешь делать с ней уборку — с помощью разных тестов!</p>
				<p>Ты сможешь постоянно заходить в Экзамо и выполнять маленькие тестики для ЕГЭ по русскому — на 5-10 минут, будешь каждый день понемногу тренироваться.</p>
            </div>
            <div class="col-lg-4 text-justify">
                <h2><strong>"А как же всё это попробовать?! Я хочу немедленно всё это попробовать!!1"</strong></h2>
                <center><img src="/i/catlanding3.png" /></center>
                <p>Просто зарегистрируйся и начни кормить кошку, делать с ней уборку, учиться.</p>
                <p>Это же бесплатно!</p>
				<p>Ну или попробуй первый тест без регистрации:</p>
				<p><center><a class="btn btn-success" href="/challenge/progress?id=1">Начать тест без регистрации</a></center></p>
            </div>
        </div>

    </div>
</div>

<?php // RatingWidget::widget(); ?>

<?php //$isGuest = Yii::$app->user->id; ?>
<?php //\yii\helpers\VarDumper::dump($isGuest, 10, true); ?>
