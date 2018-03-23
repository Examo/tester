<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenges_weeks".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_id
 * @property integer $week_id
 * @property string $challenges
 *
 * @property Course $course
 * @property User $user
 */
class ChallengesWeeks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenges_weeks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id', 'week_id', 'challenges'], 'required'],
            [['user_id', 'course_id', 'week_id'], 'integer'],
            [['challenges'], 'string'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
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
            'course_id' => 'Course ID',
            'week_id' => 'Week ID',
            'challenges' => 'Challenges',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
