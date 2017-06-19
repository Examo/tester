<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class QuestionHasCourse extends \app\models\ar\QuestionHasCourse
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => Yii::t('question', 'Question'),
            'course_id' => Yii::t('course', 'Course'),
        ];
    }
}
