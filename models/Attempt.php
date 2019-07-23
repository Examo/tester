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
            'points' => Yii::t('attempt', 'Points')
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getCleanLastAttempt()
    {
        return Attempt::find()
            ->innerJoinWith('challenge')
            ->where(['challenge.element_id' => 2])
            ->andWhere(['attempt.user_id' => Yii::$app->user->id])
            ->andWhere(['attempt.points' => 1])
            ->orderBy(['attempt.id' => SORT_DESC])
            ->limit(1)
            ->one();
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getFeedLastAttempt()
    {
        return Attempt::find()
            ->innerJoinWith('challenge')
            ->where(['challenge.element_id' => 1])
            ->andWhere(['attempt.user_id' => Yii::$app->user->id])
            ->andWhere(['attempt.points' => 1])
            ->orderBy(['attempt.id' => SORT_DESC])
            ->limit(1)
            ->one();
    }

    /**
     * @param $challengeId
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function getUserAttemptByChallenge($challengeId)
    {
        return Attempt::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['challenge_id' => $challengeId])
            ->one();
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('attempts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('attempts');
    }
}
