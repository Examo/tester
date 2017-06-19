<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class QuestionHasSubject extends \app\models\ar\QuestionHasSubject
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => Yii::t('question', 'Question'),
            'subject_id' => Yii::t('subject', 'Subject'),
        ];
    }
}
