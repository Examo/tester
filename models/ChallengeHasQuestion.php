<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeHasQuestion extends \app\models\ar\ChallengeHasQuestion
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => Yii::t('challenge', 'Challenge'),
            'question_id' => Yii::t('question', 'Question'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}
