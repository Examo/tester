<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Course extends \app\models\ar\Course
{
    static public function findSubscribed($user)
    {
        $subscriptions = CourseSubscription::find()
            ->select('course_id')
            ->where(['user_id' => is_object($user) ? $user->id : $user])
            ->column();

        return self::find()->where(['id' => $subscriptions]);
    }

    static public function findAvailable($user)
    {
        $subscriptions = CourseSubscription::find()
            ->select('course_id')
            ->where(['user_id' => is_object($user) ? $user->id : $user])
            ->column();

        return self::find()->where(['not in', 'id', $subscriptions]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
            'subjects' => Yii::t('subject', 'Subjects'),
            'discipline_id' => Yii::t('discipline', 'Discipline'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['course_id' => 'id'])->inverseOf('course');
    }

    /**
     * @param User|int $user
     * @return \yii\db\ActiveQuery
     */
    public function getNewChallenges($user)
    {
        $challenges = $this->getChallenges()->select('id')->column();
        $attempts = Attempt::find()->where([
            'user_id' => is_object($user) ? $user->id : $user,
            'challenge_id' => $challenges
        ])->groupBy('challenge_id')->select('challenge_id')->column();

        return $this->hasMany(Challenge::className(), ['course_id' => 'id'])
            ->inverseOf('course')
            ->where(['id' => array_diff($challenges, $attempts)]);
    }

    /**
     * @param User|int $user
     * @return bool
     */
    public function canSubscribe($user)
    {
        return true;
    }

    /**
     * @param User|int $user
     * @return bool
     */
    public function isSubscribed($user)
    {
        return CourseSubscription::find()->where([
            'user_id' => is_object($user) ? $user->id : $user,
            'course_id' => $this->id
        ])->exists();
    }

    /**
     * @param User|int $user
     * @return bool
     */
    public function subscribe($user)
    {
        if (!$this->isSubscribed($user)) {
            $subscription = new CourseSubscription();
            $subscription->user_id = is_object($user) ? $user->id : $user;
            $subscription->course_id = $this->id;
            return $subscription->save();
        }

        return false;
    }

    /**
     * @param User|int $user
     * @return bool
     */
    public function unsubscribe($user)
    {
        return (bool)CourseSubscription::deleteAll([
            'user_id' => is_object($user) ? $user->id : $user,
            'course_id' => $this->id
        ]);
    }

    /**
     * @param User|int $user
     * @return int Percents
     */
    public function getProgress($user)
    {
        $challenges = $this->getChallenges()->select('id')->column();

        if (!count($challenges)) {
            return 0;
        }

        $attempts = Attempt::find()->where([
            'user_id' => is_object($user) ? $user->id : $user,
            'challenge_id' => $challenges
        ])->groupBy('challenge_id')->count();

        return round($attempts / count($challenges) * 100);
    }
}
