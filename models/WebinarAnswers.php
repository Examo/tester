<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "webinar_answers".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $webinar_exercise_id
 * @property integer $challenge_id
 * @property string $answers
 * @property string $hints
 * @property string $result
 * @property integer $all_user_points
 * @property integer $all_points
 * @property integer $mark
 * @property integer $time
 */
class WebinarAnswers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'webinar_answers';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'webinar_exercise_id', 'challenge_id', 'answers', 'hints', 'result', 'all_user_points', 'all_points', 'mark', 'time'], 'required'],
            [['user_id', 'webinar_exercise_id', 'challenge_id', 'all_user_points', 'all_points', 'mark', 'time'], 'integer'],
            [['answers', 'hints', 'result'], 'string'],
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
            'webinar_exercise_id' => 'Webinar Exercise ID',
            'challenge_id' => 'Challenge ID',
            'answers' => 'Answers',
            'hints' => 'Hints',
            'result' => 'Result',
            'all_user_points' => 'All User Points',
            'all_points' => 'All Points',
            'mark' => 'Mark',
            'time' => 'Time',
        ];
    }
}
