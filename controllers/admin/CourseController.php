<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\helpers\Subset;
use app\models\Course;
use app\models\CourseSubscription;
use app\models\search\CourseSearch;
use app\models\Subject;
use dektrium\user\models\UserSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Course::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return CourseSearch::className();
    }

    /**
     * @inheritdoc
     */
    public function saveModel($model)
    {
        Subset::save(
            Subject::className(),
            Yii::$app->request->post(),
            ['course_id' => $model->id]
        );

        return true;
    }

    public function actionStat()
    {
        //$this->layout = 'metronic_sidebar';
        $course = Course::findOne(Yii::$app->request->get('course_id'));

        Url::remember('', 'actions-redirect');
        $searchModel  = Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        if (!empty($course)) {
            return $this->render('stat',
                [
                    'course' => $course,
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                ]);
        } else {
            throw new NotFoundHttpException('Статистики по этому курсу пока ещё не существует!');
        }
    }

    /*public function actionStat()
    {
        //$this->layout = 'metronic_sidebar';
        $course = Course::findOne(Yii::$app->request->get('course_id'));

        Url::remember('', 'actions-redirect');
        $searchModel  = Yii::createObject(UserSearch::className());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        if (!empty($course)) {
            return $this->render('stat',
                [
                    'course' => $course,
                    'dataProvider' => $dataProvider,
                    'searchModel'  => $searchModel,
                ]);
        } else {
            throw new NotFoundHttpException('Статистики по этому курсу пока ещё не существует!');
        }
    }*/

    public function actionStats()
    {
        //$this->layout = 'metronic_sidebar';
        $courses = Course::find()->all();

        if (!empty($courses)) {
            return $this->render('stats/index',
                [
                    'courses' => $courses,


                ]);
        } else {
            throw new NotFoundHttpException('Статистики пока ещё не существует!');
        }
    }

    public function findUserById($data, $id)
    {

    }
}
