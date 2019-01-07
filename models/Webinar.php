<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "webinar".
 *
 * @property integer $id
 */
class Webinar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'webinar';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //,
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    public function getWebinarChallenges($week, $exercise_number){
        $challenges = Challenge::find()->where(['exercise_number' => $exercise_number])->andWhere(['week' => $week])->all();
        //\yii\helpers\VarDumper::dump($challenges, 10, true);
        return $challenges;
    }



    public function getChallengesStatistic($challenges){
        $challengeQuestions = [];
        $webinar = new Webinar();
        foreach ($challenges as $challenge) {
            $challengesHasQuestion = ChallengeHasQuestion::find()->where(['challenge_id' => $challenge->id])->all();
            $questions = [];
                foreach ($challengesHasQuestion as $key => $challengeHasQuestion) {
                // \yii\helpers\VarDumper::dump($challenge->question_id, 10, true);
                $questions[$challengeHasQuestion->question_id] = Question::find()->where(['id' => $challengeHasQuestion->question_id])->one();
                //\yii\helpers\VarDumper::dump($webinar->getQuestionInfo($questions[$challengeHasQuestion->question_id]), 10, true);
            }
            $challengeQuestions[$challenge->id] = $questions;
        }
       // \yii\helpers\VarDumper::dump($challengeQuestions, 10, true);
        return $challengeQuestions;
    }

    public function getQuestionInfo($question){

        if ($question->question_type_id == 1){
            //print 'LIL BITCH';
            //\yii\helpers\VarDumper::dump($question->text, 10, true);
            print '<br><br>Вопрос:<br>';
            print $question->text;
            print '<br>';
            //print $question->data;
            $data = json_decode($question->data, true);
            //\yii\helpers\VarDumper::dump($data['options'], 10, true);

            print 'Варианты ответов:<br>';
            foreach ($data['options'] as $option){
                print $option . '<br>';
            }

            print 'Правильный ответ(-ы):<br>';
            foreach ($data['answers'] as $option){
                print $data['options'][$option] . '<br>';
            }

            print 'Объяснение:<br>';
            print $question->comment;

        }
        if ($question->question_type_id == 2){

            //print '5 ZADANIE';
            print '<br><br>Вопрос:<br>';
            print $question->text;
            print '<br>';
            //print $question->data;
            $data = json_decode($question->data, true);
            //\yii\helpers\VarDumper::dump($data['options'], 10, true);

            print 'Варианты ответов:<br>';
            foreach ($data['options'] as $option){
                print $option . '<br>';
            }

            print 'Правильный ответ(-ы):<br>';
            foreach ($data['answers'] as $option){
                print $data['options'][$option] . '<br>';
            }

            print 'Объяснение:<br>';
            print $question->comment;
        }
        if ($question->question_type_id == 3){
            //\yii\helpers\VarDumper::dump($question, 10, true);
            print '<br><br>Вопрос:<br>';
            print $question->text;
            print '<br>';
            //print $question->data;
            $data = json_decode($question->data, true);
            //\yii\helpers\VarDumper::dump($data['options'], 10, true);

            print 'Варианты ответов:<br>';
            foreach ($data['options'] as $option){
                print $option . '<br>';
            }

            print 'Правильный ответ:<br>';
            //foreach ($data['answer'] as $option){
                print $data['answer'] . '<br>';
            //}

            print 'Объяснение:<br>';
            print $question->comment;
        }
        if ($question->question_type_id == 4){
            print '<br><br>4 TYPE<br>';
        }
        if ($question->question_type_id == 5){
            print '<br><br>5 TYPE<br>';
        }
        if ($question->question_type_id == 6){
            print '<br><br>6 TYPE<br>';
        }
        if ($question->question_type_id == 7){
            //print '<br><br>7 TYPE<br>';
            print '<br><br>Текст:<br>';
            print $question->text;
            print '<br>';
            //print $question->data;
            $data = json_decode($question->data, true);
            //\yii\helpers\VarDumper::dump($data['options'], 10, true);

            print 'Варианты ответов:<br>';
            foreach ($data['options'] as $option){
                print $option . '<br>';
            }

            print 'Правильный ответ:<br>';
            foreach ($data['associations'] as $association){
            print $association . '<br>';
            }
        }
        if ($question->question_type_id == 8){
            //print '<br><br>8 TYPE<br>';
            print '<br><br>Текст:<br>';
            print $question->text;
            print '<br>';
            //print $question->data;
            $data = json_decode($question->data, true);
            //\yii\helpers\VarDumper::dump($data['options'], 10, true);

            print 'Варианты ответов:<br>';
            foreach ($data['question'] as $question){
                print $question . '<br>';
            }

            print 'Правильный ответ:<br>';
            foreach ($data['answer'] as $aanswer){
                print $aanswer . '<br>';
            }
        }

    }
}
