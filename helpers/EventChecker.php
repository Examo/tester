<?php

namespace app\helpers;

use app\models\Course;
use app\models\Event;
use app\models\Question;
use Yii;

class EventChecker
{
    static function getEventsData(){

        $data = [];
        $badgeBackgroundColor = 'white';
        $badgeColor = 'grey';
        $all = [];
        //$date = date_create('2000-01-01', timezone_open('Europe/Moscow'));
        date_default_timezone_set('Europe/Moscow');
        $timeCorrectness = 60 * 60 * 3;
        $day = date("d");
        $year = date("Y");
        $month = date("n");
        //setlocale(LC_ALL, 'ru_RU');
        //setlocale(LC_ALL, 'ru_RU.cp1251');
        setlocale(LC_ALL, 'ru_RU.UTF8');
        $today = strftime("%A, %e %b.", mktime(0, 0, 0, $month, $day, $year));
        $allWebinars = [];
        if (Course::findSubscribed(Yii::$app->user->id)->one()) {
            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                //foreach (Course::findSubscribed(Yii::$app->user->id)->all() as ) {
                $data = EventChecker::getEvents($course->id);
                //\yii\helpers\VarDumper::dump($data, 10, true);
                // }

                $currentTime = Yii::$app->getFormatter()->asTimestamp(time());


                foreach ($data as $courseId => $coursesData) {

                    foreach ($coursesData as $eventKey => $webinarData) {

                        $start = Yii::$app->getFormatter()->asTimestamp($webinarData['webinar_start'])  - $timeCorrectness;
                        $waiting = $start - $currentTime;

                        $daysToWait = floor($waiting / (60 * 60 * 24));
                        $lastPartOfDayToWait = $waiting / (60 * 60 * 24) - $daysToWait;

                        $waitingBeforeDisappear = Yii::$app->getFormatter()->asTimestamp($webinarData['webinar_end']) - $currentTime  - $timeCorrectness;

                        $beforeDisappearHours = $waitingBeforeDisappear / (60 * 60);
                        //print $beforeDisappearHours . '<br>';
                        $beforeDisappearMinutes = $beforeDisappearHours - floor($beforeDisappearHours);
                        $beforeDisappearHours = floor($beforeDisappearHours);
                        $beforeDisappearMinutes = ceil($beforeDisappearMinutes * 60);

                        $lastHoursInSeconds = $lastPartOfDayToWait * (60 * 60 * 24);
                        $lastHours = $lastHoursInSeconds / (60 * 60);
                        $lastMinutes = $lastHours - floor($lastHours);
                        $lastMinutes = ceil($lastMinutes * 60);

                        $newWebinarStart = strtotime($webinarData['webinar_start']);
                        $month = intval(date("n", $newWebinarStart));
                        $day = intval(date("j", $newWebinarStart));
                        $newDateFormat = date("m/d/y g:i A", $newWebinarStart);

                        $webinarDate = strftime("%A, %e %b", mktime(0, 0, 0, $month, $day, $year));
                        $webinarStart = date("G:i", $newWebinarStart);
                        $newWebinarEnd = strtotime($webinarData['webinar_end']);
                        $webinarEnd = date("G:i", $newWebinarEnd);

                        $allWebinars[$courseId][$eventKey]['course_week'] = $data[$courseId][$eventKey]['course_week'];
                        $allWebinars[$courseId][$eventKey]['webinar_week'] = $data[$courseId][$eventKey]['webinarWeek'];
                        $allWebinars[$courseId][$eventKey]['daysToWait'] = $daysToWait;
                        $allWebinars[$courseId][$eventKey]['lastHours'] = floor($lastHours);
                        $allWebinars[$courseId][$eventKey]['lastMinutes'] = $lastMinutes;
                        $allWebinars[$courseId][$eventKey]['course_name'] = $data[$courseId][$eventKey]['course_name'];
                        $allWebinars[$courseId][$eventKey]['webinar_id'] = $data[$courseId][$eventKey]['webinar_id'];
                        $allWebinars[$courseId][$eventKey]['webinar_link'] = $data[$courseId][$eventKey]['webinar_link'];
                        $allWebinars[$courseId][$eventKey]['webinar_description'] = $data[$courseId][$eventKey]['webinar_description'];
                        $allWebinars[$courseId][$eventKey]['webinar_start'] = $webinarStart;
                        $allWebinars[$courseId][$eventKey]['webinar_end'] = $webinarEnd;
                        $allWebinars[$courseId][$eventKey]['webinar_hours_before_end'] = $beforeDisappearHours;
                        $allWebinars[$courseId][$eventKey]['webinar_minutes_before_end'] = $beforeDisappearMinutes;
                        $allWebinars[$courseId][$eventKey]['webinar_begining'] = $data[$courseId][$eventKey]['webinar_begining'];
                    }
                }

                $all = [];

                foreach ($allWebinars as $courseId => $allWebinar) {
                    foreach ($allWebinar as $eventKey => $webinar) {
                        if ($webinar['course_week'] == $webinar['webinar_week']) {
                            //print 'Совпадение: ' . $eventKey;
                            $all[$courseId] = $webinar;
                            if ($webinar['daysToWait'] == 0 && $webinar['webinar_minutes_before_end'] > 0) {
                                $badgeBackgroundColor = '#ff8c00';
                                $badgeColor = 'white';
                            } else {
                                $badgeBackgroundColor = 'white';
                                $badgeColor = 'grey';
                            }
                        }
                    }
                }
            }
        } else {
        }

       // \yii\helpers\VarDumper::dump($all, 10, true);
        $countEvent = count($all);
        $newData['badgeColor'] = $badgeColor;
        $newData['badgeBackgroundColor'] = $badgeBackgroundColor;
        $newData['countEvent'] = $countEvent;
        $newData['today'] = $today;
        $newData['all'] = $all;

        return $newData;

    }

    /**
     * @param $course_id
     * @return array
     */
    static function getEvents($course_id) {
        $data = [];
        $weekTime = 604800;
        //$date = date_create('2000-01-01', timezone_open('Europe/Moscow'));
        date_default_timezone_set('Europe/Moscow');
        $timeCorrectness = 60 * 60 * 3;
        $time = Yii::$app->getFormatter()->asTimestamp(time());
        //$time = date_create($time, timezone_open('Europe/Moscow'));
        //\yii\helpers\VarDumper::dump($time, 10, true);
        $events = Event::find()->where(['course_id' => $course_id])->all();
        //$regexp = "/(вебинар)([0-9]*)( )(ссылка)(\S*)( )(описание)([\S\s]*)/ui";
        $regexp = "/(вебинар в системе)([0-9]*)( )(вебинар по порядку)([0-9]*)( )(занятие)([0-9]*)( )(ссылка)(\S*)( )(описание)([\S\s]*)/ui";
        $match = [];
        if (Event::find()->where(['course_id' => $course_id])->andWhere(['title' => 'Начало'])->one()) {
            foreach ($events as $key => $event) {
                if (preg_match($regexp, $event->title, $match[$course_id][$key])) {
                    $begining = Event::find()->where(['course_id' => $course_id])->andWhere(['title' => 'Начало'])->one();
                    $courseStartTime = Yii::$app->getFormatter()->asTimestamp($begining->start) - $timeCorrectness;
                    //\yii\helpers\VarDumper::dump(date("d.m.Y года в H:i:s", $courseStartTime), 10, true);
                    $webinarStartTime = Yii::$app->getFormatter()->asTimestamp($event->start)  - $timeCorrectness;
                    $timeAfterCourseStart = $time - $courseStartTime;
                    $timeBeforeWebinarStart = $webinarStartTime - $courseStartTime;
                    $webinarEndTime = Yii::$app->getFormatter()->asTimestamp($event->end) - $timeCorrectness;
                    $timeBeforeWebinarEnd = $webinarEndTime - $time;
                    
                    if ($time < $webinarStartTime) {
                        $data[$course_id][$key]['course_week'] = ceil($timeAfterCourseStart / $weekTime);
                        $data[$course_id][$key]['webinarWeek'] = ceil($timeBeforeWebinarStart / $weekTime);
                        $data[$course_id][$key]['course_id'] = $event->course_id;
                        $courseName = Course::find()->select('name')->where(['id' => $event->course_id])->one();
                        $data[$course_id][$key]['course_name'] = $courseName->name;
                        $data[$course_id][$key]['webinar_id'] = $match[$course_id][$key][2];
                        $data[$course_id][$key]['webinar_link'] = $match[$course_id][$key][2];
                        $data[$course_id][$key]['webinar_description'] = $match[$course_id][$key][14];
                        $data[$course_id][$key]['webinar_start'] = $event->start;
                        $data[$course_id][$key]['webinar_end'] = $event->end;
                        $data[$course_id][$key]['webinar_before_end'] = $timeBeforeWebinarEnd;
                        $data[$course_id][$key]['webinar_begining'] = 0;
                        //print 'Будет вебинар! На неделе ' . ceil($timeBeforeWebinarStart / $weekTime) . ' по курсу ' . $course_id;
                    }

                    if ($time >= $webinarStartTime && $time < $webinarEndTime) {
                        $data[$course_id][$key]['course_week'] = ceil($timeAfterCourseStart / $weekTime);
                        $data[$course_id][$key]['webinarWeek'] = ceil($timeBeforeWebinarStart / $weekTime);
                        $data[$course_id][$key]['course_id'] = $event->course_id;
                        $courseName = Course::find()->select('name')->where(['id' => $event->course_id])->one();
                        $data[$course_id][$key]['course_name'] = $courseName->name;
                        $data[$course_id][$key]['webinar_id'] = $match[$course_id][$key][2];
                        $data[$course_id][$key]['webinar_link'] = $match[$course_id][$key][2];
                        $data[$course_id][$key]['webinar_description'] = $match[$course_id][$key][14];
                        $data[$course_id][$key]['webinar_start'] = $event->start;
                        $data[$course_id][$key]['webinar_end'] = $event->end;
                        $data[$course_id][$key]['webinar_before_end'] = $timeBeforeWebinarEnd;
                        $data[$course_id][$key]['webinar_begining'] = 1;
                        //print 'USPESHEN Начался вебинар';
                    }
                }
            }
        }

        return $data;

    }


}





