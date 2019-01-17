<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Course;
use app\models\Event;
use app\models\LoginForm;
use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class HomeController extends Controller
{
    public $layout = 'metronic_sidebar';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            if ( \Yii::$app->user->isGuest ) {
                $this->redirect( ['user/login'] );
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $challenges = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }

        $data = [];
        $badgeBackgroundColor = 'white';
        $badgeColor = 'grey';
        $all = [];

        $day = date("d");
        $year = date("Y");
        $month = date("n");
        setlocale(LC_ALL, 'ru_RU');
        $today = strftime("%A, %e %b.", mktime(0, 0, 0, $month, $day, $year));

        if (Course::findSubscribed(Yii::$app->user->id)->one()) {
            $time = Yii::$app->getFormatter()->asTimestamp(time());
            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {
                $events = Event::find()->where(['course_id' => $course->id])->all();
                //$regexp = "/(вебинар)([0-9]*)( )(ссылка)(\S*)( )(описание)([\S\s]*)/ui";
                $regexp = "/(вебинар в системе)([0-9]*)( )(вебинар по порядку)([0-9]*)( )(занятие)([0-9]*)( )(ссылка)(\S*)( )(описание)([\S\s]*)/ui";
                $match = [];
                if (Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one()) {
                    $begining = Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one();
                    foreach ($events as $key => $event) {
                        if (preg_match($regexp, $event->title, $match[$course->id][$key])) {
                            $courseStartTime = Yii::$app->getFormatter()->asTimestamp($begining->start);
                            $webinarStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
                            $timeAfterCourseStart = $time - $courseStartTime;
                            $timeBeforeWebinarStart = $webinarStartTime - $courseStartTime;
                            $webinarEndTime = Yii::$app->getFormatter()->asTimestamp($event->end);
                            $timeBeforeWebinarEnd = $webinarEndTime - $time;
                            $weekTime = 604800;

                            if ($time < $webinarStartTime) {
                                $data[$course->id][$key]['course_week'] = ceil($timeAfterCourseStart / $weekTime);
                                $data[$course->id][$key]['webinarWeek'] = ceil($timeBeforeWebinarStart / $weekTime);
                                $data[$course->id][$key]['course_id'] = $event->course_id;
                                $courseName = Course::find()->select('name')->where(['id' => $event->course_id])->one();
                                $data[$course->id][$key]['course_name'] = $courseName->name;
                                $data[$course->id][$key]['webinar_id'] = $match[$course->id][$key][2];
                                $data[$course->id][$key]['webinar_link'] = $match[$course->id][$key][2];
                                $data[$course->id][$key]['webinar_description'] = $match[$course->id][$key][14];
                                $data[$course->id][$key]['webinar_start'] = $event->start;
                                $data[$course->id][$key]['webinar_end'] = $event->end;
                                $data[$course->id][$key]['webinar_before_end'] = $timeBeforeWebinarEnd;
                                $data[$course->id][$key]['webinar_begining'] = 0;
                                //print 'Будет вебинар! На неделе ' . ceil($timeBeforeWebinarStart / $weekTime) . ' по курсу ' . $course->id;
                            }

                            if ($time >= $webinarStartTime && $time < $webinarEndTime) {
                                $data[$course->id][$key]['course_week'] = ceil($timeAfterCourseStart / $weekTime);
                                $data[$course->id][$key]['webinarWeek'] = ceil($timeBeforeWebinarStart / $weekTime);
                                $data[$course->id][$key]['course_id'] = $event->course_id;
                                $courseName = Course::find()->select('name')->where(['id' => $event->course_id])->one();
                                $data[$course->id][$key]['course_name'] = $courseName->name;
                                $data[$course->id][$key]['webinar_id'] = $match[$course->id][$key][2];
                                $data[$course->id][$key]['webinar_link'] = $match[$course->id][$key][2];
                                $data[$course->id][$key]['webinar_description'] = $match[$course->id][$key][14];
                                $data[$course->id][$key]['webinar_start'] = $event->start;
                                $data[$course->id][$key]['webinar_end'] = $event->end;
                                $data[$course->id][$key]['webinar_before_end'] = $timeBeforeWebinarEnd;
                                $data[$course->id][$key]['webinar_begining'] = 1;
                                //print 'USPESHEN Начался вебинар';
                            }
                        }
                    }
                }
            }



            $currentTime = Yii::$app->getFormatter()->asTimestamp(time());

            $allWebinars = [];
            foreach ($data as $courseId => $coursesData) {

                foreach ($coursesData as $eventKey => $webinarData) {

                    $start = Yii::$app->getFormatter()->asTimestamp($webinarData['webinar_start']);
                    $waiting = $start - $currentTime;

                    $daysToWait = floor($waiting / (60 * 60 * 24));
                    $lastPartOfDayToWait = $waiting / (60 * 60 * 24) - $daysToWait;

                    $waitingBeforeDisappear = Yii::$app->getFormatter()->asTimestamp($webinarData['webinar_end']) - $currentTime;

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
                        if ($webinar['daysToWait'] == 0) {
                            $badgeBackgroundColor = '#ff8c00';
                            $badgeColor = 'white';
                        } else {
                            $badgeBackgroundColor = 'white';
                            $badgeColor = 'grey';
                        }
                    }
                }
            }
            $countEvent = count($all);

        } else {
            $countEvent = 0;
        }

        return $this->render('index', [
            'challenges' => $challenges,
            'badgeColor' => $badgeColor,
            'badgeBackgroundColor' => $badgeBackgroundColor,
            'countEvent' => $countEvent,
            'all' => $all

        ]);
    }

}
