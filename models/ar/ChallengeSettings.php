<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge_settings".
 *
 * @property integer $challenge_id
 * @property integer $immediate_result
 * @property integer $retries_enabled
 * @property integer $registration_required
 * @property integer $subscription_required
 * @property string $start_time
 * @property string $finish_time
 * @property integer $limit_time
 * @property integer $limit_stop
 * @property integer $autostart
 *
 * @property Challenge $challenge
 */
class ChallengeSettings extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id'], 'required'],
            [['challenge_id', 'immediate_result', 'retries_enabled', 'registration_required', 'subscription_required', 'limit_time', 'limit_stop', 'autostart'], 'integer'],
            [['start_time', 'finish_time'], 'safe'],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => 'Challenge ID',
            'immediate_result' => 'Immediate Result',
            'retries_enabled' => 'Retries Enabled',
            'registration_required' => 'Registration Required',
            'subscription_required' => 'Subscription Required',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
            'limit_time' => 'Limit Time',
            'limit_stop' => 'Limit Stop',
            'autostart' => 'Autostart',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('challengeSettings');
    }
}
