<?php

namespace app\models;

use app\helpers\Subset;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\widgets\AnswerEditor;
use Yii;
use yii\db\ActiveQuery;

/**
 * @inheritdoc
 *
 * @property string $mode
 * @property ChallengeSettings $settings
 */
class Challenge extends \app\models\ar\Challenge
{
    const MODE_STATIC = 'static';
    const MODE_DYNAMIC = 'dynamic';
    const MODE_RANDOM = 'random';

    private $_settings = null;

    /**
     * Get free chalanges
     * @return ActiveQuery
     */
    static public function findFree()
    {
        return self::find()->with([
            'challengeSettings' => function (\yii\db\ActiveQuery $query) {
                $query->andWhere([
                    'registration_required' => false,
                    'subscription_required' => false,
                ]);
            }
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'course_id' => Yii::t('course', 'Course'),
            'challenge_type_id' => Yii::t('challengeType', 'Challenge Type'),
            'element_id' => Yii::t('element', 'Element'),
            'subject_id' => Yii::t('subject', 'Subject'),
            'grade_number' => Yii::t('challenge', 'Grade Number'),
            'name' => Yii::t('challenge', 'Name'),
            'description' => Yii::t('challenge', 'Description'),
            'exercise_number' => Yii::t('challenge', 'Exercise Number'),
            'exercise_challenge_number' => Yii::t('challenge', 'Exercise Challenge Number'),
            'challengeHasQuestions' => Yii::t('challenge', 'Challenge Has Questions'),
            'challengeGenerations' => Yii::t('challenge', 'Challenge Generations'),
        ];
    }

    /**
     * Get modes list
     * @return array
     */
    public function modeLabels()
    {
        return [
            self::MODE_STATIC => Yii::t('challenge', 'Mode Static'),
            self::MODE_DYNAMIC => Yii::t('challenge', 'Mode Dynamic'),
            self::MODE_RANDOM => Yii::t('challenge', 'Mode Random'),
        ];
    }

    /**
     * @return int
     */
    public function getChallengeGenerationsCount()
    {
        $count = 0;
        $questions = parent::getChallengeGenerations()->all();
        foreach ($questions as $question) {
            /** @var ChallengeHasQuestion $question */
        //    $q = $question->getQuestion()->one();
        //    /** @var \app\models\ar\Question $q */
        //    if ($q->question_type_id === QuestionType::TYPE_THREE_QUESTION) {
        //        $count += 3;
        //    } else {
        //        $count ++;
        //    }
        }

        return $count;
    }

    /**
     * @return int
     */
    public function getChallengeHasQuestionsCount()
    {
        $count = 0;
        $questions = parent::getChallengeHasQuestions()->orderBy(['position' => SORT_ASC])->all();
        foreach ($questions as $question) {
            /** @var ChallengeHasQuestion $question */
            $q = $question->getQuestion()->one();
            /** @var \app\models\ar\Question $q */
            if ($q->question_type_id === QuestionType::TYPE_THREE_QUESTION) {
                $count += 3;
            } else {
                $count ++;
            }
        }
        return $count;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this
            ->hasMany(Question::className(), ['id' => 'question_id'])
            ->viaTable('challenge_has_question', ['challenge_id' => 'id'], function ($query) {
                $query->orderBy(['position' => SORT_ASC]);
            });
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getSettings() {
        if ( is_null($this->_settings) ) {
            $this->_settings = $this->hasOne(ChallengeSettings::className(), ['challenge_id' => 'id'])
                ->inverseOf('challenge')
                ->one();
        }

        return $this->_settings;
    }

    /**
     * Get question generation mode
     * @return string
     */
    public function getMode()
    {
        $questions = $this->getChallengeHasQuestionsCount();
        $rules = $this->getChallengeGenerationsCount();

        if ($questions && $rules) {
            return self::MODE_DYNAMIC;
        } elseif ($questions) {
            return self::MODE_STATIC;
        } elseif ($rules) {
            return self::MODE_RANDOM;
        } else {
            return self::MODE_STATIC;
        }
    }

    /**
     * Set question generation mode
     * @param $mode
     * @param array $data
     */
    public function setMode($mode, $data = null)
    {
        ChallengeGeneration::deleteAll(['challenge_id' => $this->id]);
        ChallengeHasQuestion::deleteAll(['challenge_id' => $this->id]);

        switch ($mode) {
            case self::MODE_STATIC:
                Subset::save(
                    ChallengeHasQuestion::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
            case self::MODE_DYNAMIC:
                Subset::save(
                    ChallengeHasQuestion::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                Subset::save(
                    ChallengeGeneration::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
            case self::MODE_RANDOM:
                Subset::save(
                    ChallengeGeneration::className(),
                    $data,
                    ['challenge_id' => $this->id]
                );
                break;
        }
    }

    /**
     * Get questions count in this challenge
     * @return int
     */
    public function getQuestionsCount()
    {
        switch ($this->getMode()) {
            case self::MODE_STATIC:
            case self::MODE_DYNAMIC:
                return $this->getChallengeHasQuestionsCount();

            case self::MODE_RANDOM:
                $result = 0;
                foreach ($this->getChallengeGenerations() as $rule) {
                    try {
                        $result += $rule->question_count;
                    } catch (\Exception $e) {
                        $result += 0;
                    }
                }
                return $result;

            default:
                return 0;
        }
    }

    public function getAttemptsCount($user)
    {
        return $this->getAttempts()->where(['user_id' => is_object($user) ? $user->id : $user])->count();
    }

    public function getAttemptsElementsCount($element_id, $challenge_id, $challenge_element_id)
    {
        $attempts = [];
        if ($element_id == 1 && $challenge_element_id == 1) {
            $attempts = Attempt::find()->where(['challenge_id' => $challenge_id])->andWhere(['user_id' =>  Yii::$app->user->id])->all();
        }
        if ($element_id == 2 && $challenge_element_id == 2) {
            $attempts = Attempt::find()->where(['challenge_id' => $challenge_id])->andWhere(['user_id' =>  Yii::$app->user->id])->all();
        }
        return count($attempts);
    }

    public function getElementChallengesCount($course_id, $element_id){
        $challenges = Challenge::find()->where(['course_id' => $course_id])->andWhere(['element_id' => $element_id])->all();
        return count($challenges);
    }
    
    public function getChallengesByWeeks($courseChallenges) {
        $testByWeeks = [];
        foreach ($courseChallenges as $number => $challenge) {
            if ($challenge->week == $number) {
                $testByWeeks[$challenge->week][] = $challenge->id;
            }
        }
        return $testByWeeks;
    }

    public function getMarks($user_id, $challenge_id)
    {
        return Attempt::find()->select(['mark'])
            ->where(['user_id' => $user_id])
            ->andWhere(['challenge_id' => $challenge_id])
            ->all();
    }

    public function getAllChallengeAttempts($challenge_id)
    {
        return Attempt::find()->select(['id'])
            ->where(['challenge_id' => $challenge_id])
            ->all();
    }

    public function getAllChallengeMarks($challenge_id)
    {
        return Attempt::find()->select(['mark'])
            ->where(['challenge_id' => $challenge_id])
            ->all();
    }

    public function getCourseName($course_ids, $challenge_course_id)
    {
        $name = '';
        foreach($course_ids as $course_id) {
            if ($course_id->id == $challenge_course_id->course_id) {
                $name = $challenge_course_id->name;
                break;
            }
        }
        return $name;
    }

    public function getAllChallengeUsers($challenge_id)
    {
        return Attempt::find()
            ->select(['user_id'])
            ->where(['challenge_id' => $challenge_id])
            ->groupBy('user_id')
            ->all();
    }

    public function getUserById($id)
    {
        return User::find()->where(['id' => $id]);
    }

    public function getChallengeFood($id)
    {
        //$food_id = ChallengeFood::find()->select('food_id')->where(['challenge_id' => $id])->one();
        //$challengeFood = Food::find()->select('food_name')->where(['id' => $food_id])->one();

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeFood = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
        return $challengeFood;
    }

    public function getChallengeClean($id)
    {
        //$food_id = ChallengeFood::find()->select('food_id')->where(['challenge_id' => $id])->one();
        //$challengeFood = Food::find()->select('food_name')->where(['id' => $food_id])->one();

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeClean = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
        return $challengeClean;
    }

    public function getWebinar()
    {
        //$webinar = Webinar::findOne(Yii::$app->request->get('id'));
        //\yii\helpers\VarDumper::dump($webinar, 10, true);
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

    public function getAnswersFinish($data, $questionId, $questionTypeId, $answers, $question=null)
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
            case QuestionType::TYPE_ASSOC_TABLE:

                echo AnswerEditor::widget([
                    'name' => 'answer',
                    'question' => $question,
                    'answer' => $answers[$questionId],
                    'immediate_result' => '1',
                ]);
//                $number = 0;
//                for ($i = 0; $i < count(json_decode($answers[$questionId])); $i++) {
//                    $number++;
//                    foreach (json_decode($answers[$questionId])[$i] as $key => $item) {
//                        if ($key == 0){
//                            echo  '<strong>'.$number.'-я пара:</strong> <br>'.json_decode($data, true)['options'][$item].'<br><strong><=></strong><br>';
//                        } elseif ($key == 1){
//                            echo json_decode($data, true)['associations'][$item].'<br><br>';
//                        }
//                    }
//                }


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
            case QuestionType::TYPE_THREE_QUESTION:
                echo $answers[$questionId];
                break;
        }
    }

    public static function setScale($scaleType, $allLastChallengeQuestionsCost, $timeCorrectness, $lastAttempt)
    {

        if ($scaleType == 'ScaleFeed') {
            $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
            if (!$scale) {
                $scale = new ScaleFeed();
            }
            $lastTypeAttempt = Attempt::getFeedLastAttempt();
        }
        if ($scaleType == 'ScaleClean'){
            $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
            if (!$scale) {
                $scale = new ScaleClean();
            }
            $lastTypeAttempt = Attempt::getCleanLastAttempt();
        }
            // если шкала "Еды" ученика существует
        if ($scale) {
            //$lastTypeAttempt = Attempt::getFeedLastAttempt();

            // если имеется последний тест для Еды, то получаем последний тест для Еды
            if ($lastTypeAttempt) {
                $lastTypeAttemptFinishTime = $lastTypeAttempt->finish_time;
            } else {
                // если нет последнего теста, то просто вставляем текущее время
                $lastTypeAttemptFinishTime = date(time());
            }
            // получаем время окончания предыдущего теста
            $finishTime = Yii::$app->getFormatter()->asTimestamp($lastTypeAttemptFinishTime) - $timeCorrectness;
            // узнаём текущее время, простое число
            $time = time();
            // получаем изменение времени с момента окончания предыдущего теста до текущего момента
            $timeAfterLastTypeChallenge = $time - $finishTime;
            // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
            $roundTime = floor($timeAfterLastTypeChallenge / 60);
            // если в шкале на данный момент баллов меньше или равно 0 (такое логически не возможно), то прибавляем полученные за тест баллы и сохраняем

            if ($scale->points - $roundTime + $allLastChallengeQuestionsCost <= 0) {
                $scale->user_id = Yii::$app->user->id;
                $scale->points = $allLastChallengeQuestionsCost;
                $scale->last_time = $lastAttempt->finish_time;
                $scale->step = 0;
                $scale->save();
            }
            if ($scale->points - $roundTime + $allLastChallengeQuestionsCost > 0) {
                $scale->user_id = Yii::$app->user->id;
                $scale->points = $scale->points - $roundTime + $allLastChallengeQuestionsCost + 2;
                $scale->last_time = $lastAttempt->finish_time;
                $scale->step = 0;
                $scale->save();
            }
            if ($scale->points - $roundTime + $allLastChallengeQuestionsCost >= 100) {
                $scale->user_id = Yii::$app->user->id;
                $scale->points = 100;
                $scale->last_time = $lastAttempt->finish_time;
                $scale->step = 0;
                $scale->save();
            }

        } else {
                //$lastAttempt = Attempt::getFeedLastAttempt();
                $scale->user_id = Yii::$app->user->id;
                $scale->last_time = $lastAttempt->finish_time;
                $scale->points = $allLastChallengeQuestionsCost;
                $scale->step = 0;
                $scale->save();
            }
    }

}
