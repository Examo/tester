<?php
namespace app\widgets;
use app\models\ar\LearnObject;
use app\models\ar\ScaleLearn;
use app\models\Attempt;
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
        $heightScaleValue = 0;
        $allDaysFeed = [];
        $allDaysClean = [];
        $allDays = [];
        $allEvents = [];
        $match = [];
        $data = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            if ($events != []) {
                $allEvents[$course->id] = $events;
            }
        }

        if ($allEvents) {

            // цикл с разбором всех событий
            foreach ($allEvents as $keyEvent => $event) {

                // цикл с перебором всех событий конкретного курса и выбором события "Начало"
                for ($i = 0; $i < count($event); $i++) {
                    // если у события курса название "Начало", то...
                    if ($event[$i]->title == 'Начало') {
                        // получим модель курса
                        $course = Course::find()->where(['id' => $event[$i]->course_id])->one();
                        // получим время начала курса
                        $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event[$i]->start);
                        // узнаём текущее время и переводим его в простое число
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        // получаем изменение времени с момента начала курса до текущего момента
                        $timeAfterCourseStart = $time - $courseStartTime;
                        // если курс ещё не начался
                        if ($timeAfterCourseStart < 0) {
                            $timeAfterCourseStart /= 60;
                            //print 'Курс ' . $course->name . ' ещё не начался!<br> До начала курса осталось ' . $timeAfterCourseStart . ' секунд.<br>';
                        } // если курс уже начался
                        else {
                            //print 'Курс ' . $course->name . ' уже начался!<br> С момента начала курса прошло ' . $timeAfterCourseStart . ' секунд.<br>';
                            $weekTime = 604800;
                            $week = ceil($timeAfterCourseStart / $weekTime);
                            //print 'Идёт ' . $week . '-я неделя курса<br>';

                            for ($o = 1; $o <= $week; $o++) {
                                if (ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                    $scale = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                    foreach ($days as $day) {
                                        $oneOfDays = json_decode($scale->$day, true);
                                        $allDaysFeed[$keyEvent][$o][] = $oneOfDays['feed'];
                                        $allDaysClean[$keyEvent][$o][] = $oneOfDays['clean'];
                                        $allDays[$keyEvent][$o]['feed'][] = $oneOfDays['feed'];
                                        $allDays[$keyEvent][$o]['clean'][] = $oneOfDays['clean'];
                                    }
                                } else {
                                    $learn = new ScaleLearn();
                                    $learn->user_id = Yii::$app->user->id;
                                    $learn->course_id = $course->id;
                                    $learn->week_id = $o;
                                    foreach ($days as $key => $day) {
                                        $learn->$day = json_encode(['feed' => 0, 'clean' => 0]);
                                    }
                                    $learn->save();
                                }
                            }
                        }
                    }
                }
            }

            //\yii\helpers\VarDumper::dump($weekTests, 10, true);
            $result = [];
            $weekResult = 0;
            foreach ($allDaysFeed as $courseFeed => $weekFeed) {
                foreach ($allDaysClean as $courseClean => $weekClean) {
                    if ($courseFeed == $courseClean) {
                        //print $courseClean;
                        foreach ($weekFeed as $keyFeed => $valueFeed) {
                            foreach ($weekClean as $keyClean => $valueClean) {
                                if ($keyFeed == $keyClean) {
                                    //$result += $valueFeed + $valueClean;
                                    //print $keyClean;
                                    $weekResult = 0;
                                    foreach ($valueFeed as $lastKeyFeed => $lastValueFeed) {
                                        foreach ($valueClean as $lastKeyClean => $lastValueClean) {
                                            if ($lastKeyFeed == $lastKeyClean) {
                                                //print $lastValueFeed;
                                                //print $lastValueClean;
                                                $weekResult += $lastValueFeed + $lastValueClean;
                                                // print '<br>';
                                            }
                                        }
                                    }
                                }
                            }
                            $result[$courseFeed][$keyFeed] = $weekResult;
                        }
                    }
                }
            }

            print '<br><br><br><br><br>';
            // \yii\helpers\VarDumper::dump($allDays, 10, true);

            $dayName = [0 => 'monday', 1 => 'tuesday', 2 => 'wednesday', 3 => 'thursday', 4 => 'friday', 5 => 'saturday', 6 => 'sunday'];

            $mustDo = [];
            foreach ($allDays as $course => $weeks) {
                foreach ($weeks as $weekKey => $weekArray) {
                    //print count($weeks);
                    foreach ($weekArray as $element => $week) {
                        foreach ($week as $day => $value) {
                            if ($value == 0 && $weekKey != count($weeks)) {
                                //print 'Получить общий тест ' . $element . ' за неделю №' . $weekKey . '<br>';
                                // набивать массив и выводить в конце
                                $mustDo[$course][$weekKey] = true;
                                break;
                            }
                            if ($value == 0 && $weekKey == count($weeks)) {
                                //print 'Не заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                                //print 'в неделе '. $weekKey . '<br>';
                            }
                            if ($value != 0 && $weekKey == count($weeks)) {
                                //print 'Заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                                //print 'в неделе ' . $weekKey . '<br>';
                            }

                        }
                    }
                }
            }

            $lastResult = [];
            $lastWeeks = [];
            //$allWeeks = [];
            foreach ($result as $course => $week) {
                foreach ($week as $weekKey => $day) {
                    if (isset($lastResult[$weekKey])) {
                        $lastResult[$weekKey] += $day;
                    } else {
                        $lastResult[$weekKey] = $day;
                    }
                }
                $lastWeeks[$course]['week'] = count($week);
                $lastWeeks[$course]['result'] = $week[count($week)];
            }


            $lastValue = 0;
            foreach ($lastWeeks as $course => $lastWeeksValue) {
                $lastValue += $lastWeeksValue['result'];
                //\yii\helpers\VarDumper::dump($course, 10, true);
            }

            $heightScaleValue = 100 - $lastValue;

                if ($lastValue <= 10) {
                    $backgroundColor = 'red';
                } elseif ($lastValue < 100 && $lastValue > 10) {
            $backgroundColor = 'green';
                } elseif ($lastValue >= 100) {
                    $backgroundColor = 'blue';
                }



        echo '<a href="/learn">' . '<div><p style="margin: 5px">Учёба</p></div></a>';
//\yii\helpers\VarDumper::dump(count($lastWeeks), 10, true);
        foreach ($lastResult as $weekKey => $value) {
            // общее количество обязательных заданий
            $assignmentValue = 7 * 2 * count($lastWeeks);

            // стоимость в процентах одного задания
             $assignmentValueCost = 100 / $assignmentValue;
            //\yii\helpers\VarDumper::dump($assignmentValueCost, 10, true);
            // //$value *= 25;
            $value *= $assignmentValueCost;
            //$heightScaleValue = 100 - $assignmentValueCost;
            $heightScaleValue = 100 - $value;

            if ($value <= 10) {
                $backgroundColor = 'red';
            } elseif ($value < 100 && $value > 10) {
                $backgroundColor = 'green';
            } elseif ($value >= 100) {
                $backgroundColor = 'blue';
            }

            echo '<a href="/learn" id="learn-widget">' .
                '<div class="bar-wrapper-learn"><p style="font-size:9px"><strong>' . $weekKey . '<br>' . ceil($value) . '%</strong></p>' .
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

        //return $this->food;
    }
}