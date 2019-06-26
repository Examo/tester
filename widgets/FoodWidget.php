<?php
namespace app\widgets;
use app\models\ar\ScaleFeed;
use app\models\Attempt;
use app\models\Question;
use Yii;
use yii\base\Widget;

class FoodWidget extends Widget
{
    public function init()
    {
        parent::init();

        $backgroundColor = 'grey';
        $heightScaleValue = 0;
        $scaleNumeration = 0;
        $timeCorrectness = 60 * 60 * 3;

        // если у пользователя существует хотя бы один выполненные тест для "Еды"
        if (Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one()) {
            // получаем последнюю запись о прохождении теста для "Еды"
            $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
            // получаем время окончания последнего теста для "Еды"
            $lastFeedChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttempt->finish_time) - $timeCorrectness;
            // узнаём текущее время и переводим его в простое число
            $time = Yii::$app->getFormatter()->asTimestamp(time());
            // получаем изменение времени с момента окончания теста до текущего момента
            $timeAfterLastFeedChallengeTest = $time - $lastFeedChallengeFinishTime;
            // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
            $roundTime = ceil($timeAfterLastFeedChallengeTest / 100) - 1;
            //$roundTime = 100 - $roundTime;
            //$roundTime = 100 - (0 - $roundTime);
            //$roundTime = 0 - $roundTime;
            // достаём шкалу "Еды" текущего пользователя (если есть прохождение, то шкала тоже уже у него есть)
            $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
            // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или меньше 0
            // то на шкале будет 0%
            //\yii\helpers\VarDumper::dump($scale, 10, true);
            //\yii\helpers\VarDumper::dump($roundTime, 10, true);
            //\yii\helpers\VarDumper::dump($heightScaleValue, 10, true);

            // шкала минус время

            if ($scale->points - $roundTime <= 0) {
                $scale->points = 0;
                $roundTime = 0;
                //print 'меньше или равно нулю';
            }

            // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или больше 100
            // то на шкале будет 100%
            if ($scale->points - $roundTime >= 100) {
                $scale->points = 100;
                $roundTime = 0;
                //print 'больше или равно сто';
            }

            // если от баллов на шкале отнять баллы прошедшего времени и разность будет равна или меньше 10,
            // то шкала будет красного цвета
            if ($scale->points - $roundTime <= 10) {
                $backgroundColor = 'red';
            } else {
                $backgroundColor = 'green';
            }

            // значение столбика шкалы в "высоту"
            $heightScaleValue = $scale->points - $roundTime;
            // проценты на самой шкале в цифрах
            $scaleNumeration = $scale->points - $roundTime;
            // значение столбика шкалы в "высоту"
            //$heightScaleValue = 100 - $scale->points + $roundTime;
            // проценты на самой шкале в цифрах
            //$scaleNumeration = $scale->points - $roundTime;

        }
        // если не существует записи в таблице шкалы "Еды" для данного пользователя,
        // то создаём её с нулевыми значениями
        if (!ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one()) {
            $scale = new ScaleFeed();
            $scale->user_id = Yii::$app->user->id;
            $scale->last_time = date("Y-m-d H:i:s");
            $scale->points = 0;
            $scale->step = 0;
            $scale->save();
        }

        //\yii\helpers\VarDumper::dump($heightScaleValue, 10, true);

        //$heightScaleValue = 100 - $heightScaleValue;
        $heightScaleValue = 100 - $heightScaleValue;
        //$scaleNumeration = 10;
        $backgroundColor = 'green';
        if ($heightScaleValue <= 0){
            $heightScaleValue = 100;
            $scaleNumeration = 0;
        }

        echo '<a href="/feed" id="food-widget">' .
            '<div class="bar-wrapper"><p>Еда</p>' .
            '<div class="feeding-progress-bar-block" style=" background-color:' . $backgroundColor .'">' .
            '<div class="feeding-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"><center><p><b>' . $scaleNumeration . '%</b></p></center></div>' .
            '</div>' .
            '</div></a>';

    }

    public function run(){
        $script = <<< JS
            var updateFoodWidget = setTimeout(function rqst() {
                $('#food-widget').load('/feed/widget #food-widget');
                updateFoodWidget = setTimeout(rqst, 100000);
            }, 100000);
JS;
        $this->view->registerJs($script, yii\web\View::POS_READY);
        //return $this->food;
    }
}