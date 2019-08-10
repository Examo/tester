<?php

namespace app\helpers;

use app\models\Event;
use Yii;

/**
 * Prepares question to output to client
 * @package app\helpers
 */
class WebinarStats
{

    public static function getWebinarStart($course_id, $event)
    {
        $timeCorrectness = 60 * 60 * 3;
        $begining = Event::find()->where(['course_id' => $course_id])->andWhere(['title' => 'Начало'])->one();
        $courseStartTime = Yii::$app->getFormatter()->asTimestamp($begining->start) - $timeCorrectness;
        $webinarStartTime = Yii::$app->getFormatter()->asTimestamp($event->start)  - $timeCorrectness;
        $timeAfterCourseStart = time() - $courseStartTime;
        $timeBeforeWebinarStart = $webinarStartTime - $courseStartTime;
        $webinarEndTime = Yii::$app->getFormatter()->asTimestamp($event->end) - $timeCorrectness;
        $timeBeforeWebinarEnd = $webinarEndTime - time();
        $weekTime = 604800;
        $data['courseStartTime'] = $courseStartTime;
        $data['webinarStartTime'] = $webinarStartTime;
        $data['timeBeforeWebinarStart'] = $timeBeforeWebinarStart;
        $data['timeBeforeWebinarEnd'] = $timeBeforeWebinarEnd;
        $data['timeAfterCourseStart'] = $timeAfterCourseStart;

        return $data;
    }
}