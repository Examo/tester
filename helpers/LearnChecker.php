<?php

namespace app\helpers;

use app\models\ar\ScaleLearn;
use app\models\Course;
use app\models\CourseSubscription;
use app\models\Event;
use app\models\Question;
use Yii;

class LearnChecker
{
    static function getLearnData(){

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

        $webinars = [];

        $lastData = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            // получили все курсы ученика, а дальше получаем все события в курсах
            $events = Event::find()->where(['course_id' => $course->id])->all();
            if ($events != []) {
                $allEvents[$course->id] = $events;
                $subscriptionStart = CourseSubscription::find()->one();
                $webinars[$course->id] = $subscriptionStart->getWebinarChallengesCheck($course->id);
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

            //\yii\helpers\VarDumper::dump($webinars, 10, true);

            $webinarsData = [];

            // перебираются выполненные и невыполненные тесты на вебинарах, курс => все вебинары по нему
            foreach ($webinars as $courseId => $allWebinarsData) {
                // пересмотр данных по вебинарам: неделя => все тесты на вебинаре по этой неделе
                foreach ($allWebinarsData['webinarChallenges'] as $weekKey => $challengesData) {
                    $webinarsData[$weekKey]['countDone'] = 0;
                    $webinarsData[$weekKey]['countUndone'] = 0;
                    // пересмотр массива с данными по тестам, номер теста => выполнен/невыполнен
                    foreach ($challengesData as $challengeKey => $challengeData) {
                        if ($challengeData == 0) {
                            //если не выполнен, то записывается 1 undone
                            $webinarsData[$weekKey]['undone'] = 1;
                            $webinarsData[$weekKey]['countUndone'] = $webinarsData[$weekKey]['countUndone'] + 1;
                        }
                        if ($challengeData == 1) {
                            // если выполнен, то записывается 1 в done
                            $webinarsData[$weekKey]['done'] = 1;
                            $webinarsData[$weekKey]['countDone'] = $webinarsData[$weekKey]['countDone'] + 1;
                        }
                    }
                }
            }
            //\yii\helpers\VarDumper::dump($webinarsData, 10, true);

            // перебираем все выполненные тесты для Еды и Уборки на неделю
            $result = [];
            $weekResult = 0;
            foreach ($allDaysFeed as $courseFeed => $weekFeed) {
                foreach ($allDaysClean as $courseClean => $weekClean) {
                    if ($courseFeed == $courseClean) {
                        //print $courseClean;
                        foreach ($weekFeed as $keyFeed => $valueFeed) {
                            foreach ($weekClean as $keyClean => $valueClean) {
                                if ($keyFeed == $keyClean) {
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
                            \yii\helpers\VarDumper::dump($weekResult, 10, true);
                            // если
                        ////   if (isset($webinarsData[$keyFeed]) && $weekResult != 7 * 2 * count($allDaysFeed)) {
                        //       $result[$courseFeed][$keyFeed] = $weekResult + (4.5 / count($allDaysFeed));
                        //       if ($result[$courseFeed][$keyFeed] > 7 * 2 * count($allDaysFeed)) {
                        //           $result[$courseFeed][$keyFeed] = 7 * 2 * count($allDaysFeed);
                        //       }
                        //   } else {
                        //       $result[$courseFeed][$keyFeed] = $weekResult;
                        //   }

                            //if (isset($webinarsData[$keyFeed]['undone'])){
                                $result[$courseFeed][$keyFeed] = $weekResult;
                          //  }
                          //  if (!isset($webinarsData[$keyFeed]['undone'])){
                          //      $result[$courseFeed][$keyFeed] = $weekResult;
                          //  }
                        }
                    }
                }
            }

             \yii\helpers\VarDumper::dump($allDaysFeed, 10, true);
             \yii\helpers\VarDumper::dump($allDaysClean, 10, true);
            \yii\helpers\VarDumper::dump($result, 10, true);
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

            $lastData['lastWeeks'] = $lastWeeks;
            $lastData['lastResult'] = $lastResult;
            $lastData['lastValue'] = $lastValue;
            $lastData['webinars'] = $webinars;
            $lastData['webinarsData'] = $webinarsData;

        }
      \yii\helpers\VarDumper::dump($lastData, 10, true);
        return $lastData;
    }
}
    