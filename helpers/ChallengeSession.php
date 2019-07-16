<?php

namespace app\helpers;

use app\models\Challenge;
use app\models\Question;
use yii\helpers\Json;

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
            $this->clearFinishTime();
            $this->setCountThreeQuestion(0);

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
     * @param $preview
     */
    public function answer($answer, $preview)
    {
        $question = $this->getCurrentQuestion();
        if ($question->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION && strlen($answer)) {
            $answer = $this->getAnswerThreeQuestion($question, $answer);
        }

        if ($preview) {
            $_SESSION['preview_answer'] = $answer;
            $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber());
            return;
        }

        if (strlen($_SESSION['preview_answer'])) {
            $answer = $_SESSION['preview_answer'];
        }

        if (!strlen($answer)) {
            $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber());
            return;
        }

        $this->storeAnswer($answer);
        $_SESSION['preview_answer'] = '';

        if ($question->question_type_id === \app\models\QuestionType::TYPE_THREE_QUESTION) {
            $this->setCountThreeQuestion($this->getCountThreeQuestion() + 1);
        }

        $this->setCurrentQuestionNumber($this->getCurrentQuestionNumber() + 1);

        if ($this->isFinished()) {
            $this->finish();
            return;
        }
    }

    /**
     * Get current question hint
     * @param int $num
     * @return string
     */
    public function hint($num = null)
    {
        $this->useHint($num);
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
        $array = array_splice($queue, $this->getCurrentQuestionNumber(), 1);

        $queue[] = reset($array);

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
     * @param int $num
     */
    protected function useHint($num = null)
    {
        $queue = $this->getQueue();
        $question = $queue[$this->getCurrentQuestionNumber()];

        $hints = $this->getHints();
        if (isset($num)) {
            $hints[$question][$num] = true;
        } else {
            $hints[$question] = true;
        }

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

    /**
     * @param Question $question
     * @param $answer
     * @return string
     */
    public function getAnswerThreeQuestion(Question $question, $answer)
    {
        $mistakes = $question->check($answer);
        $answers = Json::decode($answer) ?? ['', '', ''];

        if ($mistakes === true) {
            foreach ($answers as $key => $ans) {
                $arr = [$ans, 1];
                $answers[$key] = $arr;
            }
        } elseif (json_decode($mistakes)) {
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

        return Json::encode($answers);
    }

    /**
     * @param $count
     */
    public function setCountThreeQuestion($count)
    {
        return \Yii::$app->session->set($this->getSessionKey('three_question_count'), $count);
    }

    /**
     * @return int
     */
    public function getCountThreeQuestion()
    {
        return (int)\Yii::$app->session->get($this->getSessionKey('three_question_count'));
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

    public function clearFinishTime()
    {
        return \Yii::$app->session->set($this->getSessionKey('finish'), null);
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
