<?php
namespace app\controllers;
use app\models\ar\LearnObject;
use app\models\ar\ScaleLearn;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\Learn;
use app\models\search\CourseSearch;
use app\models\Event;
use Yii;
use yii\web\Controller;

class LearnController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            if ( \Yii::$app->user->isGuest ) {
                $this->redirect( ['user/login'] );
                return false;
            }

            return true;
        }

        return false;
    }

    public function actionIndex() // основной экшн
    {
        $learning = new Learn();

        $challenges = [];
        $match = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        $backgroundColor = 'grey';
        $heightScaleValue = 0;
        $allDaysFeed = [];
        $allDaysClean = [];
        $allDays = [];
        $allEvents = [];
        $all = [];
        $allCourses = [];
        $data = [];
        $lastData = [];
        $coursesBegin = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            if ($events != []) {
                $allEvents[$course->id] = $events;
            }
        }

        if ($allEvents) {

            // цикл с разбором всех событий
            foreach ($allEvents as $keyEvent => $event) {

                // цикл с перебором всех событий конкретного курса и выбором события "Начало"
                for ($i = 0; $i < count($event); $i++) {
                    // если у события курса название "Начало", то...
                    if ($event[$i]->title == 'Начало') {
                        // получим модель курса
                        $course = Course::find()->where(['id' => $event[$i]->course_id])->one();
                        // получим время начала курса
                        $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event[$i]->start);
                        // узнаём текущее время и переводим его в простое число
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        // получаем изменение времени с момента начала курса до текущего момента
                        $timeAfterCourseStart = $time - $courseStartTime;
                        // если курс ещё не начался
                        if ($timeAfterCourseStart < 0) {
                            $timeAfterCourseStart /= 60;
                            //print 'Курс ' . $course->name . ' ещё не начался!<br> До начала курса осталось ' . $timeAfterCourseStart . ' секунд.<br>';
                        } // если курс уже начался
                        else {
                            //print 'Курс ' . $course->name . ' уже начался!<br> С момента начала курса прошло ' . $timeAfterCourseStart . ' секунд.<br>';
                            $weekTime = 604800;
                            $week = ceil($timeAfterCourseStart / $weekTime);
                            //print 'Идёт ' . $week . '-я неделя курса<br>';

                            for ($o = 1; $o <= $week; $o++) {
                                if (ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                    $scale = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                    foreach ($days as $day) {
                                        $oneOfDays = json_decode($scale->$day, true);
                                        $allDaysFeed[$keyEvent][$o][] = $oneOfDays['feed'];
                                        $allDaysClean[$keyEvent][$o][] = $oneOfDays['clean'];
                                        $allDays[$keyEvent][$o]['feed'][] = $oneOfDays['feed'];
                                        $allDays[$keyEvent][$o]['clean'][] = $oneOfDays['clean'];
                                    }
                                } else {
                                    $learn = new ScaleLearn();
                                    $learn->user_id = Yii::$app->user->id;
                                    $learn->course_id = $course->id;
                                    $learn->week_id = $o;
                                    foreach ($days as $key => $day) {
                                        $learn->$day = json_encode(['feed' => 0, 'clean' => 0]);
                                    }
                                    $learn->save();
                                }

                            }
                        }

                        $test = Event::find()->where(['course_id' => $keyEvent])->all();

                        // получаем изменение времени с момента начала курса до текущего момента
                        $timeAfterCourseStart = $time - $courseStartTime;
                        // если курс ещё не начался

                        $regexp = "/(тест)([0-9]*)/ui";
                        $weekTime = 604800;
                        foreach ($test as $key => $oneTest) {
                            if (preg_match($regexp, $oneTest->title, $match[$keyEvent][$key])) {
                                $currentWeek = ceil($timeAfterCourseStart / $weekTime);
                                $testWeekTime = Yii::$app->getFormatter()->asTimestamp($oneTest->start);
                                $tillTestWeekStart = $testWeekTime - $courseStartTime;
                                if ($tillTestWeekStart > 0) {
                                    $testWeek = ceil($tillTestWeekStart / $weekTime);
                                    $week = ceil($timeAfterCourseStart / $weekTime);
                                    if ($week - $testWeek > 0) {
                                        $learnObject = LearnObject::find()->where(['id' => $testWeek])->one();
                                        $data[$keyEvent]['currentWeek'] = $currentWeek;
                                        $data[$keyEvent][$key]['week'] = $testWeek;
                                        $data[$keyEvent][$key]['test'] = $match[$keyEvent][$key][2];
                                        if (Challenge::find()->select(['week'])->where(['id' => $match[$keyEvent][$key][2]])->one()) {
                                            $realChallengeWeek = Challenge::find()->select(['week'])->where(['id' => $match[$keyEvent][$key][2]])->one();
                                            $data[$keyEvent][$key]['realChallengeWeek'] = $realChallengeWeek->week;
                                        }
                                        $data[$keyEvent][$key]['object'] = $learnObject->object;
                                        if (Attempt::find()->where(['challenge_id' => $match[$keyEvent][$key][2]])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                            $data[$keyEvent][$key]['isAttempt'] = true;
                                        } else {
                                            $data[$keyEvent][$key]['isAttempt'] = null;
                                        }
                                    }
                                }
                            } else {
                                unset($match[$keyEvent][$key]);
                            }
                        }
                    }
                }
            }

            $test = Event::find()->where(['course_id' => 1])->all();

            $regexp = "/(тест)([0-9]*)/ui";
            $match = [];
            foreach ($test as $key => $oneTest) {
                if (preg_match($regexp, $oneTest->title, $match[$key])) {

                } else {
                    unset($match[$key]);
                }
            }

            foreach ($match as $key => $oneMatch) {
                // print $oneMatch[2];
            }

            $new = [];
            $sortData = [];
            foreach ($data as $hey => $row) {
                foreach ($row as $newKey => $newRow) {
                    $new[$hey][$newKey] = $newRow['week'];
                }
                array_multisort($new[$hey], SORT_ASC, $row);
                $sortData[$hey] = $row;
            }
            $data = $sortData;

            $testData = [];
            $lastData = [];

            foreach ($data as $courseId => $challengesData) {
                foreach ($challengesData as $keyChallenges => $dataChallenge) {
                    if (isset($testData[$dataChallenge['week']])){
                        $lastData[$dataChallenge['week']][$courseId] = $dataChallenge;
                    } else {
                        $testData[$dataChallenge['week']] = $dataChallenge['week'];
                        $lastData[$dataChallenge['week']][$courseId] = $dataChallenge;
                    }
                }
                $coursesBegin[$courseId]['currentWeek'] = $challengesData['currentWeek'];
            }
            unset($lastData['']);

            ksort($lastData);
            
            $result = [];
            $weekResult = 0;
            foreach ($allDaysFeed as $courseFeed => $weekFeed) {
                foreach ($allDaysClean as $courseClean => $weekClean) {
                    if ($courseFeed == $courseClean) {
                        //print $courseClean;
                        foreach ($weekFeed as $keyFeed => $valueFeed) {
                            foreach ($weekClean as $keyClean => $valueClean) {
                                if ($keyFeed == $keyClean) {
                                    //$result += $valueFeed + $valueClean;
                                    //print $keyClean;
                                    $weekResult = 0;
                                    foreach ($valueFeed as $lastKeyFeed => $lastValueFeed) {
                                        foreach ($valueClean as $lastKeyClean => $lastValueClean) {
                                            if ($lastKeyFeed == $lastKeyClean) {
                                                //print $lastValueFeed;
                                                //print $lastValueClean;
                                                $weekResult += $lastValueFeed + $lastValueClean;
                                                // print '<br>';
                                            }
                                        }
                                    }
                                }
                            }
                            $result[$courseFeed][$keyFeed] = $weekResult;
                        }
                    }
                }
            }

            $dayName = [0 => 'monday', 1 => 'tuesday', 2 => 'wednesday', 3 => 'thursday', 4 => 'friday', 5 => 'saturday', 6 => 'sunday'];

            $mustDo = [];
            $allDays = [];
            foreach ($allDays as $course => $weeks) {
                foreach ($weeks as $weekKey => $weekArray) {
                    //print count($weeks);
                    foreach ($weekArray as $element => $week) {
                        foreach ($week as $day => $value) {
                            if ($value == 0 && $weekKey != count($weeks)) {
                                //print 'Получить общий тест ' . $element . ' за неделю №' . $weekKey . '<br>';
                                // набивать массив и выводить в конце
                                $mustDo[$course][$weekKey] = true;
                                break;
                            }
                            if ($value == 0 && $weekKey == count($weeks)) {
                                //print 'Не заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                                //print 'в неделе '. $weekKey . '<br>';
                            }
                            if ($value != 0 && $weekKey == count($weeks)) {
                                //print 'Заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                                //print 'в неделе ' . $weekKey . '<br>';
                            }

                        }
                    }
                }
            }

            $lastResult = [];
            $lastWeeks = [];
            foreach ($result as $course => $week) {
                foreach ($week as $weekKey => $day) {
                    if (isset($lastResult[$weekKey])) {
                        $lastResult[$weekKey] += $day;
                    } else {
                        $lastResult[$weekKey] = $day;
                    }
                }
                $lastWeeks[$course]['week'] = count($week);
                $lastWeeks[$course]['result'] = $week[count($week)];
            }

            $lastValue = 0;
            foreach ($lastWeeks as $course => $lastWeeksValue) {
                $lastValue += $lastWeeksValue['result'];
            }

            $heightScaleValue = 100 - $lastValue;

            if ($lastValue <= 10) {
                $backgroundColor = 'red';
            } elseif ($lastValue < 100 && $lastValue > 10) {
                $backgroundColor = 'green';
            } elseif ($lastValue >= 100) {
                $backgroundColor = 'blue';
            }

            $all = [];
            foreach ($lastResult as $weekKey => $value) {
                if (LearnObject::find()->where(['id' => $weekKey])->one()) {
                    $learn = LearnObject::find()->where(['id' => $weekKey])->one();

                    $assignmentValue = 7 * 2 * count($lastWeeks);
                    $assignmentValueCost = 100 / $assignmentValue;
                    $value *= $assignmentValueCost;

                    $heightScaleValue = 100 - $value;

                    $all[$weekKey]['week'] = $weekKey;
                    $all[$weekKey]['object'] = $learn->object;
                    $all[$weekKey]['value'] = ceil($value);
                    $all[$weekKey]['heightScaleValue'] = $heightScaleValue;
                }
            }

            $allCourses = [];
            $allCourses['number'] = 0;
            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $key => $course) {
                $allCourses['number'] = $allCourses['number'] + 1;
                $allCourses[$key]['name'] = $course->name;
            }
        } else { // если нет никаких событий
            $all = null;

        }

        return $this->render('index', [
            'learning' => $learning,
            'challenges' => $challenges,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'all' => $all,
            'allCourses' => $allCourses,
            'data' => $data,
            'lastData' => $lastData,
            'coursesBegin' => $coursesBegin
        ]);
    }
}