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
    protected $answers = [];

    /**
     * @var bool[]
     */
    protected $correct = [];

    /**
     * @var bool[]
     */
    protected $hints = [];

    protected $comments = [];

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
        foreach ($session->getAnswers() as $question => $answer) {
            $hint = isset($hints[$question]) && $hints[$question];
            $inst->addAnswer($question, $answer, null, $hint);
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
    public function addAnswer($question, $answer, $correct = null, $hintUsed = false)
    {
        $this->answers[$question] = $answer;
        $this->hints[$question] = $hintUsed;

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
        return $this->comments;
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