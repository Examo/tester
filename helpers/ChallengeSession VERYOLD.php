<?php

namespace app\helpers;

use app\models\Challenge;
use app\models\Question;

/**
 * Challenge Session Manager
 * @package app\helpers
 */
class ChallengeSession
{

    /**
     * @var Challenge
     */
    protected $challenge;

    /**
     * User ID
     * @var int
     */
    protected $user;

//----------------------------------------------------------------------------------------------------------------------
// Public
//----------------------------------------------------------------------------------------------------------------------

    /**
     * ChallengeSession constructor.
     * @param Challenge $challenge
     * @param $user
     */
    public function __construct(Challenge $challenge, $user)
    {
        $this->challenge = $challenge;
        $this->user = $user;
    }

    /**
     * Can user start this challenge?
     * @return bool
     */
    public function canStart()
    {
        return true;
    }

    /**
     * Start challenge
     * @return bool
     */
    public function start()
    {
        if ($this->canStart()) {
            $this->clearAnswers();
            $this->clearHints();
            $this->openQueue();
            $this->setCurrentQuestionNumber(0);
            $this->setStartTime();

            return true;
        }

        return false;
    }

    /**
     * Finish challenge
     * Calling when the last answer submitted
     */
    public function finish()
    {
        $this->closeQueue();
        $this->setFinishTime();
    }

    /**
     * Submit answer and switch to next question.
     * If no questions left - finish challenge
     * @param $answer string
     */
    public function answer($answer, $pre)
    {
        if (!$this->isFinished()) {
            if($pre) {
                $question = $this->getCurrentQuestion();

                if ($question->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION) {
                    $mistakes = $question->check($answer);
                    $answers = \yii\helpers\Json::decode($answer) ?? ['', '', ''];

                    if ($mistakes === true) {
                        foreach ($answers as $key => $ans) {
                            $arr = [$ans, 1];
                            $answers[$key] = $arr;
                        }
                    } else if (\yii\helpers\Json::decode($mistakes)) {
                        $mistakes = \yii\helpers\Json::decode($mistakes);
                        foreach ($answers as $key => $ans) {
                            $arr = [$ans, $mistakes[$key] ? 0 : 1];
                            $answers[$key] = $arr;
                        }
                    } else {
                        foreach ($answers as $key => $ans) {
                            $arr = [$ans, 0];
                            $answers[$key] = $arr;
                        }
                    }

                    $answer = \yii\helpers\Json::encode($answers);
                }

                $_SESSION['pre'] = $answer;
                $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber());
            } else {
                $this->storeAnswer($_SESSION['pre']);
                $_SESSION['pre'] = '';
                $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber() + 1);
            }
        }

        if ($this->isFinished()) {
            $this->finish();
        }
    }

    /**
     * Get current question hint
     * @return string
     */
    public function hint()
    {
        $this->useHint();
        $hints = $this->getCurrentQuestion()->getHint( true );

        if (is_array($hints)) {
            return \yii\helpers\Json::encode($hints);
        }
        return $hints;
    }

    /**
     * Move current question to the end of queue
     */
    public function skip()
    {
        $queue = $this->getQueue();

        $queue[] = reset(array_splice($queue, $this->getCurrentQuestionNumber(), 1));

        $this->setQueue($queue);
    }

    /**
     * Is last question reached?
     * @return bool
     */
    public function isFinished()
    {
        return $this->getCurrentQuestionNumber() >= count($this->getQueue());
    }

    /**
     * @return Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

//----------------------------------------------------------------------------------------------------------------------
// Current question
//----------------------------------------------------------------------------------------------------------------------

    /**
     * @return int
     */
    public function getCurrentQuestionNumber()
    {
        return \Yii::$app->session->get($this->getSessionKey('question'), 0);
    }

    /**
     * @return Question
     */
    public function getCurrentQuestion()
    {
        $queue = $this->getQueue();
        $question = $this->getCurrentQuestionNumber();
        return Question::findOne($queue[$question]);
    }

    /**
     * @param $value int
     */
    protected function setCurrentQuestionNumber($value)
    {
        \Yii::$app->session->set($this->getSessionKey('question'), $value);
    }

//----------------------------------------------------------------------------------------------------------------------
// Hints
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Remember hint usage for current question
     */
    protected function useHint()
    {
        $queue = $this->getQueue();
        $question = $queue[$this->getCurrentQuestionNumber()];

        $hints = $this->getHints();
        $hints[$question] = true;

        \Yii::$app->session->set($this->getSessionKey('hints'), $hints);
    }

    /**
     * Reset hints array in session
     */
    protected function clearHints()
    {
        \Yii::$app->session->remove($this->getSessionKey('hints'));
    }

    /**
     * Get hints usage
     * @return array
     */
    public function getHints()
    {
        return \Yii::$app->session->get($this->getSessionKey('hints'), []);
    }

    /**
     * Get is hint used for current question
     * @return bool
     */
    public function isHintUsed() {
        $queue = $this->getQueue();
        $question = $queue[$this->getCurrentQuestionNumber()];

        $hints = $this->getHints();

        return isset($hints[$question]) && $hints[$question];
    }

//----------------------------------------------------------------------------------------------------------------------
// Answers
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Temporary stores answer until challenge finished
     * @param $answer string
     */
    protected function storeAnswer($answer)
    {
        $answers = $this->getAnswers();
        $queue = $this->getQueue();
        $question = $queue[$this->getCurrentQuestionNumber()];

        $answers[$question] = $answer;

        \Yii::$app->session->set($this->getSessionKey('answers'), $answers);
    }

    /**
     * Reset answers array in session
     */
    protected function clearAnswers()
    {
        \Yii::$app->session->remove($this->getSessionKey('answers'));
    }

    /**
     * Get all answers
     * @return array
     */
    public function getAnswers()
    {
        return \Yii::$app->session->get($this->getSessionKey('answers'), []);
    }

//----------------------------------------------------------------------------------------------------------------------
// Questions queue
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Create questions queue
     */
    protected function openQueue()
    {
        $this->setQueue( $this->generateQueue() );
    }

    /**
     * Clear questions queue
     */
    protected function closeQueue()
    {
        \Yii::$app->session->remove($this->getSessionKey('queue'));
    }

    /**
     * Get questions ids for current challenge
     * @return int[]
     */
    protected function getQueue()
    {
        return \Yii::$app->session->get($this->getSessionKey('queue'));
    }

    /**
     * Set questions ids for current challenge
     * @param $queue
     */
    protected function setQueue($queue)
    {
        return \Yii::$app->session->set($this->getSessionKey('queue'), $queue);
    }

    /**
     * Generate questions queue using challenge settings
     * @return int[]
     */
    protected function generateQueue()
    {
        $queue = [];

        switch ($this->challenge->getMode()) {
            case Challenge::MODE_STATIC:
            case Challenge::MODE_DYNAMIC:
                foreach ($this->challenge->getChallengeHasQuestions()->all() as $item) {
                    $queue[] = $item->question_id;
                }
                break;

            case Challenge::MODE_RANDOM:
                $chooser = new QuestionChooser($this->challenge);
                $queue = $chooser->generate();
                break;

        }

        return $queue;
    }

//----------------------------------------------------------------------------------------------------------------------
// Time tracking
//----------------------------------------------------------------------------------------------------------------------

    protected function setStartTime()
    {
        \Yii::$app->session->set($this->getSessionKey('start'), time());
    }

    protected function setFinishTime()
    {
        \Yii::$app->session->set($this->getSessionKey('finish'), time());
    }

    public function getStartTime()
    {
        return \Yii::$app->session->get($this->getSessionKey('start'));
    }

    public function getFinishTime()
    {
        return \Yii::$app->session->get($this->getSessionKey('finish'));
    }

//----------------------------------------------------------------------------------------------------------------------
// Helpers
//----------------------------------------------------------------------------------------------------------------------

    /**
     * Generate session key
     * @param string $postfix
     * @return string
     */
    private function getSessionKey($postfix = '')
    {
        return implode('-', ['challenge', $this->challenge->id, $this->user, $postfix]);
    }
}
