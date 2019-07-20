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
use app\models\SavedResults;
use app\models\Subject;
use app\models\WebinarAnswers;
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
                $_SESSION['preview_answer'] = '';
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
        $timeCorrectness = 60 * 60 * 3;
        $challengeElementsType = Challenge::find()->select('element_id')->where(['id' => $id])->one();
        //date_default_timezone_set('Europe/Moscow');
        //date_default_timezone_set('Etc/GMT+3');
        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeItem = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);
        //$course = Course::find()->where(['id' => $challenge->course_id])->one();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));
        $week = 0;
        $course = Course::findSubscribed(Yii::$app->user->id)->where(['id' => $challenge->course_id])->one();
        $event = Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one();

        if ($event) {
            $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
            $time = Yii::$app->getFormatter()->asTimestamp(time());
            // получаем изменение времени с момента начала курса до текущего момента
            $timeAfterCourseStart = $time - $courseStartTime;
            $weekTime = 604800;
            $week = ceil($timeAfterCourseStart / $weekTime);
        }

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

        if (!count($session->getAnswers())) {
            // It looks like session wasn't even started
            return $this->redirect(Url::to(['challenge/start', 'id' => $challenge->id]));
        }

        $summary = ChallengeSummarizer::fromSession($session);
        if (!Yii::$app->user->isGuest) {
            $summary->saveAttempt();
            $lastAttempt = Attempt::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy('id DESC')
                ->one();
            $lastChallengeQuestions = ChallengeHasQuestion::find()->where(['challenge_id' => $challenge->id])->all();
            $allLastChallengeQuestionsCost = 0;
            foreach ($lastChallengeQuestions as $lastChallengeQuestion) {
                $lastChallengeQuestionCost = Question::find()
                    ->select('cost')
                    ->where(['id' => $lastChallengeQuestion->question_id])
                    ->one();
                $allLastChallengeQuestionsCost += $lastChallengeQuestionCost->cost;
                //\yii\helpers\VarDumper::dump($lastChallengeQuestionCost->cost, 10, true);
            }
            $allLastChallengeQuestionsCost = ceil(($allLastChallengeQuestionsCost / 5) * intval($lastAttempt->mark));
            //\yii\helpers\VarDumper::dump($allLastChallengeQuestionsCost, 10, true);

            $testQuestions = $summary->getQuestions();
            $testResults = $summary->getCorrectness();

            if ($lastAttempt->points == 0) {
                foreach ($summary->answers as $realQuestionId => $answer) {
                    foreach ($testQuestions as $i => $question) {
                        if ($realQuestionId == $question['id']) {
                            $subject = QuestionHasSubject::find()
                                ->select(['subject_id'])
                                ->where(['question_id' => $question['id']])
                                ->one();
                            $difficultSubjects = DifficultSubjects::find()
                                ->where(['user_id' => Yii::$app->user->id])
                                ->andWhere(['subject_id' => $subject])
                                ->one();

                            if ($difficultSubjects) {
                                if ($testResults[$question->id]) {
                                    $difficultSubjects->points += 1;
                                } else {
                                    $difficultSubjects->points -= 1;
                                }
                            } else {
                                $difficultSubjects = new DifficultSubjects();
                                $difficultSubjects->points = 1;
                            }
                            $difficultSubjects->user_id = Yii::$app->user->id;
                            $difficultSubjects->subject_id = $subject->subject_id;
                            $difficultSubjects->save();
                        }
                    }
                }

                if ($challengeElementsType->element_id == 1) {
                    // получаем шкалу "Еды" ученика
                    $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
                    // если шкала "Еды" ученика существует
                    if ($scale) {
                        $lastFeedAttempt = Attempt::getFeedLastAttempt(1);

                        // если имеется последний тест для Еды, то получаем последний тест для Еды
                        if ($lastFeedAttempt) {
                            $lastFeedAttemptFinishTime = $lastFeedAttempt->finish_time;
                        } else {
                            // если нет последнего теста, то просто вставляем текущее время
                            $lastFeedAttemptFinishTime = date(time());
                        }
                        // получаем время окончания предыдущего теста
                        $finishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttemptFinishTime) - $timeCorrectness;
                        // узнаём текущее время, простое число
                        $time = time();
                        // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                        $timeAfterLastFeedChallenge = $time - $finishTime;
                        // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                        $roundTime = ceil($timeAfterLastFeedChallenge / 60) - 1;
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
                            $scale->points = $scale->points - $roundTime + $allLastChallengeQuestionsCost;
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
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                    } else {
                        $scale = new ScaleFeed();
                        //$lastAttempt = Attempt::getFeedLastAttempt();
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                        $scale->user_id = Yii::$app->user->id;
                        $scale->last_time = $lastAttempt->finish_time;
                        $scale->points = $allLastChallengeQuestionsCost;
                        $scale->step = 0;
                        $scale->save();
                    }
                }

                if ($challengeElementsType->element_id == 2) {
                    // получаем шкалу "Уборки" ученика
                    $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
                    // если шкала "Уборки" ученика существует
                    if ($scale) {
                        $lastCleanAttempt = Attempt::getCleanLastAttempt(1);

                        // если имеется последний тест для Уборки, то получаем последний тест для Уборки
                        if ($lastCleanAttempt) {
                            $lastCleanAttemptFinishTime = $lastCleanAttempt->finish_time;
                        } else {
                            // если нет последнего теста, то просто вставляем текущее время
                            $lastCleanAttemptFinishTime = date(time());
                        }
                        // получаем время окончания предыдущего теста
                        $finishTime = Yii::$app->getFormatter()->asTimestamp($lastCleanAttemptFinishTime) - $timeCorrectness;
                        // узнаём текущее время, простое число
                        $time = time();
                        // получаем изменение времени с момента окончания предыдущего теста до текущего момента
                        $timeAfterLastCleanChallenge = $time - $finishTime;
                        // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                        $roundTime = ceil($timeAfterLastCleanChallenge / 60) - 1;
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
                            $scale->points = $scale->points - $roundTime + $allLastChallengeQuestionsCost;
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
                        $lastAttempt->points = 1;
                        $lastAttempt->save();
                    } else {
                        $scale = new ScaleClean();
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
                $challengesWeeks = ChallengesWeeks::find()
                    ->where(['course_id' => $challengeNew->course_id])
                    ->andWhere(['week_id' => $challengeNew->week])
                    ->andWhere(['element_id' => $challengeNew->element_id])
                    ->andWhere(['user_id' => Yii::$app->user->id])->one();
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
                    $learn = ScaleLearn::find()
                        ->where(['course_id' => $challengeNew->course_id])
                        ->andWhere(['week_id' => $challengeNew->week])
                        ->andWhere(['user_id' => Yii::$app->user->id])->one();
                    if ($learn) {
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
                                    Yii::$app->session->setFlash(
                                        'success',
                                        "Просто должно всё работать и появиться оповещение, если день не понедельник!"
                                    );
                                    if ($challengeNew->element_id == 1 && $data['feed'] == 0) {
                                        Yii::$app->session->setFlash(
                                            'success',
                                            "Кошка не ела в предыдущий день (" . Yii::t('days', $day) . "), теперь она поела и за него!"
                                        );
                                        $data['feed'] = 1;
                                        break;
                                    }
                                    if ($challengeNew->element_id == 2 && $data['clean'] == 0) {
                                        Yii::$app->session->setFlash(
                                            'success',
                                            "Кошка не делала уборку в предыдущий день (" . Yii::t('days', $day) . "), теперь она поубиралась и за него!"
                                        );
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
                                $learn = ScaleLearn::find()
                                    ->where(['course_id' => $challenge->course_id])
                                    ->andWhere(['week_id' => $challenge->week])
                                    ->andWhere(['user_id' => Yii::$app->user->id])->one();
                                //\yii\helpers\VarDumper::dump($learn, 10, true);
                                foreach ($days as $day) {
                                    $data = json_decode($learn->$day, true);
                                    $data['feed'] = 1;
                                    $data['clean'] = 1;
                                    $learn->$day = json_encode($data);
                                    $learn->save();
                                }
                                Yii::$app->session->setFlash(
                                    'success',
                                    "Ура, сделан общий тест № " . $challenge->id . " за целую прошедшую неделю № " . $challenge->week . "!"
                                );
                            }
                        }
                    }
                }

                $points = UserPoints::find()
                    ->where(['user_id' => Yii::$app->user->id])
                    ->andWhere(['course_id' => $course->id])
                    ->andWhere(['element_id' => $challengeElementsType->element_id])->one();
                if (!$points) {
                    $points = new UserPoints();
                }
                $points->points += $allLastChallengeQuestionsCost;
                $points->element_id = $challengeElementsType->element_id;
                $points->user_id = Yii::$app->user->id;
                $points->course_id = $course->id;
                $points->save();

                foreach ($testResults as $testId => $testResult) {
                    $question = Question::find()->where(['id' => $testId])->one();
                    if ($testResult) {
                        if (is_string($testResult)) {
                            print $testResult;
                            $regexpRight = "/(\[1,1,1\])/ui";
                            $wrongMatch = [];
                            if (preg_match($regexpRight, $testResult, $wrongMatch)) {
                                // print $testId . ' успешная строка с ответом!<br>';
                                $question->right_points += 1;
                            } else {
                                //print $testId . ' неуспешная строка с ответом...<br>';
                                $question->wrong_points += 1;
                            }
                        } else {
                            //print $testId . ' успешен!<br>';
                            //\yii\helpers\VarDumper::dump($question->right_points, 10, true);
                            $question->right_points += 1;
                        }
                    } else {
                        //print $testId . ' не успешен...<br>';
                        //\yii\helpers\VarDumper::dump($question->wrong_points, 10, true);
                        $question->wrong_points += 1;
                    }
                    $question->save();
                }

//                $questions = $summary->getQuestions();
//                $answerOnFinish = $summary->getAnswersFinish($questions[0]->data, $questions[0]->id, $questions[0]->question_type_id, $summary->answers, $questions[0]);
//                $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
//                $challengeItem = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
//                $results = $summary->getCorrectness();
//                $hints = $summary->getHints();
//                $mark = intval($summary->getMark());
//                $allPoints = $summary->getAllPoints($newQuestions, $points)['allPoints'];
//                $numberOfPoints = $summary->getAllPoints($newQuestions, $points)['numberOfPoints'];
//                $challengeTime = round($summary->getTime() / 60);
                $points = $summary->getPoints();
                $newQuestions = $summary->getQuestions();
                if ($challenge->challenge_type_id == 3 && $challenge->week <= $week) {
                    $webinarAnswers = new WebinarAnswers();
                    $webinarAnswers->user_id = Yii::$app->user->id;
                    $webinarAnswers->webinar_exercise_id = $challenge->exercise_number;
                    $webinarAnswers->challenge_id = $challenge->id;
                    $webinarAnswers->answers = json_encode($summary->answers);
                    $webinarAnswers->hints = json_encode($summary->getHints());
                    $webinarAnswers->result = json_encode($summary->getCorrectness());
                    $webinarAnswers->mark = intval($summary->getMark());
                    //$webinarAnswers->time = intval($summary->getMark());
                    $webinarAnswers->all_user_points = $summary->getAllPoints($newQuestions, $points)['allPoints'];
                    $webinarAnswers->points = json_encode($summary->getPoints());
                    $webinarAnswers->all_points = $summary->getAllPoints($newQuestions, $points)['numberOfPoints'];
                    $webinarAnswers->time = round($summary->getTime() / 60);
                    $webinarAnswers->save();
                }

                $_SESSION['course_id'] = $challenge->course_id;
                $_SESSION['exercise_id'] = $challenge->exercise_number;
                $_SESSION['challenge_id'] = $challenge->id;
                $_SESSION['answers'] = json_encode($summary->answers);
                $_SESSION['hints'] = json_encode($summary->getHints());
                $_SESSION['result'] = json_encode($summary->getCorrectness());
                $_SESSION['mark'] = intval($summary->getMark());
                $_SESSION['all_user_points'] = $summary->getAllPoints($newQuestions, $points)['allPoints'];
                $_SESSION['points'] = json_encode($summary->getPoints());
                $_SESSION['all_points'] = $summary->getAllPoints($newQuestions, $points)['numberOfPoints'];
                $_SESSION['time'] = round($summary->getTime() / 60);
            }

        } else {
            $testQuestions = $summary->getQuestions();
            $testResults = $summary->getCorrectness();
            Yii::$app->session->setFlash(
                'success',
                "Ура, твой первый выполненный тест! Но лучше зарегистрируйся - так все результаты будут сохраняться!"
            );
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
            'allSubjects' => $allSubjects,
            'testResults' => $testResults,
    //       'results' => $results,
    //       'points' => $points,
    //       'hints' => $hints,
    //       'mark' => $mark,
    //       'textMark' => $textMark,
    //       'emoticon' => $emoticon,
    //       'allPoints' => $allPoints,
    //       'numberOfPoints' => $numberOfPoints,
    //       'challengeTime' => $challengeTime,
    //       'webinarAnswers' => $webinarAnswers
        ]);
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
        $immediate_result = $challenge->getSettings()->getAttribute('immediate_result');
        $session->answer(\Yii::$app->request->post('answer'), $immediate_result);

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
        $preview = true;

        if (!$session->isFinished()) {
            if (!empty($_SESSION['preview_answer'])) {
                $preview = false;
            }
            $session->answer(\Yii::$app->request->post('answer'), $preview);
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
     * @param int $num
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionHint($id = 0, $num = null)
    {
        $challenge = $this->getChallenge($id);
        $session = new ChallengeSession($challenge, Yii::$app->user->id);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $session->hint($num);
    }

    /**
     * Skip current question
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
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
    
    public function actionSave($id){
        print 'USPESHEN SAVE';
        //\yii\helpers\VarDumper::dump($_SESSION, 10, true);
        //$data = '';
        // строка, которую будем записывать
        //$text = "Какой-то простой текст новый чтобы дадада текст";
        $file = 'C:/Apache24/htdocs/tester/web/challenges/text/layout.txt';
        $current = file_get_contents($file);
        // открываем файл, если файл не существует,
        //делается попытка создать его
        $link = 'user' . Yii::$app->user->id . 'challenge' . $id . 'timestamp' . Yii::$app->getFormatter()->asTimestamp(time());
        file_put_contents('C:/Apache24/htdocs/tester/web/challenges/user' . Yii::$app->user->id . 'challenge' . $id . 'timestamp' . Yii::$app->getFormatter()->asTimestamp(time()) . '.txt', $current);
        //return $this->redirect(Url::to(['challenge/progress', 'id' => $id]));

        \yii\helpers\VarDumper::dump($_SESSION['exercise_id'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['challenge_id'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['answers'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['hints'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['result'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['mark'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['all_user_points'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['points'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['all_points'], 10, true);
        \yii\helpers\VarDumper::dump($_SESSION['time'], 10, true);

        $savedResults = new SavedResults();
        $savedResults->user_id = Yii::$app->user->id;;
        $savedResults->course_id = $_SESSION['course_id'];
        $savedResults->exercise_id = $_SESSION['exercise_id'];
        $savedResults->challenge_id = $_SESSION['challenge_id'];
        $savedResults->answers = $_SESSION['answers'];
        $savedResults->hints = $_SESSION['hints'];
        $savedResults->result = $_SESSION['result'];
        $savedResults->mark = $_SESSION['mark'];
        $savedResults->all_user_points = $_SESSION['all_user_points'];
        $savedResults->points = $_SESSION['points'];
        $savedResults->all_points = $_SESSION['all_points'];
        $savedResults->time = $_SESSION['time'];
        $savedResults->link = $link;
        $savedResults->save();
    }

}
