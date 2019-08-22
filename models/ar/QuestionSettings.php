<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "question_settings".
 *
 * @property integer $id
 * @property integer $type_id
 * @property string $settings
 * @property string $name
 *
 * @property QuestionType $questionType
 * @property Question[] $questions
 */
class QuestionSettings extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question_settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'required'],
            [['type_id'], 'integer'],
            [['settings'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type_id' => 'Question Type ID',
            'settings' => 'Settings',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'type_id'])->inverseOf('questionSettings');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['question_settings_id' => 'id'])->inverseOf('questionSettings');
    }
}
