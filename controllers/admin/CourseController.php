<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\helpers\Subset;
use app\models\Course;
use app\models\CourseLecturer;
use app\models\CourseSubscription;
use app\models\search\CourseSearch;
use app\models\Subject;
use dektrium\user\models\User;
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $class = $this->getModelClass();
        $model = new $class();

        $lecturer = new CourseLecturer();

        $users = User::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $lecturer->user_id = $model->user_id;
            $lecturer->course_id = $model->id;
            $lecturer->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'lecturer' => $lecturer,
                'users' => $users
            ]);
        }
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * INSERT INTO course_lecturer(id, user_id, course_id) VALUES('1','7','1');
     * DELETE FROM course_lecturer WHERE id='11';
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$lecturer = CourseLecturer::find()->where(['course_id' => $id])->one();
        $lecturer = CourseLecturer::find()->where(['course_id' => $id])->one();
        $users = CourseLecturer::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $lecturer->user_id = $model->user_id;
            $lecturer->course_id = $id;
            $lecturer->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'lecturer' => $lecturer,
                'users' => $users
            ]);
        }
    }

    /**
     * Displays a single model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'lecturer' => CourseLecturer::find()->select('user_id')->where(['course_id' => $id])->all(),
            'users' => User::find()->all()
        ]);
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
    
}
