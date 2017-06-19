<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Attempt extends \app\models\ar\Attempt
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User'),
            'challenge_id' => Yii::t('challenge', 'Challenge'),
            'start_time' => Yii::t('attempt', 'Start Time'),
            'finish_time' => Yii::t('attempt', 'Finish Time'),
            'mark' => Yii::t('attempt', 'Mark'),
        ];
    }
}
