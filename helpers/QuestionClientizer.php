<?php

namespace app\helpers;

use app\models\Question;
use app\models\QuestionType;

/**
 * Prepares question to output to client
 * @package app\helpers
 */
class QuestionClientizer
{

    /**
     * Build client data
     * @param Question $question
     */
    public static function prepare(Question $question)
    {
        $type = $question->getQuestionType()->one();

        $data = $question->getData();
        $data = self::shuffleOptions($type, $data);
        $data = self::removeAnswers($type, $data);

        return (object)$data;
    }

    /**
     * Removes answers from data
     * @param $data
     * @return mixed
     */
    protected static function removeAnswers(QuestionType $type, $data)
    {
        switch ($type->sysname) {
            case 'select_one':
            case 'select_multiple':
                if (isset($data['answers'])) {
                    unset($data['answers']);
                }
                break;

            case 'text_short':
                if (isset($data['answer'])) {
                    unset($data['answer']);
                }
                break;
        }

        return $data;
    }

    /**
     * Shuffle question options
     * @param $data
     */
    protected static function shuffleOptions(QuestionType $type, $data)
    {
        switch ($type->sysname) {
            case 'select_one':
            case 'select_multiple':
            case 'text_short':
                $keys = array_keys($data['options']);
                shuffle($keys);

                $options = [];
                foreach ($keys as $id) {
                    $options[$id] = $data['options'][$id];
                }

                $data['options'] = $options;

                break;
        }

        return $data;
    }


}