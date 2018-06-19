<?php

namespace app\controllers;

use app\helpers\ChallengeSession;
use app\helpers\ChallengeSummarizer;
use app\models\ar\ChallengeFood;
use app\models\ar\ChallengesWeeks;
use app\models\ar\DifficultSubjects;
use app\models\ar\ElementsItem;
use app\models\ar\Food;
use app\models\ar\QuestionHasSubject;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\models\ar\ScaleLearn;
use app\models\ar\UserPoints;
use app\models\Attempt;
use app\models\Challenge;
use app\models\ChallengeHasQuestion;
use app\models\Course;
use app\models\Event;
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


        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);
        $course = Course::find()->where(['id' => $challenge->course_id])->one();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));

        $week = 0;
        $course = Course::findSubscribed(Yii::$app->user->id)->where(['id' => $challenge->course_id])->one();
        if (Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one()) {
            $event = Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one();
            //\yii\helpers\VarDumper::dump($events, 10, true);
            $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
            $time = Yii::$app->getFormatter()->asTimestamp(time());
            // получаем изменение времени с момента начала курса до текущего момента
            $timeAfterCourseStart = $time - $courseStartTime;
            $weekTime = 604800;
            $week = ceil($timeAfterCourseStart / $weekTime);
            //\yii\helpers\VarDumper::dump($week, 10, true);
            }


        if ($challenge->week == $week){
            \yii\helpers\VarDumper::dump($challenge->week, 10, true);
        }
//        }
        //\yii\helpers\VarDumper::dump($allEvents, 10, true);
        //\yii\helpers\VarDumper::dump($allEvents, 10, true);
        // die();

    ////   if ($allEvents) {

    //       // цикл с разбором всех событий
    //       foreach ($allEvents as $keyEvent => $event) {

    //           // цикл с перебором всех событий конкретного курса и выбором события "Начало"
    //           for ($i = 0; $i < count($event); $i++) {
    //               // если у события курса название "Начало", то...
    //               if ($event[$i]->title == 'Начало') {

        // получим время начала курса
        //$courseStartTime = Yii::$app->getFormatter()->asTimestamp($course->start_time);
        // узнаём текущее время и переводим его в простое число


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
            if (!Yii::$app->user->isGuest) {
                $lastChallenge = Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();

                //\yii\helpers\VarDumper::dump($lastChallenge->id + 1, 10, true);
                //$lastChallengeId = $lastChallenge->id + 1; //Attempt::find()->select(['challenge_id'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
                // \yii\helpers\VarDumper::dump($lastChallenge->id, 10, true);
                // \yii\helpers\VarDumper::dump($lastFeedAttempt->id, 10, true);
                $lastChallengeQuestions = ChallengeHasQuestion::find()->where(['challenge_id' => $challenge->id])->all();
                $allLastChallengeQuestionsCost = 0;
                foreach ($lastChallengeQuestions as $lastChallengeQuestion) {
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

                            //\yii\helpers\VarDumper::dump($testAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one(), 10, true);
                            //\yii\helpers\VarDumper::dump(Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->andWhere(['attempt.points' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one(), 10, true);

                            // получаем шкалу "Уборки" ученика
                            $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
                            if (Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->andWhere(['attempt.points' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one()) {
                                $lastCleanAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->andWhere(['attempt.points' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                                $lastCleanAttemptFinishTime = $lastCleanAttempt->finish_time;
                            } else {
                                // если нет последнего теста, то просто вставляем текущее время
                                $lastCleanAttemptFinishTime = time();
                            }
                            // получаем время окончания предыдущего теста
                            $finishTime = Yii::$app->getFormatter()->asTimestamp($lastCleanAttemptFinishTime);
                            // узнаём текущее время и переводим его в простое число
                            $time = Yii::$app->getFormatter()->asTimestamp(time());
                            // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                            $timeAfterLastCleanChallengeTest = $time - $finishTime;
                            // if ($timeAfterLastCleanChallengeTest >= 10000){
                            //     $timeAfterLastCleanChallengeTest = 1000;
                            // }
                            // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                            $roundTime = ceil($timeAfterLastCleanChallengeTest / 100) - 1;

                            print $roundTime;

                            $scalePoints = $scale->points;
                            // если в шкале на данный момент баллов меньше или равно 0 (такое логически не возможно), то прибавляем полученные за тест баллы и сохраняем
                            if ($scalePoints <= 0) {
                                $scale->user_id = Yii::$app->user->id;
                                $scale->points = $allLastChallengeQuestionsCost;
                                $scale->step = 0;
                                $scale->save();
                            }
                            // если в шкале баллов больше 0
                            if ($scalePoints > 0) {
                                // записываем ID ученика
                                $scale->user_id = Yii::$app->user->id;
                                // если разница между баллами в шкале и баллами прошедшего времени больше 0, то баллы в шкале делаем такими, каковы они на текущий момент
                                if ($scalePoints - $roundTime > 0) {
                                    print '<br>$scale->points - $roundTime > 0 (или всё по-прежнему не работает, если прошло много времени, а тест перезаписал имевшиеся баллы в шкале)';
                                    $scale->points = $allLastChallengeQuestionsCost + $scalePoints - $roundTime;
                                }
                                // если разница между баллами и прошедшим временем в баллах равна или меньше 0, то записываем полученные за последний тест баллы
                                if ($scalePoints - $roundTime <= 0) {
                                    print '$scale->points - $roundTime <= 0';
                                    $scale->points = $allLastChallengeQuestionsCost;
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

                        //\yii\helpers\VarDumper::dump($lastAttempt, 10, true);

                        //$challengesWeeks = $challengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();;

                    }
                    if ($challengeElementsType->element_id == 1) {

                        // если шкала "Еды" ученика существует
                        if (ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                            // получаем шкалу "Еды" ученика
                            $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
                            // если имеется последний тест для Еды, то получаем последний тест для Еды
                            if (Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->andWhere(['attempt.points' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one()) {
                                $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->andWhere(['attempt.points' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                                $lastFeedAttemptFinishTime = $lastFeedAttempt->finish_time;
                            } else {
                                // если нет последнего теста, то просто вставляем текущее время
                                $lastFeedAttemptFinishTime = time();
                            }

                            // получаем время окончания предыдущего теста
                            $finishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttemptFinishTime);
                            // узнаём текущее время и переводим его в простое число
                            $time = Yii::$app->getFormatter()->asTimestamp(time());
                            // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                            $timeAfterLastFeedChallenge = $time - $finishTime;
                            // если после крайнего теста прошло больше 10000 секунд, то
                            //   if ($timeAfterLastFeedChallenge >= 10000){
                            //       $timeAfterLastFeedChallenge = 1000;
                            //   }
                            // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                            $roundTime = ceil($timeAfterLastFeedChallenge / 100) - 1;
                            // если в шкале на данный момент баллов меньше или равно 0 (такое логически не возможно), то прибавляем полученные за тест баллы и сохраняем
                            if ($scale->points <= 0) {
                                $scale->user_id = Yii::$app->user->id;
                                $scale->points = $allLastChallengeQuestionsCost;
                                $scale->step = 0;
                                $scale->save();
                            }
                            // если в шкале баллов больше 0
                            if ($scale->points > 0) {
                                print '$scale->points > 0';
                                // записываем ID ученика
                                $scale->user_id = Yii::$app->user->id;
                                // если разница между баллами и прошедшим временем в баллах равна или меньше 0, то записываем полученные за последний тест баллы
                                if ($scale->points - $roundTime <= 0) {
                                    print '$scale->points - $roundTime <= 0';
                                    $scale->points = $allLastChallengeQuestionsCost;
                                } // если разница между баллами в шкале и баллами прошедшего времени больше 0, то баллы в шкале делаем такими, каковы они на текущий момент
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

                    $challengeNew = Challenge::find()->where(['id' => $id])->one();

                    $challengesWeeks = ChallengesWeeks::find()->where(['course_id' => $challengeNew->course_id])->andWhere(['week_id' => $challengeNew->week])->andWhere(['element_id' => $challengeNew->element_id])->andWhere(['user_id' => Yii::$app->user->id])->one();

                    // andWhere(['element_id' => 2])->
                    if ($challengesWeeks) {
                        if ($challengesWeeks->challenges) {
                            $challengesIds = json_decode($challengesWeeks->challenges, true);
                            foreach ($challengesIds as $challengesId => $flag) {
                                if ($challengesId == $id) {
                                    $challengesIds[$challengesId] = 1;
                                    $challengesWeeks->course_id = $challengeNew->course_id;
                                    $challengesWeeks->week_id = $challengeNew->week;
                                    $challengesWeeks->user_id = Yii::$app->user->id;
                                    $challengesWeeks->challenges = json_encode($challengesIds);
                                    $challengesWeeks->element_id = $challengeNew->element_id;
                                    $challengesWeeks->save();
                                }
                            }
                        }
                    }

                    $challengeNew = Challenge::find()->where(['id' => $id])->one();

                    if ($challengeNew->week == $week) {
                        if (ScaleLearn::find()->where(['course_id' => $challengeNew->course_id])->andWhere(['week_id' => $challengeNew->week])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                            $learn = ScaleLearn::find()->where(['course_id' => $challengeNew->course_id])->andWhere(['week_id' => $challengeNew->week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                            //$learn = ScaleLearn::find()->where(['course_id' => $challengeNew->course_id])->andWhere(['week_id' => $challengeNew->week])->andWhere(['element_id' => $challengeNew->element_id])->andWhere(['user_id' => Yii::$app->user->id])->one();
                            //\yii\helpers\VarDumper::dump($days, 10, true);
                            //\yii\helpers\VarDumper::dump($learn, 10, true);
                            foreach ($days as $keyDay => $day) {
                                $data = json_decode($learn->$day, true);
                                if ($day == $currentDay && $currentDay == 'monday') {
                                    print 'MONDAY' . $keyDay;
                                    //$data = json_decode($learn->$day, true);
                                    //print $data;
                                    //\yii\helpers\VarDumper::dump($data, 10, true);
                                    if ($challengeNew->element_id == 1) {
                                        print 'Feed';
                                        $data['feed'] = 1;
                                        //\yii\helpers\VarDumper::dump($data, 10, true);
                                    }
                                    if ($challengeNew->element_id == 2) {
                                        print 'Clean';
                                        $data['clean'] = 1;
                                        //\yii\helpers\VarDumper::dump($data, 10, true);
                                    }
                                    $learn->$day = json_encode($data);
                                    $learn->save();
                                }
                                if ($day == $currentDay && $currentDay != 'monday' && $data['feed'] == 0) {
                                    //$data = json_decode($learn->$day, true);
                                    if ($challengeNew->element_id == 1) {
                                        $data['feed'] = 1;
                                    }
                                    if ($challengeNew->element_id == 2) {
                                        $data['clean'] = 1;
                                    }
                                    $learn->$day = json_encode($data);
                                    $learn->save();
                                }
                                if ($day == $currentDay && $currentDay != 'monday' && $data['feed'] == 1) {
                                    for ($i = 0; $i <= $keyDay; $i++) {
                                        $day = $days[$i];
                                        $data = json_decode($learn->$day, true);
                                        //print $i . ' - день по счёту<br>';
                                        Yii::$app->session->setFlash('success', "Просто должно всё работать и появиться оповещение, если день не понедельник!");
                                        if ($challengeNew->element_id == 1 && $data['feed'] == 0) {
                                            Yii::$app->session->setFlash('success', "Кошка не ела в предыдущий день (" . Yii::t('days', $day) . "), теперь она поела и за него!");
                                            $data['feed'] = 1;
                                            break;
                                        }
                                        if ($challengeNew->element_id == 2 && $data['clean'] == 0) {
                                            Yii::$app->session->setFlash('success', "Кошка не делала уборку в предыдущий день (" . Yii::t('days', $day) . "), теперь она поубиралась и за него!");
                                            $data['clean'] = 1;
                                            break;
                                        }
                                    }
                                    $learn->$day = json_encode($data);
                                    $learn->save();
                                }
                            }
                        } else {
                            $learn = new ScaleLearn();
                            $learn->user_id = Yii::$app->user->id;
                            $learn->course_id = $challengeNew->course_id;
                            $learn->week_id = $challengeNew->week;
                            foreach ($days as $key => $day) {
                                if ($day == $currentDay) {
                                    if ($challengeNew->element_id == 1) {
                                        $learn->$currentDay = json_encode(['feed' => 1, 'clean' => 0]);
                                    }
                                    if ($challengeNew->element_id == 2) {
                                        $learn->$currentDay = json_encode(['feed' => 0, 'clean' => 1]);
                                    }
                                    unset($days[$key]);
                                }
                            }

                            foreach ($days as $day) {
                                $learn->$day = json_encode(['feed' => 0, 'clean' => 0]);
                            }
                            //       $learn->monday = 'Monday';
                            //       $learn->tuesday = 'Tuesday';
                            //       $learn->wednesday = 'Wednesday';
                            //       $learn->thursday = 'Thursday';
                            //       $learn->friday = 'Friday';
                            //       $learn->saturday = 'Saturday';
                            //       $learn->sunday = 'Sunday';

                            $learn->save();
                        }
                    }
                    if ($challenge->week < $week) {
                        print 'Неделя у теста меньше текущей недели';

                        $test = Event::find()->where(['course_id' => $challenge->course_id])->all();
                        if ($test) {
                            $regexp = "/(тест)([0-9]*)/ui";
                            $match = [];
                            foreach ($test as $key => $oneTest) {
                                if (preg_match($regexp, $oneTest->title, $match[$key])) {
                                } else {
                                    unset($match[$key]);
                                }
                            }
                            //\yii\helpers\VarDumper::dump($match, 10, true);

                            foreach ($match as $key => $oneMatch) {
                                print $oneMatch[2];
                                if ($oneMatch[2] == $challenge->id) {
                                    // print 'В курсе указан тот же тест, который был выполнен';
                                    $learn = ScaleLearn::find()->where(['course_id' => $challenge->course_id])->andWhere(['week_id' => $challenge->week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                    //\yii\helpers\VarDumper::dump($learn, 10, true);
                                    foreach ($days as $day) {
                                        $data = json_decode($learn->$day, true);
                                        $data['feed'] = 1;
                                        $data['clean'] = 1;
                                        $learn->$day = json_encode($data);
                                        $learn->save();
                                    }
                                    Yii::$app->session->setFlash('success', "Ура, сделан общий тест № " . $challenge->id . " за целую прошедшую неделю № " . $challenge->week . "!");
                                }
                            }
                        }
                    }
                    if (UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => $challengeElementsType->element_id])->one()) {
                        $points = UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => $challengeElementsType->element_id])->one();
                        \yii\helpers\VarDumper::dump($points, 10, true);
                    } else {
                        $points = new UserPoints();
                        $points->user_id = Yii::$app->user->id;
                        $points->course_id = $course->id;
                        $points->element_id = $challengeElementsType->element_id;
                        $points->points = $allLastChallengeQuestionsCost;
                        $points->save();
                    }
                }
            }

            if (Yii::$app->user->isGuest) {
                $testQuestions = $summary->getQuestions();
                $testResults = $summary->getCorrectness();
                Yii::$app->session->setFlash('success', "Ура, твой первый выполненный тест! Но лучше зарегистрируйся - так все результаты будут сохраняться!");
            }


            //\yii\helpers\VarDumper::dump($challenge->challenge_type_id, 10, true);

            //$challengeNew = Challenge::find()->where(['id' => $id])->one();
            //$idTest = 'id';
            //\yii\helpers\VarDumper::dump($challengeNew->$idTest, 10, true);
            //\yii\helpers\VarDumper::dump(date("l"), 10, true);
            //if (strtolower(date("l")) == 'thursday') {
            //    print 'lalala';
            //}
            //$day = strtolower(date("l"));

            //\yii\helpers\VarDumper::dump(strtolower(date("l")), 10, true);



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
