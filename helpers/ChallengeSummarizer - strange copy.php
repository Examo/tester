<?php

namespace app\helpers;

use app\models\Answer;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Question;

/**
 * Class ChallengeSummarizer
 * @package app\helpers
 */
class ChallengeSummarizer
{

    /**
     * @var DateTime
     */
    protected $startTime;

    /**
     * @var DateTime
     */
    protected $finishTime;

    /**
     * @var array
     */
    protected $marks = [];

    /**
     * @var string[]
     */
    public $answers = [];

    /**
     * @var bool[]
     */
    protected $correct = [];

    /**
     * @var bool[]
     */
    protected $hints = [];

    //protected $comment = [];

    /**
     * @var Challenge
     */
    protected $challenge = false;

    /**
     * @var int
     */
    protected $user = false;

    protected $questions = null;
    
    

    /**
     * @param ChallengeSession $session
     * @return ChallengeSummarizer
     */
    public static function fromSession(ChallengeSession $session)
    {
        $inst = new self($session->getChallenge(), $session->getUser());

        $inst->setStartTime($session->getStartTime());
        $inst->setFinishTime($session->getFinishTime());

        $hints = $session->getHints();
        $comments = $session->getComments();
        foreach ($session->getAnswers() as $question => $answer) {
            $hint = isset($hints[$question]) && $hints[$question];
            $inst->addAnswer($question, $answer, null, $hint, $comments);
        }

        foreach ($session->getChallenge()->getChallengeMarks()->all() as $range) {
            $inst->addMarkRange($range->value_from, $range->value_to, $range->mark);
        }

        return $inst;
    }

    /**
     * @param Attempt $attempt
     * @return ChallengeSummarizer
     */
    public static function fromAttempt(Attempt $attempt)
    {
        $inst = new self($attempt->challenge, $attempt->user_id);

        $inst->setStartTime(strtotime($attempt->start_time));
        $inst->setFinishTime(strtotime($attempt->finish_time));

        foreach ($attempt->getAnswers() as $answer) {
            $inst->addAnswer($answer->question_id, $answer->data, $answer->correct, $answer->hint);
        }

        foreach ($attempt->getChallenge()->getChallengeMarks()->all() as $range) {
            $inst->addMarkRange($range->value_from, $range->value_to, $range->mark);
        }

        return $inst;
    }

    /**
     * ChallengeSummarizer constructor.
     * @param Challenge $challenge
     * @param $user
     */
    public function __construct(Challenge $challenge, $user)
    {
        $this->challenge = $challenge;
        $this->user = $user;
    }


    /**
     * @param $question
     * @param $answer
     * @param null|bool $correct If null answer will be checked automaticaly
     */
    public function addAnswer($question, $answer, $correct = null, $hintUsed = false, $comments)
    {
        $this->answers[$question] = $answer;
        $this->hints[$question] = $hintUsed;
        $this->comment[$question] = $comments;

        if (!is_null($correct)) {
            $this->correct[$question] = $correct;
        }
    }

    /**
     * Add new mark range
     * @param $min From percents
     * @param $max To percents
     * @param $mark
     */
    public function addMarkRange($min, $max, $mark)
    {
        $this->marks[] = [
            'min' => $min,
            'max' => $max,
            'mark' => $mark
        ];
    }

    /**
     * Save summarizer results as Attempt
     * @return Attempt
     */
    public function saveAttempt()
    {
        $previous = Attempt::find()->where([
            'challenge_id' => $this->challenge->id,
            'user_id' => $this->user,
            'start_time' => date('Y-m-d H:i:s', $this->startTime),
            'finish_time' => date('Y-m-d H:i:s', $this->finishTime)
        ])->one();

        if ($previous) {
            return $previous;
        }

        $attempt = new Attempt();
        $attempt->challenge_id = $this->challenge->id;
        $attempt->user_id = $this->user;
        $attempt->start_time = date('Y-m-d H:i:s', $this->startTime);
        $attempt->finish_time = date('Y-m-d H:i:s', $this->finishTime);
        $attempt->mark = $this->getMark();
        if (!$attempt->save()) {
            return false;
        }

        $correct = $this->getCorrectness();
        foreach ($this->getQuestions() as $question) {
            $answer = new Answer();
            $answer->attempt_id = $attempt->id;
            $answer->question_id = $question->id;
            $answer->data = $this->answers[$question->id];
            $answer->correct = (int)$correct[$question->id];
            $answer->hint = (int)$this->hints[$question->id];
            //$answer->comment = $this->comment[$question->id];
            $answer->save();
        }

        return $attempt;
    }

//----------------------------------------------------------------------------------------------------------------------
// Results
//----------------------------------------------------------------------------------------------------------------------

    /**
     * @return Question[]
     */
    public function getQuestions()
    {
        if (is_null($this->questions)) {
            $this->questions = Question::find()->where(['id' => array_keys($this->answers)])->all();
        }

        return $this->questions;
    }

    /**
     * @return \bool[]
     */
    public function getCorrectness()
    {
        foreach ($this->getQuestions() as $question) {
            if (!isset($this->correct[$question->id])) {
                $this->correct[$question->id] = $question->check($this->answers[$question->id]);
            }
        }

        return $this->correct;
    }

    public function getMistakes(Question $question) {
        return $question->getMistakes( $this->answers[$question->id] );
    }

    /**
     * @return \bool[]
     */
    public function getHints()
    {
        return $this->hints;
    }

    public function getComments()
    {
        return $this->comment;
    }

    /**
     * Get question result points
     * @return float[]
     */
    public function getPoints() {
        $result = [];
        foreach ($this->getQuestions() as $question) {
            $points = (int)$question->getPoints($this->answers[$question->id]);

            // if hint used, decrease points amount
            if ( $this->hints[$question->id] ) {
                $points /= 2;
            }

            $result[$question->id] = $points;
        }
        return $result;
    }

    /**
     * Get challenge maximum points
     * @return float
     */
    public function getMaxPoints() {
        $max = 0;

        foreach ($this->getQuestions() as $question) {
            $max += (int)$question->getCost();
        }

        return $max;
    }

    /**
     * Get percent of correct answers
     * @return float
     */
    public function getCorrectPercent()
    {
        $max = $this->getMaxPoints();
        $total = array_sum( $this->getPoints() );

        return $max > 0 ? round($total / $max * 100) : 0;
    }

    /**
     * Get mark based on mark ranges
     * @return string
     */
    public function getMark()
    {
        if ( $this->challenge->getQuestionsCount() > count($this->getQuestions()) ) {
            return false;
        }

        $percent = $this->getCorrectPercent();

        foreach ($this->marks as $range) {
            if ($percent >= $range['min'] && $percent <= $range['max']) {
                return $range['mark'];
            }
        }

        return false;
    }
    
    public function getTextMark($numberMark)
    {
        switch ($numberMark) {
            case 2:
                echo '2 — "ДВОЙКА"';
                break;
            case 3:
                echo '3 — "ТРОЙКА"';
                break;
            case 4:
                echo '4 — "ЧЕТВЁРКА"';
                break;
            case 5:
                echo '5 — "ПЯТЁРКА"';
                break;
        }
    }

    public function getAllPoints($questions, $points)
    {
        $numberOfPoints = 0;
        $allPoints = 0;
        foreach ($questions as $key => $question){
            $allPoints += $points[$question->id];
            $numberOfPoints += $question->cost;
        }
        return [
            'allPoints' => $allPoints,
            'numberOfPoints' => $numberOfPoints
        ];
    }

    public function getEmoticon($mark)
    {
        $emoticonExcellent = ['catemoticonexcellent1.png', 'catemoticonexcellent2.png', 'catemoticonexcellent3.png', 'catemoticonexcellent4.png', 'catemoticonexcellent5.png', 'catemoticonexcellent6.png'];
        $emoticonGood = ['catemoticongood1.png', 'catemoticongood2.png', 'catemoticongood3.png', 'catemoticongood4.png', 'catemoticongood5.png', 'catemoticongood6.png'];
        $emoticonSatisfactory = ['catemoticonsatisfactory1.png', 'catemoticonsatisfactory2.png', 'catemoticonsatisfactory3.png', 'catemoticonsatisfactory4.png', 'catemoticonsatisfactory5.png', 'catemoticonsatisfactory6.png'];
        $emoticonBad = ['catemoticonbad1.png', 'catemoticonbad2.png', 'catemoticonbad3.png', 'catemoticonbad4.png', 'catemoticonbad5.png', 'catemoticonbad6.png'];
        switch ($mark){
            case 2: echo '<img src="/i/'.$emoticonBad[mt_rand(0, count($emoticonBad) - 1)].'" />';
                break;
            case 3: echo '<img src="/i/'.$emoticonSatisfactory[mt_rand(0, count($emoticonSatisfactory) - 1)].'" />';
                break;
            case 4: echo '<img src="/i/'.$emoticonGood[mt_rand(0, count($emoticonGood) - 1)].'" />';
                break;
            case 5: echo '<img src="/i/'.$emoticonExcellent[mt_rand(0, count($emoticonExcellent) - 1)].'" />';
                break;
         }
    }

    public function getAnswersFinish($data, $questionId, $questionTypeId, $answers)
    {
       switch ($questionTypeId){
            case 1:
                foreach (json_decode($data, true)['options'] as $key => $option) {
                    $rightOption = '[' . $key . ']';
                    if ($rightOption == $answers[$questionId]) {
                        echo '<center>'.mb_strtoupper($option).'</center>';
                    }
                }
                break;
            case 2:
                for ($i = 0; $i < count(json_decode($answers[$questionId], true)); $i++) {
                    if (json_decode($data, true)['options'][json_decode($answers[$questionId], true)[$i]]) {
                        echo '<li>'.mb_strtoupper(json_decode($data, true)['options'][json_decode($answers[$questionId], true)[$i]]).'</li>';
                   }
                }
                break;
            case 3:
                echo '<center>'.mb_strtoupper($answers[$questionId]).'</center>';
                break;
            case 4:
                echo 'type 4';
                break;
            case 5:
                echo 'type 5';
                break;
            case 6:
                echo 'type 6';
                break;
            case 7:
                $number = 0;
                for ($i = 0; $i < count(json_decode($answers[$questionId])); $i++) {
                    $number++;
                    foreach (json_decode($answers[$questionId])[$i] as $key => $item) {
                        if ($key == 0){
                            echo  '<strong>'.$number.'-я пара:</strong> <br>'.json_decode($data, true)['options'][$item].'<br><strong><=></strong><br>';
                        } elseif ($key == 1){
                            echo json_decode($data, true)['associations'][$item].'<br><br>';
                        }
                    }
                }
                 /*
                 *var_dump(json_decode($data, true));
                 * array(3) {
                 * ["options"]=> array(5) {
                 * [0]=> string(16) "1 задание" [1]=> string(16) "2 задание" [2]=> string(16) "3 задание" [3]=> string(16) "4 задание" [4]=> string(16) "5 задание" }
                 * ["associations"]=> array(5) {
                 * [0]=> string(22) "А к 1 заданию" [1]=> string(22) "Б к 2 задание" [2]=> string(22) "В к 3 заданию" [3]=> string(23) "Г к 4 заданию " [4]=> string(22) "Д к 5 заданию" }
                 * ["comments"]=> array(5) {
                 * [0]=> string(29) "Комментарий к 1А" [1]=> string(29) "Комментарий к 2Б" [2]=> string(29) "Комментарий к 3В" [3]=> string(29) "Комментарий к 4Г" [4]=> string(29) "Комментарий к 5Д" } }
                 *
                 * var_dump(json_decode($data, true)['associations']);
                 * array(5) {
                 * [0]=> string(22) "А к 1 заданию"
                 * [1]=> string(22) "Б к 2 задание"
                 * [2]=> string(22) "В к 3 заданию"
                 * [3]=> string(23) "Г к 4 заданию "
                 * [4]=> string(22) "Д к 5 заданию" }
                 *
                 * var_dump(json_decode($data, true)['options']);
                 * array(5) {
                 * [0]=> string(16) "1 задание"
                 * [1]=> string(16) "2 задание"
                 * [2]=> string(16) "3 задание"
                 * [3]=> string(16) "4 задание"
                 * [4]=> string(16) "5 задание" }
                 *
                 * var_dump(json_decode($answers[$questionId]));
                 * array(5) {
                [0]=> array(2) { [0]=> int(0) [1]=> int(1) }
                [1]=> array(2) { [0]=> int(1) [1]=> int(2) }
                [2]=> array(2) { [0]=> int(2) [1]=> int(4) }
                [3]=> array(2) { [0]=> int(3) [1]=> int(3) }
                [4]=> array(2) { [0]=> int(4) [1]=> int(0) } }*/
                break;
        }
    }


//----------------------------------------------------------------------------------------------------------------------
// Time tracking
//----------------------------------------------------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param mixed $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * @return mixed
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * @param mixed $finishTime
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;
    }

    /**
     * Get time amount taken by challenge
     * @return mixed
     */
    public function getTime()
    {
        return $this->finishTime - $this->startTime;
    }

}