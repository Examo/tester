<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeSettings extends \app\models\ar\ChallengeSettings
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => Yii::t('challenge', 'Challenge'),
            'immediate_result' => Yii::t('challenge', 'Immediate Result'),
            'retries_enabled' => Yii::t('challenge', 'Retries Enabled'),
            'registration_required' => Yii::t('challenge', 'Registration Required'),
            'subscription_required' => Yii::t('challenge', 'Subscription Required'),
            'start_time' => Yii::t('challenge', 'Start Time'),
            'finish_time' => Yii::t('challenge', 'Finish Time'),
            'limit_time' => Yii::t('challenge', 'Limit Time'),
            'limit_stop' => Yii::t('challenge', 'Limit Stop'),
            'autostart' => Yii::t('challenge', 'Autostart'),
        ];
    }
}
