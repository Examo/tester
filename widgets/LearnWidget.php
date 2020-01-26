<?php
namespace app\widgets;
use app\helpers\LearnChecker;
use app\models\Course;
use app\models\Event;
use app\models\Question;
use Yii;
use yii\base\Widget;

class LearnWidget extends Widget
{
    public function init()
    {
        parent::init();

        $backgroundColor = 'grey';
        $allEvents = [];


        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            if ($events != []) {
                $allEvents[$course->id] = $events;
            }
        }

        if ($allEvents) {

            print '<br><br><br><br><br>';

            $latestData = LearnChecker::getLearnData();

        echo '<a href="/learn">' . '<div><p style="margin: 5px">Учёба</p></div></a>';

        foreach ($latestData['lastResult'] as $weekKey => $value) {
            // общее количество обязательных заданий
            // $assignmentValue = 7 * 2 * count($latestData['lastWeeks']);
            // стоимость в процентах одного задания
            //  $assignmentValueCost = 100 / $assignmentValue;
            //\yii\helpers\VarDumper::dump($assignmentValueCost, 10, true);
            // //$value *= 25;
            // $value *= $assignmentValueCost;
            //  $heightScaleValue = 100 - $value;

             $webinar = 0;
             $number = 1;
             $generalValue = 7 * 2; // 7 дней и 2 обязательных теста в каждом
             $generalScaleValue = $value;

            if (isset($latestData['webinarsData'][$weekKey]) && $latestData['webinarsData'][$weekKey]['countUndone'] == 0){
                $webinar = $generalValue;
                $generalScaleValue += $webinar;
                $number++;
            }
            if (isset($latestData['webinarsData'][$weekKey]['undone'])){
                $webinar = 0;
                $generalScaleValue += $webinar;
                $number++;
            }

            $assignmentValue = $generalValue * $number * count($latestData['lastWeeks']);
            // стоимость в процентах одного задания
            $assignmentValueCost = 100 / $assignmentValue;
            $value = $generalScaleValue * $assignmentValueCost;
            $heightScaleValue = 100 - $value;

            if ($value <= 10) {
                $backgroundColor = 'red';
                $value = strval(ceil($value)) . '%';
            } elseif ($value < 100 && $value > 10) {
                $backgroundColor = 'green';
                $value = strval(ceil($value)) . '%';
            } elseif ($value >= 100) {
                $backgroundColor = 'blue';
                $value = '&#10004';
            }

            echo '<a href="/learn" id="learn-widget">' .
                '<div class="bar-wrapper-learn"><p style="font-size:9px"><strong>' . $weekKey . '<br>' . $value . '</strong></p>' .
                '<div class="learning-progress-bar-block" style=" background-color:' . $backgroundColor . '">' .
                '<div class="learning-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"></div>' .
                '</div>' .
                '</div></a>';

        }
    } else { // если нет начала у курсов или не подписан
            echo '<a href="/learn">' . '<div><p style="margin: 5px">Учёба</p></div></a>';
            echo '<a href="/learn" id="learn-widget">' .
                '<div class="bar-wrapper-learn"><p style="font-size:9px"><strong>0<br>0%</strong></p>' .
                '<div class="learning-progress-bar-block" style=" background-color:' . $backgroundColor . '">' .
                '<div class="learning-progress-bar-fill" style="height:0%; width:100%;"></div>' .
                '</div>' .
                '</div></a>';
        }
    }

    public function run(){
    }
}