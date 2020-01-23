<?php
namespace app\widgets;
use app\models\ar\ScaleClean;
use app\models\Attempt;
use app\models\Question;
use Yii;
use yii\base\Widget;

class CleanWidget extends Widget
{  public function init()
{
    parent::init();

    $backgroundColor = 'grey';
    $heightScaleValue = 0;
    $scaleNumeration = 0;
    $timeCorrectness = 60 * 60 * 3;
    $lastCleanAttempt = Attempt::getCleanLastAttempt();
    date_default_timezone_set('Europe/Moscow');

    // если у пользователя существует хотя бы один выполненные тест для "Уборки"
    if ($lastCleanAttempt) {
        // получаем последнюю запись о прохождении теста для "Уборки"
        // получаем время окончания последнего теста для "Уборки"
        $lastCleanChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastCleanAttempt->finish_time)  - $timeCorrectness;
        // узнаём текущее время и переводим его в простое число
        $time = time();
        // получаем изменение времени с момента окончания теста до текущего момента
        $timeAfterLastCleanChallengeTest = $time - $lastCleanChallengeFinishTime;
        // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
        $roundTime = floor($timeAfterLastCleanChallengeTest / 60);
        // достаём шкалу "Уборки" текущего пользователя (если есть прохождение, то шкала тоже уже у него есть)
        $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
        // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или меньше 0

        // шкала минус время
        if ($scale->points - $roundTime <= 0) {
            $scale->points = 0;
            $roundTime = 0;
        }
        // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или больше 100
        // то на шкале будет 100%
        if ($scale->points - $roundTime >= 100) {
            $scale->points = 100;
            $roundTime = 0;
        }

        // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или меньше 10,
        // то шкала будет красного цвета
        if ($scale->points - $roundTime <= 10) {
            $backgroundColor = 'red';
        }
        if ($scale->points - $roundTime >= 100){
            $backgroundColor = 'blue';
        }
        if ($scale->points - $roundTime > 10 && $scale->points - $roundTime < 100) {
            $backgroundColor = 'green';
        }

        // значение столбика шкалы в "высоту"
        $heightScaleValue = $scale->points - $roundTime;
        // проценты на самой шкале в цифрах
        $scaleNumeration = $scale->points - $roundTime;

    }
    // если не существует записи в таблице шкалы "Уборки" для данного пользователя,
    // то создаём её с нулевыми значениями
    if (!ScaleClean::findOne(['user_id' => Yii::$app->user->id])) {
        $scale = new ScaleClean();
        $scale->user_id = Yii::$app->user->id;
        $scale->last_time = date("Y-m-d H:i:s");
        $scale->points = 0;
        $scale->step = 0;
        $scale->save();
    }

    if ($heightScaleValue <= 0){
        $heightScaleValue = 0;
        $scaleNumeration = 0;
    }

    if ($heightScaleValue >= 100){
        $heightScaleValue = 100;
        $scaleNumeration = 100;
    }

    $heightScaleValue = 100 - $heightScaleValue;

    echo '<a href="/clean" id="clean-widget">' .
        '<div class="bar-wrapper"><p>Уборка</p>' .
        '<div class="feeding-progress-bar-block" style=" background-color:' . $backgroundColor .'">' .
        '<div class="feeding-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"><center><p><b>' . $scaleNumeration . '%</b></p></center></div>' .
        '</div>' .
        '</div></a>';

}

    public function run(){
        $script = <<< JS
            var updateCleanWidget = setTimeout(function rqst() {
                $('#clean-widget').load('/clean/widget #clean-widget');
                updateCleanWidget = setTimeout(rqst, 100000);
            }, 100000);
JS;
        $this->view->registerJs($script, \yii\web\View::POS_READY);
        //return $this->clean;
    }

}
