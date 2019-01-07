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
 * @property integer $question_id
 * @property string $answer_correctness
 * @property string $answer_indicator
 *
 * @property User $user
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
            [['user_id', 'webinar_exercise_id', 'challenge_id', 'question_id', 'answer_correctness', 'answer_indicator'], 'required'],
            [['user_id', 'webinar_exercise_id', 'challenge_id', 'question_id'], 'integer'],
            [['answer_correctness', 'answer_indicator'], 'string'],
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
            'webinar_exercise_id' => 'Webinar Exercise ID',
            'challenge_id' => 'Challenge ID',
            'question_id' => 'Question ID',
            'answer_correctness' => 'Answer Correctness',
            'answer_indicator' => 'Answer Indicator',
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
