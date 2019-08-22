<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Answer extends \app\models\ar\Answer
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attempt_id' => Yii::t('attempt', 'Attempt'),
            'question_id' => Yii::t('question', 'Question'),
            'data' => Yii::t('answer', 'Data'),
            'correct' => Yii::t('answer', 'Correct'),
            'hint' => Yii::t('answer', 'Hint'),
            'result' => Yii::t('answer', 'Results'),
        ];
    }

}
