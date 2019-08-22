<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "answer".
 *
 * @property integer $id
 * @property integer $attempt_id
 * @property integer $question_id
 * @property string $data
 * @property integer $correct
 * @property integer $hint
 *
 * @property Attempt $attempt
 * @property Question $question
 */
class Answer extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'answer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attempt_id', 'question_id'], 'required'],
            [['attempt_id', 'question_id', 'correct', 'hint'], 'integer'],
            [['data'], 'string'],
            [['result'], 'safe'],
            [['attempt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Attempt::className(), 'targetAttribute' => ['attempt_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => Question::className(), 'targetAttribute' => ['question_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attempt_id' => 'Attempt ID',
            'question_id' => 'Question ID',
            'data' => 'Data',
            'correct' => 'Correct',
            'hint' => 'Hint',
            'result' => 'Result'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttempt()
    {
        return $this->hasOne(Attempt::className(), ['id' => 'attempt_id'])->inverseOf('answers');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::className(), ['id' => 'question_id'])->inverseOf('answers');
    }
}
