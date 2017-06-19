<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "attempt".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $challenge_id
 * @property string $start_time
 * @property string $finish_time
 * @property string $mark
 *
 * @property Answer[] $answers
 * @property Challenge $challenge
 * @property User $user
 */
class Attempt extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'attempt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'challenge_id'], 'required'],
            [['user_id', 'challenge_id'], 'integer'],
            [['start_time', 'finish_time'], 'safe'],
            [['mark'], 'string', 'max' => 32],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'challenge_id' => 'Challenge ID',
            'start_time' => 'Start Time',
            'finish_time' => 'Finish Time',
            'mark' => 'Mark',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['attempt_id' => 'id'])->inverseOf('attempt');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('attempts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('attempts');
    }
}
