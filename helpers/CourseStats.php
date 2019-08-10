<?php

namespace app\helpers;

use app\models\Event;
use Yii;

/**
 * Prepares question to output to client
 * @package app\helpers
 */
class CourseStats
{

    public static function getCourseStart($course_id)
    {
        $data = [];
        $event = Event::find()->where(['course_id' => $course_id])->andWhere(['title' => 'Начало'])->one();
        //\yii\helpers\VarDumper::dump($events, 10, true);
        $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
        $time = Yii::$app->getFormatter()->asTimestamp(time());
        // получаем изменение времени с момента начала курса до текущего момента
        $timeAfterCourseStart = $time - $courseStartTime;
        $weekTime = 604800;
        $week = ceil($timeAfterCourseStart / $weekTime);
        $timeAfterCourseStart = $time - $courseStartTime;
        $data['courseStartTime'] = $courseStartTime;
        $data['week'] = $week;
        $data['timeAfterCourseStart'] = $timeAfterCourseStart;

        return $data;
    }
}