<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeGeneration extends \app\models\ar\ChallengeGeneration
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => Yii::t('challenge', 'Challenge'),
            'question_type_id' => Yii::t('questionType', 'Question Type'),
            'question_count' => Yii::t('challengeGeneration', 'Question Count'),
        ];
    }
}
