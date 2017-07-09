<?php
namespace app\controllers;
use app\models\Course;
use app\models\Learn;
use app\models\search\CourseSearch;
use Yii;
use yii\web\Controller;

class LearnController extends Controller
{
    public $layout = 'metronic_sidebar';

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

        return $this->render('index', [
            'learning' => $learning,
            'challenges' => $challenges,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}