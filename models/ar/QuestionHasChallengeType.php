<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "question_has_challenge_type".
 *
 * @property integer $question_id
 * @property integer $challenge_type_id
 *
 * @property ChallengeType $challengeType
 * @property Question $question
 */
class QuestionHasChallengeType extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_has_challenge_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_id', 'challenge_type_id'], 'required'],
            [['question_id', 'challenge_type_id'], 'integer'],
            [['challenge_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChallengeType::className(), 'targetAttribute' => ['challenge_type_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_id' => 'Question ID',
            'challenge_type_id' => 'Challenge Type ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeType()
    {
        return $this->hasOne(ChallengeType::className(), ['id' => 'challenge_type_id'])->inverseOf('questionHasChallengeTypes');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id'])->inverseOf('questionHasChallengeTypes');
    }
}
