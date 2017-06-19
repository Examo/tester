<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class CourseSubscription extends \app\models\ar\CourseSubscription
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User'),
            'course_id' => Yii::t('course', 'Course'),
        ];
    }
}
