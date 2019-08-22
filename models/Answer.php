<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Answer extends \app\models\ar\Answer
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'attempt_id' => Yii::t('attempt', 'Attempt'),
            'question_id' => Yii::t('question', 'Question'),
            'data' => Yii::t('answer', 'Data'),
            'correct' => Yii::t('answer', 'Correct'),
            'hint' => Yii::t('answer', 'Hint'),
            'result' => Yii::t('answer', 'Results'),
        ];
    }

    /**
     * @throws \Exception
     */
    public function getResultCriterions()
    {
        $questionSettings = $this->question->questionSettings;

        if (!$questionSettings->settings) {
            throw new \Exception('Настройки задания не найдены');
        }

        $settings = str_replace(array('\n'), '', $questionSettings->settings);
        $jsonSettings = json_decode($settings);

        if (!$jsonSettings->settings) {
            throw new \Exception('Настройки задания не найдены');
        }

        try {
            return json_decode($jsonSettings->settings);
        } catch (\Exception $e) {
            throw new \Exception('Настройки задания заданы неверно');
        }
    }
}
