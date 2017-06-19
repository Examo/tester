<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge_generation".
 *
 * @property integer $id
 * @property integer $challenge_id
 * @property integer $question_type_id
 * @property integer $question_count
 *
 * @property Challenge $challenge
 * @property QuestionType $questionType
 */
class ChallengeGeneration extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge_generation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['challenge_id', 'question_type_id'], 'required'],
            [['challenge_id', 'question_type_id', 'question_count'], 'integer'],
            [['challenge_id'], 'exist', 'skipOnError' => true, 'targetClass' => Challenge::className(), 'targetAttribute' => ['challenge_id' => 'id']],
            [['question_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionType::className(), 'targetAttribute' => ['question_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'challenge_id' => 'Challenge ID',
            'question_type_id' => 'Question Type ID',
            'question_count' => 'Question Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenge()
    {
        return $this->hasOne(Challenge::className(), ['id' => 'challenge_id'])->inverseOf('challengeGenerations');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'question_type_id'])->inverseOf('challengeGenerations');
    }
}
