<?php

namespace app\controllers;

use app\helpers\ChallengeSession;
use app\helpers\ChallengeSummarizer;
use app\models\ar\ChallengeFood;
use app\models\ar\DifficultSubjects;
use app\models\ar\ElementsItem;
use app\models\ar\Food;
use app\models\ar\QuestionHasSubject;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\models\Attempt;
use app\models\Challenge;
use app\models\ChallengeHasQuestion;
use app\models\Question;
use app\models\Subject;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use kartik\markdown\Markdown;

/**
 * Class ChallengeController
 * @package app\controllers
 */
class ChallengeController extends Controller
{
    public $layout = 'metronic_sidebar';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'answer' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Challenges list
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Free challenges list
     * @return string
     */
    public function actionFree()
    {
        $challenges = Challenge::findFree()->all();

        if (count($challenges) == 1) {
            $challenge = reset($challenges);
            return $this->redirect(Url::to(['challenge/start', 'id' => $challenge->id]));
        }

        return $this->render('free', [
            'challenges' => $challenges
        ]);
    }

    /**
     * Start challenge
     * @param int $id Challenge Id
     * @param bool $confirm Confirm start
     * @return string|\yii\web\Response
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionStart($id = 0, $confirm = false)
    {
        $challenge = $this->getChallenge($id);

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeItem = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();

        if ($challenge->settings->autostart || $confirm) {

            $session = new ChallengeSession($challenge, Yii::$app->user->id);
            if ($session->start()) {
                $_SESSION['pre'] = '';
                return $this->redirect(Url::to(['challenge/progress', 'id' => $challenge->id]));
            } else {
                throw new HttpException(500);
            }

        } else {
            return $this->render('start', [
                'challenge' => $challenge,
                'challengeItem' => $challengeItem
            ]);
        }
    }

    /**
     * Finish challenge
     * @param int $id Challenge Id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionFinish($id = 0, $confirm = false)
    {
        $challengeElementsType = Challenge::find()->select('element_id')->where(['id' => $id])->one();

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeItem = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
        $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
        $lastCleanAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        if (!$session->isFinished()) {
            if ($confirm) {
                $session->finish();
            } else {
                return $this->render('finish_confirm', [
                    'challenge' => $challenge,
                    'challengeItem' => $challengeItem
                ]);
            }
        }

        if (count($session->getAnswers())) {
            $summary = ChallengeSummarizer::fromSession($session);
            if (!Yii::$app->user->isGuest) {
                $summary->saveAttempt();
            }
            //\yii\helpers\VarDumper::dump($challenge->id, 10, true);
            $lastChallenge = Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();

            //\yii\helpers\VarDumper::dump($lastChallenge->id + 1, 10, true);
            //$lastChallengeId = $lastChallenge->id + 1; //Attempt::find()->select(['challenge_id'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
           // \yii\helpers\VarDumper::dump($lastChallenge->id, 10, true);
           // \yii\helpers\VarDumper::dump($lastFeedAttempt->id, 10, true);
            $lastChallengeQuestions = ChallengeHasQuestion::find()->where(['challenge_id' => $challenge->id])->all();
            $allLastChallengeQuestionsCost = 0;
            foreach ($lastChallengeQuestions as $lastChallengeQuestion){
                $lastChallengeQuestionCost = Question::find()->select('cost')->where(['id' => $lastChallengeQuestion->question_id])->one();
                $allLastChallengeQuestionsCost += $lastChallengeQuestionCost->cost;
                //\yii\helpers\VarDumper::dump($lastChallengeQuestionCost->cost, 10, true);
            }
            $allLastChallengeQuestionsCost = ceil(($allLastChallengeQuestionsCost / 5) * intval($lastChallenge->mark));
            //\yii\helpers\VarDumper::dump($allLastChallengeQuestionsCost, 10, true);

            $testQuestions = $summary->getQuestions();
            $testResults = $summary->getCorrectness();

            $lastAttempt = Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
            //\yii\helpers\VarDumper::dump($lastAttempt, 10, true);
            if ($lastAttempt->points == 0) {

                foreach ($summary->answers as $realQuestionId => $answer) {
                    foreach ($testQuestions as $i => $question) {
                        if ($realQuestionId == $question['id']) {
                            $subject = QuestionHasSubject::find()->select(['subject_id'])->where(['question_id' => $question['id']])->one();
                            $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['subject_id' => $subject])->one();

                            $points = DifficultSubjects::find()->select(['points'])->where(['user_id' => Yii::$app->user->id])->andWhere(['subject_id' => $subject])->one();
                            if ($difficultSubjects) {
                                $difficultSubjects->user_id = Yii::$app->user->id;
                                $difficultSubjects->subject_id = $subject->subject_id;
                                if ($testResults[$question->id] == true) {
                                    $difficultSubjects->points = $points->points + 1;
                                } else {
                                    $difficultSubjects->points = $points->points - 1;
                                }
                                //$difficultSubjects->points = $points->points + 1;
                                // if ($difficultSubjects->points == 0) {

//                            }
                                $difficultSubjects->save();
                            } else {
                                $difficultSubjects = new DifficultSubjects();
                                $difficultSubjects->user_id = Yii::$app->user->id;
                                $difficultSubjects->subject_id = $subject->subject_id;
                                $difficultSubjects->points = 1;
                                $difficultSubjects->save();
                            }

                            // \yii\helpers\VarDumper::dump($subject->subject_id, 10, true);
                            // \yii\helpers\VarDumper::dump($question['id'], 10, true);
                            if ($testResults[$question->id] == true) {
                                //      \yii\helpers\VarDumper::dump($testResults[$question->id], 10, true);
                                // \yii\helpers\VarDumper::dump($difficultSubjects, 10, true);
                            }
                        }
                    }
                }

                if ($challengeElementsType->element_id == 2) {

                    // если шкала "Еды" ученика существует
                    if (ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                        // получаем шкалу "Еды" ученика
                        $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
                        // получаем время окончания предыдущего теста
                        $finishTime = Yii::$app->getFormatter()->asTimestamp($lastCleanAttempt->finish_time);
                        // узнаём текущее время и переводим его в простое число
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                        $timeAfterLastFeedChallenge = $time - $finishTime;
                        // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                        $roundTime = ceil($timeAfterLastFeedChallenge/100) - 1;
                        // если в шкале на данный момент баллов меньше или равно 0 (такое логически не возможно), то прибавляем полученные за тест баллы и сохраняем
                        if ($scale->points <= 0){
                            $scale->user_id = Yii::$app->user->id;
                            $scale->points = $allLastChallengeQuestionsCost;
                            $scale->step = 0;
                            $scale->save();
                        }
                        // если в шкале баллов больше 0
                        if ($scale->points > 0){
                            // записываем ID ученика
                            $scale->user_id = Yii::$app->user->id;
                            // если разница между баллами и прошедшим временем в баллах равна или меньше 0, то записываем полученные за последний тест баллы
                            if ($scale->points - $roundTime <= 0) {
                                $scale->points = $allLastChallengeQuestionsCost;
                            }
                            // если разница между баллами в шкале и баллами прошедшего времени больше 0, то баллы в шкале делаем такими, каковы они на текущий момент
                            else {
                                $scale->points = $allLastChallengeQuestionsCost + $scale->points - $roundTime;
                            }
                            $scale->step = 0;
                            $scale->save();
                        }
                        $lastAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                    } else {
                        $scale = new ScaleClean();
                        $lastAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                        $scale->user_id = Yii::$app->user->id;
                        $scale->last_time = $lastAttempt->finish_time;
                        $scale->points = $allLastChallengeQuestionsCost;
                        $scale->step = 0;
                        $scale->save();
                    }
                }
                if ($challengeElementsType->element_id == 1) {
                    
                    // если шкала "Еды" ученика существует
                    if (ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                        // получаем шкалу "Еды" ученика
                        $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
                        // получаем время окончания предыдущего теста
                        $finishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttempt->finish_time);
                        // узнаём текущее время и переводим его в простое число
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                        $timeAfterLastFeedChallenge = $time - $finishTime;
                        // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                        $roundTime = ceil($timeAfterLastFeedChallenge/100) - 1;
                        // если в шкале на данный момент баллов меньше или равно 0 (такое логически не возможно), то прибавляем полученные за тест баллы и сохраняем 
                        if ($scale->points <= 0){
                            $scale->user_id = Yii::$app->user->id;
                            $scale->points = $allLastChallengeQuestionsCost;
                            $scale->step = 0;
                            $scale->save();
                        }
                        // если в шкале баллов больше 0
                        if ($scale->points > 0){
                            // записываем ID ученика
                            $scale->user_id = Yii::$app->user->id;
                            // если разница между баллами и прошедшим временем в баллах равна или меньше 0, то записываем полученные за последний тест баллы
                            if ($scale->points - $roundTime <= 0) {
                                $scale->points = $allLastChallengeQuestionsCost;
                            } 
                            // если разница между баллами в шкале и баллами прошедшего времени больше 0, то баллы в шкале делаем такими, каковы они на текущий момент
                            else {
                                $scale->points = $allLastChallengeQuestionsCost + $scale->points - $roundTime;
                            }
                                $scale->step = 0;
                                $scale->save();
                        }

                        $lastAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                    } else {
                        $scale = new ScaleFeed();
                        $lastAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                        $scale->user_id = Yii::$app->user->id;
                        $scale->last_time = $lastAttempt->finish_time;
                        $scale->points = $allLastChallengeQuestionsCost;
                        $scale->step = 0;
                        $scale->save();
                    }
                }

            }

            $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->all();
            $allSubjects = Subject::find()->all();

            return $this->render('finish', [
                'challenge' => $challenge,
                'summary' => $summary,
                'challengeItem' => $challengeItem,
                'challengeElementsType' => $challengeElementsType,
                'testQuestions' => $testQuestions,
                'difficultSubjects' => $difficultSubjects,
                'allSubjects' => $allSubjects
              //  'testResults' => $testResults
            ]);
        } else {
            // It looks like session wasn't even started
            return $this->redirect(Url::to(['challenge/start', 'id' => $challenge->id]));
        }
    }

    /**
     * Challenge in progress
     * @param int $id Challenge Id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProgress($id = 0)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        //$food_id = ChallengeFood::find()->select('food_id')->where(['challenge_id' => $id])->one();
        //$challengeFood = Food::find()->select('food_name')->where(['id' => $food_id])->one();

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeItem = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();

        if ($session->isFinished()) {
            return $this->redirect(Url::to(['challenge/finish', 'id' => $challenge->id]));
        }

        return $this->render('progress', [
            'session' => $session,
            'challenge' => $challenge,
            'challengeItem' => $challengeItem
        ]);
    }

    /**
     * Submit answer to current challenge question
     * @param int $id Challenge Id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionAnswer($id = 0)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        if (!$session->isFinished()) {
            if (empty($_SESSION['pre'])) {
                $pre = true;
            }
            $session->answer(\Yii::$app->request->post('answer'), $pre);
        }

        if ($session->isFinished()) {
            return $this->redirect(Url::to(['challenge/finish', 'id' => $challenge->id]));
        } else {
            return $this->redirect(Url::to(['challenge/progress', 'id' => $challenge->id]));
        }
    }

    public function actionContinue($id = 0)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        if (!$session->isFinished()) {
            if (!empty($_SESSION['pre'])) {
                $pre = false;
            }
            $session->answer(\Yii::$app->request->post('answer'), $pre);
        }

        if ($session->isFinished()) {
            return $this->redirect(Url::to(['challenge/finish', 'id' => $challenge->id]));
        } else {
            return $this->redirect(Url::to(['challenge/progress', 'id' => $challenge->id]));
        }
    }

    /**
     * Get question hint
     * @param int $id
     */
    public function actionHint($id = 0)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $session->hint();
    }

    /**
     * Skip current question
     * @param int $id
     */
    public function actionSkip($id = 0)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        $session->skip();

        return $this->redirect(Url::to(['challenge/progress', 'id' => $challenge->id]));
    }

    /**
     * Get Challenge by id
     * @param $id
     * @return Challenge
     * @throws NotFoundHttpException
     */
    protected function getChallenge($id)
    {
        if ($challenge = Challenge::findOne($id)) {
            return $challenge;
        } else {
            throw new NotFoundHttpException(Yii::t('challenge', 'Not found'));
        }
    }

}
