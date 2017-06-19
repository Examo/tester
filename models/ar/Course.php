<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "course".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $position
 * @property integer $discipline_id
 *
 * @property Challenge[] $challenges
 * @property Discipline $discipline
 * @property CourseSubscription[] $courseSubscriptions
 * @property User[] $users
 * @property QuestionHasCourse[] $questionHasCourses
 * @property Question[] $questions
 * @property Subject[] $subjects
 */
class Course extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string'],
            [['position', 'discipline_id'], 'integer'],
            [['discipline_id'], 'required'],
            [['discipline_id'], 'exist', 'skipOnError' => true, 'targetClass' => Discipline::className(), 'targetAttribute' => ['discipline_id' => 'id']],
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
            'description' => 'Description',
            'position' => 'Position',
            'discipline_id' => 'Discipline ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['course_id' => 'id'])->inverseOf('course');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscipline()
    {
        return $this->hasOne(Discipline::className(), ['id' => 'discipline_id'])->inverseOf('courses');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourseSubscriptions()
    {
        return $this->hasMany(CourseSubscription::className(), ['course_id' => 'id'])->inverseOf('course');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('course_subscription', ['course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasCourses()
    {
        return $this->hasMany(QuestionHasCourse::className(), ['course_id' => 'id'])->inverseOf('course');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::className(), ['id' => 'question_id'])->viaTable('question_has_course', ['course_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['course_id' => 'id'])->inverseOf('course');
    }
}
