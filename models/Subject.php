<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Subject extends \app\models\ar\Subject
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => Yii::t('course', 'Course'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}
