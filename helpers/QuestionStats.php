<?php

namespace app\helpers;

use app\models\Question;
use app\models\QuestionType;

/**
 * Prepares question to output to client
 * @package app\helpers
 */
class QuestionStats
{

    /**
     * @param \app\models\ar\Question $question
     * @return float|int
     */
    public static function getNumberOfRightPoints(\app\models\ar\Question $question)
    {
        $numberOfRightPoints = 0;

        if ($question->right_points !== 0) {
            $numberOfRightPointsCoefficient = ($question->right_points + $question->wrong_points) / $question->right_points;
            $numberOfRightPoints = 100 / $numberOfRightPointsCoefficient;
        }

        return $numberOfRightPoints;
    }

    /**
     * @param Question|int $question
     * @return string
     */
    public static function getProgressBar($question)
    {
        if (is_integer($question)) {
            $questionModel = Question::findOne(['id' => $question]);
        } else {
            $questionModel = $question;
        }

        $numberOfPupils = $questionModel->right_points + $questionModel->wrong_points;
        $numberOfRightPoints = QuestionStats::getNumberOfRightPoints($questionModel);
        $numberOfWrongPoints = 100 - $numberOfRightPoints;

        return '<center>Выполняли задание раз: <strong>' . $numberOfPupils . '</strong></center>
            <center>
                <label>Неправильно: 
                    <strong>' . $questionModel->wrong_points . '</strong>
                    / Правильно: 
                    <strong>' . $questionModel->right_points . '</strong>
                </label>
            </center>
            <div class="progress">
                <div class="progress-bar progress-bar-info progress-bar-danger" 
                     role="progressbar" 
                     aria-valuenow="25.9" 
                     aria-valuemin="10" 
                     style="width:' . $numberOfWrongPoints . '% ">
                </div>
                <div class="progress-bar progress-bar-info" 
                     role="progressbar" 
                     aria-valuenow="74.1" 
                     aria-valuemin="10" 
                     style="width:' . $numberOfRightPoints . '% ">
                </div>
            </div>';
    }
}