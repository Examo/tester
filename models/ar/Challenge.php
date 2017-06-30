<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "challenge".
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $challenge_type_id
 * @property integer $element_id
 * @property integer $subject_id
 * @property integer $grade_number
 * @property string $name
 * @property string $description
 * @property integer $exercise_number
 * @property integer $exercise_challenge_number
 *
 * @property Attempt[] $attempts
 * @property Element $element
 * @property Subject $subject
 * @property ChallengeType $challengeType
 * @property Course $course
 * @property ChallengeGeneration[] $challengeGenerations
 * @property ChallengeHasQuestion[] $challengeHasQuestions
 * @property Question[] $questions
 * @property ChallengeMark[] $challengeMarks
 * @property ChallengeSettings $challengeSettings
 */
class Challenge extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'challenge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'challenge_type_id', 'element_id', 'subject_id'], 'required'],
            [['course_id', 'challenge_type_id', 'element_id', 'subject_id', 'grade_number', 'exercise_number', 'exercise_challenge_number', 'week'], 'integer'],
            [['name', 'description'], 'string'],
            [['element_id'], 'exist', 'skipOnError' => true, 'targetClass' => Element::className(), 'targetAttribute' => ['element_id' => 'id']],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::className(), 'targetAttribute' => ['subject_id' => 'id']],
            [['challenge_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChallengeType::className(), 'targetAttribute' => ['challenge_type_id' => 'id']],
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
            'challenge_type_id' => 'Challenge Type ID',
            'element_id' => 'Element ID',
            'subject_id' => 'Subject ID',
            'grade_number' => 'Grade Number',
            'name' => 'Name',
            'description' => 'Description',
            'exercise_number' => 'Exercise Number',
            'exercise_challenge_number' => 'Exercise Challenge Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttempts()
    {
        return $this->hasMany(Attempt::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElement()
    {
        return $this->hasOne(Element::className(), ['id' => 'element_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeType()
    {
        return $this->hasOne(ChallengeType::className(), ['id' => 'challenge_type_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id'])->inverseOf('challenges');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeGenerations()
    {
        return $this->hasMany(ChallengeGeneration::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeHasQuestions()
    {
        return $this->hasMany(ChallengeHasQuestion::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])->viaTable('challenge_has_question', ['challenge_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeMarks()
    {
        return $this->hasMany(ChallengeMark::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeSettings()
    {
        return $this->hasOne(ChallengeSettings::className(), ['challenge_id' => 'id'])->inverseOf('challenge');
    }
}
