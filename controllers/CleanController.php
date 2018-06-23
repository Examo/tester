<?php
namespace app\controllers;
use app\models\ar\ChallengesWeeks;
use app\models\ar\Clean;
use app\models\ar\DifficultSubjects;
use app\models\Challenge;
use app\models\Course;
use app\models\Event;
use app\models\Subject;
use Yii;
use yii\web\Controller;
use app\widgets\CleanWidget;

class CleanController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex()
    {
        // получаем курс ученика и все события курсов
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            $allEvents[$course->id] = $events;
        }
        $allCurrentWeeksChallenges = [];
        $allPreparedChallenges = [];

        // если существуют события "Начало" в каком-либо курсе, на который подписан ученик
        if (isset($allEvents)) {

            // цикл с разбором всех событий
            foreach ($allEvents as $keyEvent => $event) {

                // цикл с перебором всех событий конкретного курса и выбором события "Начало"
                for ($i = 0; $i < count($event); $i++) {
                    //$allNewChallenges = [];
                    $allCurrentWeeksChallenges = [];
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
                            print 'Курс ' . $course->name . ' ещё не начался!<br> До начала курса осталось ' . $timeAfterCourseStart . ' секунд.<br>';
                        } // если курс уже начался
                        else {
                            print 'Курс ' . $course->name . ' уже начался!<br> С момента начала курса прошло ' . $timeAfterCourseStart . ' секунд.<br>';
                            $weekTime = 604800;
                            $week = ceil($timeAfterCourseStart / $weekTime);
                            print 'Идёт ' . $week . '-я неделя курса<br>';
                            $currentWeeksChallenges = Challenge::find()->where(['course_id' => $course->id])->andWhere(['week' => $week])->andWhere(['element_id' => 2])->all();
                            // соберём все тесты в массив, в котором ключи будут id тестов
                            foreach ($currentWeeksChallenges as $weekChallenge) {
                                //print $newChallenge->id . '<br>';
                                $allCurrentWeeksChallenges[$weekChallenge->id] = 0;
                            }

                            $newChallengesIds = [];
                            $allNewChallenges = [];
                            $challengesTest = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();


                            if (isset($challengesTest->challenges)) {
                                $challengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                if (json_decode($challengesWeeks->challenges) == []) {
                                    $allNewChallenges = [];
                                    foreach (json_decode($challengesWeeks->challenges) as $challengesWeek){
                                        print $challengesWeek . '<br>';
                                    }
                                    $currentWeeksChallenges = Challenge::find()->where(['course_id' => $course->id])->andWhere(['week' => $week])->andWhere(['element_id' => 2])->all();
                                    // соберём все тесты в массив, в котором ключи будут id тестов
                                    foreach ($currentWeeksChallenges as $weekChallengeNew) {
                                        //print $newChallenge->id . '<br>';
                                        $allNewChallenges[$weekChallengeNew->id] = 0;
                                    }

                                }
                            }

                            // если в таблице challenges_weeks существует запись с указанием курса, недели и id ученика
                            if (ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                // получаем запись о тестах на текущую неделю
                                $challengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                // получаем из базы массив с записанными id тестов
                                $challengesIds = json_decode($challengesWeeks->challenges, true);
                                //$allNewChallenges = [2 => 0];
                                // если существуют текущие тесты на эту неделю и существуют id тестов на эту неделю в базе данных
                                if ($allCurrentWeeksChallenges && $challengesIds) {
                                    // если существует разница между тестами на эту неделю и записью id тестов на эту неделю (тест мог добавиться по время работы недели), то
                                    if (array_diff_key($allCurrentWeeksChallenges, $challengesIds)) {
                                        // массив с разницей в тестах (тесты в системе добавились или убавились) разбираем в цикле и наполняем новый массив
                                        foreach (array_diff_key($allCurrentWeeksChallenges, $challengesIds) as $keyInsert => $valueInsert) {
                                            $allNewChallenges[$keyInsert] = $valueInsert;
                                        }
                                        // дополняем массив всеми тестами
                                        foreach ($allCurrentWeeksChallenges as $keyAll => $valueAll) {
                                            $allNewChallenges[$keyAll] = 0;
                                        }
                                        // дополняем массив тестами из базы, учитывая, пройдены они или нет (0 у теста или 1)
                                        foreach ($challengesIds as $keyId => $valueId) {
                                            $allNewChallenges[$keyId] = $valueId;
                                        }
                                        // если разницы между массивом с имеющимися тестами и массивом из базы с записанными тестами нет, то
                                    } else {
                                        // переберём в цикле массив с текущими тестами
                                        foreach ($allCurrentWeeksChallenges as $keyAll => $valueAll) {
                                            // если значение в массивах со всеми тестами и с id записанных тестов не равны (то есть если в БД указана у теста 1 - что он был выполнен), то
                                            if ($allCurrentWeeksChallenges[$keyAll] != $challengesIds[$keyAll]) {
                                                // вставляем этот тест в новый массив с 1
                                                $allNewChallenges[$keyAll] = 1;
                                            } // если значение в массиве со всеми тестами равно значению в массиве со всеми ID тестов в базе (то есть тест не выполнялся, у него 0), то ставим 0
                                            else {
                                                $allNewChallenges[$keyAll] = 0;
                                            }
                                        }

                                    }

                                    $checkOne = [];
                                    $checkNull = [];
                                    // проверим, не все ли 1 у получившихся тестов
                                    foreach ($allNewChallenges as $allNewChallenge) {
                                        if ($allNewChallenge == 1) {
                                            $checkOne[] = $allNewChallenge;
                                        } else {
                                            $checkNull[] = $allNewChallenge;
                                        }
                                    }
                                    // если существует массив с одними единицами и нет массива с нулями, то
                                    if ($checkOne && !$checkNull) {
                                        // переписываем все 1 на 0 - чтобы тесты на неделю снова были доступны
                                        foreach ($allNewChallenges as $newKey => $newValue) {
                                            $veryNewChallenges[$newKey] = 0;
                                        }
                                        $allNewChallenges = $veryNewChallenges;
                                    }
                                }

                                if ($allNewChallenges == []){
                                    $allNewChallenges = [1 => 0];
                                }

                                $challengesWeeks->course_id = $course->id;
                                $challengesWeeks->week_id = $week;
                                $challengesWeeks->user_id = Yii::$app->user->id;
                                $challengesWeeks->challenges = json_encode($allNewChallenges);
                                $challengesWeeks->element_id = 2;
                                $challengesWeeks->save();
                            } else {
                                $currentWeeksChallenges = Challenge::find()->where(['course_id' => $course->id])->andWhere(['week' => $week])->andWhere(['element_id' => 2])->all();
                                foreach ($currentWeeksChallenges as $newChallenge) {
                                    $allNewChallenges[$newChallenge->id] = 0;
                                }
                                if (!$currentWeeksChallenges) {

                                }
                                if ($allNewChallenges == []){
                                    $allNewChallenges = [1 => 0];
                                }
                                $challengesWeeks = new ChallengesWeeks();
                                $challengesWeeks->course_id = $course->id;
                                $challengesWeeks->week_id = $week;
                                $challengesWeeks->user_id = Yii::$app->user->id;
                                $challengesWeeks->challenges = json_encode($allNewChallenges);
                                $challengesWeeks->element_id = 2;
                                $challengesWeeks->save();
                            }

                        }
                    }
                    $allPreparedChallenges[] = $allNewChallenges;
                }

            }


            $allCleanChallenges = [];
            for ($i = 0; $i < count($allPreparedChallenges); $i++) {
                foreach ($allPreparedChallenges[$i] as $key => $value) {
                    $allCleanChallenges[$key] = $value;
                }
            }

            $cleaningTests = new Clean();
            $challenges = [];
            $cleanChallenges = [];
            $number = 1;
            $newCleanChallenges = [];

            foreach ($allCleanChallenges as $keyVeryNew => $valueVeryNew) {
                if ($valueVeryNew == 0) {
                    $challenges[] = Challenge::find()->where(['id' => $keyVeryNew])->one();
                } else {
                }
            }

            $subjectsChecked = [];
            foreach ($challenges as $challenge) {
                if ($challenge->element_id == 2) {
                    $subject = Challenge::find()->innerJoinWith('subject')->where(['challenge.subject_id' => $challenge->subject_id])->andWhere(['challenge.id' => $challenge->id])->one();
                    if (!isset($subjectsChecked[$subject->subject->id])) {
                        $cleanChallenges[$number]['subject_id'] = $subject->subject->id;
                        $cleanChallenges[$number]['subject_name'] = $subject->subject->name;
                        $cleanChallenges[$number]['challenge_id'] = $subject->id;
                        $cleanChallenges[$number]['challenge_name'] = $subject->name;
                        $cleanChallenges[$number]['course_id'] = $subject->course_id;
                        $elements_item = Challenge::find()->innerJoinWith('elements_item')->where(['challenge.elements_item_id' => $subject->elements_item_id])->one();
                        $cleanChallenges[$number]['challenge_clean_item'] = $elements_item->elements_item->name;
                        $number++;
                        $subjectsChecked[$subject->subject->id] = $subject->subject->id;
                    }
                }
            }

            $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->all();
            //\yii\helpers\VarDumper::dump($difficultSubjects, 10, true);
            $newDifficultSubjects = [];
            foreach ($difficultSubjects as $difficultSubject) {
                $needCourse = Subject::find()->select('course_id')->where(['id' => $difficultSubject->subject_id])->one();
                //\yii\helpers\VarDumper::dump($needCourse, 10, true);
                foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
                    if ($needCourse->course_id == $course->id) {
                        //print $needCourse->course_id . '<br>';
                        $newDifficultSubjects[] = $difficultSubject;
                    }
                }
            }

            $difficultSubjects = $newDifficultSubjects;

            $mainChallenges = $cleanChallenges;
            \yii\helpers\VarDumper::dump($difficultSubjects, 10, true);
            if ($mainChallenges) {
                if ($difficultSubjects) {
                    foreach ($difficultSubjects as $difficultSubject) {
                        foreach ($cleanChallenges as $cleanChallengeNumber => $cleanChallenge) {
                            if ($difficultSubject->subject_id == $cleanChallenge['subject_id']) {
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_points'] = $difficultSubject->points;
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_id'] = $cleanChallenge['subject_id'];
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_name'] = $cleanChallenge['subject_name'];
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_id'] = $cleanChallenge['challenge_id'];
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_name'] = $cleanChallenge['challenge_name'];
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_clean_item'] = $cleanChallenge['challenge_clean_item'];
                                $newCleanChallenges[$difficultSubject->subject_id]['course_id'] = $cleanChallenge['course_id'];
                                $subjectCourseName = Course::find()->select('name')->where(['id' => $cleanChallenge['course_id']])->one();
                                $newCleanChallenges[$difficultSubject->subject_id]['course_name'] = $subjectCourseName->name;
                                break;
                            } else {
                                $subject = Subject::find()->where(['id' => $difficultSubject->subject_id])->one();
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_points'] = $difficultSubject->points;
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_id'] = $difficultSubject->subject_id;
                                $newCleanChallenges[$difficultSubject->subject_id]['subject_name'] = $subject->name;
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_id'] = null;
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_name'] = null;
                                $newCleanChallenges[$difficultSubject->subject_id]['challenge_clean_item'] = null;
                                $newCleanChallenges[$difficultSubject->subject_id]['course_id'] = $subject->course_id;
                                $subjectCourseName = Course::find()->select('name')->where(['id' => $subject->course_id])->one();
                                $newCleanChallenges[$difficultSubject->subject_id]['course_name'] = $subjectCourseName->name;
                            }
                        }
                    }
                    foreach ($newCleanChallenges as $key => $row) {
                        $subjectPoints[$key] = $row['subject_points'];
                    }
                    array_multisort($subjectPoints, SORT_ASC, $newCleanChallenges);
                } else {
                    $newCleanChallenges = null;
                }
            } else {
                $newCleanChallenges = null;
            }
        } else {
            $newCleanChallenges =  [];
            $cleaningTests = new Clean();
            $challenges =  [];;
            $difficultSubjects = [];
        }
            return $this->render('index', [
                'cleaningTests' => $cleaningTests,
                'challenges' => $challenges,
                'difficultSubjects' => $difficultSubjects,
                'newCleanChallenges' => $newCleanChallenges
            ]);

    }

    public function actionWidget()
    {
        $this->layout = false;

        return CleanWidget::widget();
    }
}