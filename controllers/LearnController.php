<?php
namespace app\controllers;
use app\helpers\LearnChecker;
use app\models\ar\LearnObject;
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

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        $allEvents = [];
        $allCourses = [];
        $data = [];
        $lastData = [];
        $coursesBegin = [];
        $latestData = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            if ($events != []) {
                $allEvents[$course->id] = $events;
            }
        }

        if ($allEvents) {

            $latestData = LearnChecker::getLearnData();

            $all = [];
            foreach ($latestData['lastResult'] as $weekKey => $value) {
                if (LearnObject::find()->where(['id' => $weekKey])->one()) {
                    $learn = LearnObject::find()->where(['id' => $weekKey])->one();

                    // заявленное значение равно 7 дней умножить на 2 теста в день (Еда и Уборка) и умножить на количество курсов
                    // но нужно умножить на вебинары готовы или не готовы,
                    // 7 * 2 + ()
                    // Вебинары + тесты для Еды и Уборки + Домашнее задание + Экзамен = 100%
                    // тесты для Еды и Уборки - это 100% разделить на 4 и разделить на 14 - так каждый тест даёт
                    // вебинары - это 25% и всё остальное - тоже
                    // на домашнее задание
                    // на общий тест за неделю


                    $webinar = 0;
                    if (isset($latestData['webinarsData'])){
                        foreach ($latestData['webinarsData'] as $webinarId => $webinarsData){
                            if (isset($webinarsData['undone'])){
                                $webinar = 14;
                                print 'webinar undone';
                            }
                            if (!isset($webinarsData['undone'])){
                                $webinar = 0;
                                print 'webinar done';
                            }

                        }

                    }
                    \yii\helpers\VarDumper::dump($latestData['webinarsData'], 10, true);

                    $number = 1;
                    $generalValue = 7 * 2; // 7 дней и 2 обязательных теста в каждом
                    $generalScaleValue = $value;

                    if (isset($webinar) && isset($webinar['done'])){
                        $webinar = $generalValue;
                        $generalScaleValue += $webinar;
                        $number++;
                    }
                    if (isset($webinar) && isset($webinar['undone'])){
                        $webinar = 0;
                        $generalScaleValue += $webinar;
                        $number++;
                    }
                    if (isset($homework) && isset($homework['done'])){
                        $homework = $generalValue;
                        $generalScaleValue += $homework;
                        $number++;
                    }
                    if (isset($homework) && isset($homework['undone'])){
                        $homework = 0;
                        $generalScaleValue += $homework;
                        $number++;
                    }
                    if (isset($exam) && isset($exam['done'])){
                        $exam = $generalValue;
                        $generalScaleValue += $exam;
                        $number++;
                    }
                    if (isset($exam) && isset($exam['undone'])){
                        $exam = 0;
                        $generalScaleValue += $exam;
                        $number++;
                    }
                    if (isset($generalChallenge) && isset($generalChallenge['done'])){
                        $generalChallenge = $generalValue;
                        $generalScaleValue += $generalChallenge;
                        $number++;
                    }
                    if (isset($generalChallenge) && isset($generalChallenge['undone'])){
                        $generalChallenge = 0;
                        $generalScaleValue += $generalChallenge;
                        $number++;
                    }

                    $hundredPercent = $generalValue * $number;
                    $onePercent = $hundredPercent / 100 * 1;

                    $result = $value * 100 / $hundredPercent;


                    $assignmentValue = ((7 * 2) + $webinar) * count($latestData['lastWeeks']);
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

        //\yii\helpers\VarDumper::dump($latestData, 10, true);

        return $this->render('index', [
            'learning' => $learning,
            'challenges' => $challenges,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'all' => $all,
            'allCourses' => $allCourses,
            'data' => $data,
            'lastData' => $lastData,
            'coursesBegin' => $coursesBegin,
            'latestData' => $latestData
        ]);
    }
}