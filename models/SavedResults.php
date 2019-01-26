<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "saved_results".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_id
 * @property integer $exercise_id
 * @property integer $challenge_id
 * @property string $answers
 * @property string $hints
 * @property string $result
 * @property string $points
 * @property integer $all_user_points
 * @property integer $all_points
 * @property integer $mark
 * @property integer $time
 * @property string $link
 *
 * @property User $user
 */
class SavedResults extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'saved_results';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id', 'exercise_id', 'challenge_id', 'answers', 'hints', 'result', 'points', 'all_user_points', 'all_points', 'mark', 'time', 'link'], 'required'],
            [['user_id', 'course_id', 'exercise_id', 'challenge_id', 'all_user_points', 'all_points', 'mark', 'time'], 'integer'],
            [['answers', 'hints', 'result', 'points', 'link'], 'string'],
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
            'exercise_id' => 'Exercise ID',
            'challenge_id' => 'Challenge ID',
            'answers' => 'Answers',
            'hints' => 'Hints',
            'result' => 'Result',
            'points' => 'Points',
            'all_user_points' => 'All User Points',
            'all_points' => 'All Points',
            'mark' => 'Mark',
            'time' => 'Time',
            'link' => 'Link',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
