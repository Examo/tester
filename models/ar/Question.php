<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "question".
 *
 * @property integer $id
 * @property integer $question_type_id
 * @property string $text
 * @property string $data
 * @property string $hint
 * @property string $comment
 * @property integer $cost
 *
 * @property Answer[] $answers
 * @property ChallengeHasQuestion[] $challengeHasQuestions
 * @property Challenge[] $challenges
 * @property QuestionType $questionType
 * @property QuestionHasChallengeType[] $questionHasChallengeTypes
 * @property ChallengeType[] $challengeTypes
 * @property QuestionHasCourse[] $questionHasCourses
 * @property Course[] $courses
 * @property QuestionHasSubject[] $questionHasSubjects
 * @property Subject[] $subjects
 */
class Question extends \app\components\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'question';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question_type_id'], 'required'],
            [['question_type_id', 'cost', 'right_points', 'wrong_points'], 'integer'],
            [['text', 'data', 'hint', 'comment'], 'string'],
            [['question_type_id', 'right_points', 'wrong_points'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionType::className(), 'targetAttribute' => ['question_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'question_type_id' => 'Question Type ID',
            'text' => 'Text',
            'data' => 'Data',
            'hint' => 'Hint',
            'comment' => 'Comment',
            'cost' => 'Cost',
            'right_points' => 'Right Points',
            'wrong_points' => 'Wrong Points',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeHasQuestions()
    {
        return $this->hasMany(ChallengeHasQuestion::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallenges()
    {
        return $this->hasMany(Challenge::className(), ['id' => 'challenge_id'])->viaTable('challenge_has_question', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'question_type_id'])->inverseOf('questions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasChallengeTypes()
    {
        return $this->hasMany(QuestionHasChallengeType::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChallengeTypes()
    {
        return $this->hasMany(ChallengeType::className(), ['id' => 'challenge_type_id'])->viaTable('question_has_challenge_type', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasCourses()
    {
        return $this->hasMany(QuestionHasCourse::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['id' => 'course_id'])->viaTable('question_has_course', ['question_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionHasSubjects()
    {
        return $this->hasMany(QuestionHasSubject::className(), ['question_id' => 'id'])->inverseOf('question');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['id' => 'subject_id'])->viaTable('question_has_subject', ['question_id' => 'id']);
    }

    /**
     * @param $hint
     * @param $answer
     * @param int $type
     */
    public function getRightHintText($hint, $answer, int $type)
    {
        if ($type === \app\models\QuestionType::TYPE_ASSOC_TABLE){
            if ($hint == true && $answer == true) {
                echo 'Подсказка была, увы, общий балл делится пополам';
            } elseif ($hint == true && $answer == false) {
                echo 'Подсказка была';
            } elseif ($hint == false && $answer == false) {
                echo 'Подсказки не было';
            } elseif ($hint == false && $answer == true) {
                echo 'Подсказки не было, великолепно!';
            }
        } else {
            if ($hint == true && $answer == true) {
                echo 'Подсказка была, увы, балл делится пополам';
            } elseif ($hint == true && $answer == false) {
                echo 'Подсказка была, но это не важно';
            } elseif ($hint == false && $answer == false) {
                echo 'Подсказки не было, но это не важно';
            } elseif ($hint == false && $answer == true) {
                echo 'Подсказки не было, ура!';
            }
        }
    }

    public function getOptionsFinish($data)
    {
        if (isset(json_decode($data, true)['associations'])) {
            $i = 1;
            foreach (json_decode($data, true)['associations'] as $key => $item){
                echo '<strong>'.$i.'-я пара:</strong><br>'.json_decode($data, true)['options'][$key].'<br><center><strong><=></strong></center>'.$item.'</li><br><br>';
                $i++;
            }
        } else {
            if (isset(json_decode($data, true)['options'])) {
                foreach (json_decode($data, true)['options'] as $option) {
                    echo "<li>" . $option . "</li>";
                }
            }
        }
    }
}
