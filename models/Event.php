<?php

namespace app\models;

use Yii;

class Event extends \app\models\ar\Event
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('event', 'Title'),
            'color' => Yii::t('event', 'Color'),
            'start' => Yii::t('event', 'Start'),
            'end' => Yii::t('event', 'End'),
            'course_id' => Yii::t('course', 'Course'),
        ];
    }

}