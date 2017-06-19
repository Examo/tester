<?php

namespace app\helpers;

use app\models\Challenge;
use app\models\Question;

/**
 * Questions sequence generator
 * @package app\helpers
 */
class QuestionChooser
{

    /**
     * Generation rules
     * @var array
     */
    protected $rules = [];

    /**
     * Excluded Ids
     * @var array
     */
    protected $ignore = [];

    /**
     * Current rule
     * @var int
     */
    protected $rule = 0;

    /**
     * Current question
     * @var int
     */
    protected $question = 0;

    /**
     * Question buffer
     * @var array
     */
    protected $queue = [];

    /**
     * Create from challenge settings
     * @param Challenge $challange
     * @return QuestionChooser
     */
    public static function fromChallenge(Challenge $challange)
    {
        $inst = new self;

        foreach ($challange->challengeGenerations as $rule) {
            $inst->addRule($rule->question_type_id, $rule->question_count);
        }

        return $inst;
    }

    /**
     * Add generation rule
     * @param $type Question Type Id
     * @param $count Amount of questions
     */
    public function addRule($type, $count)
    {
        $this->rules[] = [$type, $count];
    }

    /**
     * Prevent question from being choosed
     * @param Question $question
     */
    public function ignoreQuestion(Question $question)
    {
        $this->ignore[] = $question->id;
    }

    /**
     * Generate questions sequence
     * @return int[] Ids
     */
    public function generate()
    {
        $result = [];

        while ($next = $this->next()) {
            $result[] = $next;
        }

        return $result;
    }

    /**
     * Get next ID in sequence
     * @return bool FALSE if no questions left
     */
    public function next()
    {
        if (!isset($this->queue[$this->question])) {
            $this->nextRule();
        }

        if (isset($this->queue[$this->question])) {
            return $this->nextQuestion();
        } else {
            return false;
        }
    }

    /**
     * Prefill queue on rule switching
     */
    protected function nextRule()
    {
        if (isset($this->rules[$this->rule])) {
            $type = $this->rules[$this->rule][0];
            $count = $this->rules[$this->rule][1];

            $query = Question::find()->where(['not in', 'id', $this->ignore])->andWhere(['question_type_id' => $type]);
            $realCount = $query->count();

            if ($realCount <= $count) {
                $questions = $query->select('id')->column();
            } else {
                $offsets = [];
                for ($i = 0; $i < $count; $i++) {
                    while (in_array($num = mt_rand(0, $realCount - 1), $offsets)) {
                    }
                    $offsets[] = $num;
                }
                foreach ($offsets as $offset) {
                    $questions[] = $query->offset($offset)->limit(1)->select('id')->scalar();
                }
            }

            foreach ($questions as $id) {
                $this->queue[] = $id;
                $this->ignore[] = $id;
            }

            $this->rule++;
        }
    }

    /**
     * Get next question
     * @return int
     */
    protected function nextQuestion()
    {
        $this->question++;
        return $this->queue[$this->question - 1];
    }
}