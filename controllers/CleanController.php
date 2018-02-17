<?php
namespace app\controllers;
use app\models\ar\Clean;
use app\models\ar\DifficultSubjects;
use app\models\Course;
use app\models\Subject;
use Yii;
use yii\web\Controller;

class CleanController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $cleaningTests = new Clean();
        $challenges = [];
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewCleanChallenges(Yii::$app->user->id)->all());
        }
        $allSubjects = Subject::find()->all();
        $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->all();

        return $this->render('index', [
            'cleaningTests' => $cleaningTests,
            'challenges' => $challenges,
            'difficultSubjects' => $difficultSubjects,
            'allSubjects' => $allSubjects
        ]);
    }
}