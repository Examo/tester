<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class QuestionHasChallengeType extends \app\models\ar\QuestionHasChallengeType
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => Yii::t('Question', 'Question'),
            'challenge_type_id' => Yii::t('challengeType', 'Challenge Type'),
        ];
    }
}
