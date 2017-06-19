<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property integer $id
 * @property integer $course_id
 * @property string $name
 * @property string $description
 * @property integer $position
 *
 * @property Challenge[] $challenges
 * @property QuestionHasSubject[] $questionHasSubjects
 * @property Question[] $questions
 * @property Course $course
 */
class Subject extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id'], 'required'],
            [['course_id', 'position'], 'integer'],
            [['name', 'description'], 'string'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'course_id' => 'Course ID',
            'name' => 'Name',
            'description' => 'Description',
            'position' => 'Position',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['subject_id' => 'id'])->inverseOf('subject');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasSubjects()
    {
        return $this->hasMany(QuestionHasSubject::className(), ['subject_id' => 'id'])->inverseOf('subject');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])->viaTable('question_has_subject', ['subject_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id'])->inverseOf('subjects');
    }
}
