<?php
namespace app\controllers;
use app\helpers\LearnChecker;
use app\models\ar\LearnObject;
use app\models\Course;
use app\models\Game;
use app\models\Learn;
use app\models\search\CourseSearch;
use app\models\Event;
use Yii;
use yii\web\Controller;

class GamerController extends Controller
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

                    $assignmentValue = 7 * 2 * count($latestData['lastWeeks']);
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

////   public $layout = 'metronic_sidebar';

//   /**
//    * @inheritdoc
//    */
//   public function beforeAction($action)
//   {
//       if (parent::beforeAction($action)) {
//           // Authorized users only
//           if ( \Yii::$app->user->isGuest ) {
//               $this->redirect( ['user/login'] );
//               return false;
//           }

//           return true;
//       }

//       return false;
//   }

//   public function actionIndex() // основной экшн
//   {
//       $learning = new Game();
//       if ( \Yii::$app->user->isGuest ) {
//           print 'NEUSPESHEN';
//           $this->redirect( ['user/login'] );
//           return false;
//       } else {
//
//           return $this->render('index', [
//               'learning' => $learning
//           ]);
//       }
//   }
}