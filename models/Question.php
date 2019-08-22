<?php

namespace app\models;

use app\helpers\QuestionChecker;
use kartik\markdown\Markdown;
use Yii;
use yii\helpers\Json;

/**
 * @inheritdoc
 *
 * @property int[] $courses_ids
 * @property int[] $subjects_ids
 * @property int[] $challengeTypes_ids
 */
class Question extends \app\models\ar\Question
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \voskobovich\behaviors\ManyToManyBehavior::className(),
                'relations' => [
                    'courses_ids' => 'courses',
                    'subjects_ids' => 'subjects',
                    'challengeTypes_ids' => 'challengeTypes',
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['courses_ids', 'subjects_ids', 'challengeTypes_ids'], 'each', 'rule' => ['integer']],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'question_type_id' => Yii::t('questionType', 'Question Type'),
            'text' => Yii::t('question', 'Text'),
            'data' => Yii::t('question', 'Data'),
            'hint' => Yii::t('question', 'Hint'),
            'comment' => Yii::t('question', 'Comment'),
            'cost' => Yii::t('question', 'Cost'),
            'right_points' => Yii::t('question', 'Right Points'),
            'wrong_points' => Yii::t('question', 'Wrong Points'),
            'courses_ids' => Yii::t('question', 'Courses'),
            'subjects_ids' => Yii::t('question', 'Subjects'),
            'challengeTypes_ids' => Yii::t('question', 'Challenge Types'),
            'question_settings_id' => Yii::t('question', 'Question Settings Id')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionType()
    {
        return $this->hasOne(QuestionType::className(), ['id' => 'question_type_id'])->inverseOf('questions');
    }

    /**
     * Check if answer is correct
     * @param $answer
     * @return bool
     */
    public function check($answer)
    {
        $result = QuestionChecker::check($this, $answer);

        return is_array($result) ? false : $result;
    }

    /**
     * Get mistakes list
     * @param $answer
     * @return int[]
     */
    public function getMistakes($answer) {
        $result = [];

        $mistakes = QuestionChecker::check($this, $answer);
        if ( is_array($mistakes) ) {
            $data = $this->getData();
            foreach ( $mistakes as $mistake ) {
                if ( isset($data['comments'][$mistake]) ) {
                    $result[] = $data['comments'][$mistake];
                }
            }
        }

        return $result;
    }

    public function getMaxMistakes()
    {
        switch ($this->getQuestionType()->one()->sysname) {
            case 'dictation':
                $data = $this->getData();
                $options = 0;
                foreach ($data['items'] as $item) $options += is_array($item);
                return $options;

            case 'assoc':
            case 'assoc_table':
                $data = $this->getData();
                return count($data['options']);

            case 'three_question':
                return 3;

            default:
                return 1;
        }
    }

    /**
     * @return array
     */
    public function getData() {
        return Json::decode($this->data);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if( parent::beforeDelete() ) {
            $cond = ['question_id' => $this->id];

            ChallengeHasQuestion::deleteAll($cond);

            QuestionHasCourse::deleteAll($cond);
            QuestionHasChallengeType::deleteAll($cond);
            QuestionHasSubject::deleteAll($cond);

            Answer::deleteAll($cond);

            return true;
        }

        return false;
    }

    /**
     * @param bool $html
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getComment($html = false) {
        return $html ? nl2br(rtrim(Markdown::convert($this->comment), "\r\n")) : $this->comment;
    }

    /**
     * @param bool $html
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getText($html = false) {
        return $html ? nl2br(rtrim(Markdown::convert($this->text), "\r\n")) : $this->text;
    }

    /**
     * @param bool $html
     * @return string|array
     * @throws \yii\base\InvalidConfigException
     */
    public function getHint($html = false) {
        $hints = json_decode($this->hint) ? Json::decode($this->hint) : $this->hint;

        if (is_array($hints)) {
            $res = [];
            foreach ($hints as $hint) {
                $res[] = $html ? nl2br(rtrim(Markdown::convert($hint), "\r\n")) : $hint;
            }
            return $res;
        }

        return $html ? nl2br(rtrim(Markdown::convert($this->hint), "\r\n")) : $this->hint;
    }

    /**
     * Get question max points
     * @return int
     */
    public function getCost() {
        if ($this->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION) {
            return $this->cost ? $this->cost*3 : $this->getMaxMistakes();
        } else {
            return $this->cost ? $this->cost : $this->getMaxMistakes();
        }
    }

    /**
     * @param $answer
     * @return float|int
     */
    public function getPoints($answer) {
        $mistakes = QuestionChecker::check($this, $answer);
        $maxMistakes = $this->getMaxMistakes();

        if ($this->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION) {
            $mistakes = Json::decode($mistakes) ?? 3;
            $mistakesCount = 0;

            if (is_array($mistakes)) {
                foreach ($mistakes as $key => $rightAnswer) {
                    if ($rightAnswer === 0 && $key === 0) {
                        $mistakesCount += 2;
                    } elseif ($rightAnswer === 0) {
                        $mistakesCount += 1;
                    }
                }
            }

            $mistakes = $mistakesCount;
            $mistakeCost = $maxMistakes ? $this->getCost() / $maxMistakes : 0;
            return round($this->getCost() - ( $mistakes * $mistakeCost ));
        } else {
            $mistakes = is_array($mistakes) ? count($mistakes) : (int)(!$mistakes);
            $mistakeCost = $maxMistakes ? $this->getCost() / $maxMistakes : 0;
            return $this->getCost() - ( $mistakes * $mistakeCost );
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

    public function getCorrectAnswersFinish($data, $type)
    {
        if (isset(json_decode($data, true)['answer']) && $type == 3){
            echo '<li>'.json_decode($data, true)['answer'].'</li>';
        } elseif (isset(json_decode($data, true)['answers']) && $type == 1 || $type == 2) {
             foreach (json_decode($data, true)['answers'] as $item){
                 echo '<li>'.json_decode($data, true)['options'][$item].'</li>';
             }
        } elseif (isset(json_decode($data, true)['associations']) && $type == (int)7){
            foreach (json_decode($data, true)['associations'] as $key => $item){
                echo '<li>'.json_decode($data, true)['options'][$key].' <=> '.$item.'</li>';
            }
        }
    }

    public function getCommentFinish($data)
    {
        $i = 1;
        if (isset(json_decode($data, true)['comments'])) {
            foreach (json_decode($data, true)['comments'] as $item){
                echo '<center><strong>'.$i.'-я пара:</strong>'.'</center>';
                echo '<li>'.$item.'</li><br>';
                $i++;
            }
        } else {
            echo $this->comment;
        }
    }



}
