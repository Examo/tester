<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "difficult_subjects".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $subject_id
 * @property integer $points
 *
 * @property User $user
 * @property QuestionHasSubject $subject
 */
class DifficultSubjects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'difficult_subjects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'subject_id', 'points'], 'required'],
            [['user_id', 'subject_id', 'points'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionHasSubject::className(), 'targetAttribute' => ['subject_id' => 'subject_id']],
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
            'subject_id' => 'Subject ID',
            'points' => 'Points',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(QuestionHasSubject::className(), ['subject_id' => 'subject_id']);
    }
}
