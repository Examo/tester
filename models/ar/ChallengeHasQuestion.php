<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge_has_question".
 *
 * @property integer $challenge_id
 * @property integer $question_id
 * @property integer $position
 *
 * @property Challenge $challenge
 * @property Question $question
 */
class ChallengeHasQuestion extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_has_question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id', 'question_id'], 'required'],
            [['challenge_id', 'question_id', 'position'], 'integer'],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => 'Challenge ID',
            'question_id' => 'Question ID',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('challengeHasQuestions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id'])->inverseOf('challengeHasQuestions');
    }
}
