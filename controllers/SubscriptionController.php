<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\Course;
use app\models\CourseLecturer;
use app\models\Discipline;
use app\models\search\CourseSearch;
use app\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class SubscriptionController
 * @package app\controllers
 */
class SubscriptionController extends Controller
{
    public $layout = 'metronic_sidebar';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            if ( \Yii::$app->user->isGuest ) {
                $this->layout = 'register_login_metronic';
                $this->redirect( ['user/login'] );
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * My subscriptions
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Subscriptions catalog
     *
     * @return string
     */
    public function actionAll()
    {
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchAvailable(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        //$this->layout = 'metronic_sidebar';
        $lecturers = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Lecturer'])->all();
        $users = [];
        $lecturersCourses = [];
        foreach ($lecturers as $lecturer){
            $users[] = User::find()->where(['id' => $lecturer->user_id])->one();
            $lecturersCourses[] = CourseLecturer::find()->where(['user_id' => $lecturer->user_id])->all();
        }
        $disciplines = Discipline::find()->all();
        $courses = Course::find()->all();

        $testLecturer = CourseLecturer::find()->all();

        if (!empty($lecturers)) {
            return $this->render('all',
                [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'lecturers' => $lecturers,
                    'users' => $users,
                    'courses' => $courses,
                    'disciplines' => $disciplines,
                    'lecturersCourses' => $lecturersCourses,
                    'testLecturer' => $testLecturer
                ]);
        } else {
            throw new NotFoundHttpException('Преподавателей пока ещё не существует!');
        }


    }

    /**
     * View course
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $course = Course::findOne($id);
        if (!$course) {
            throw new NotFoundHttpException();
        }
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchAvailable(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );
        $lecturers = AuthAssignment::find()->select('user_id')->where(['item_name' => 'Lecturer'])->all();
        $users = [];
        $lecturersCourses = [];
        foreach ($lecturers as $lecturer){
            $users[] = User::find()->where(['id' => $lecturer->user_id])->one();
            $lecturersCourses[] = CourseLecturer::find()->where(['user_id' => $lecturer->user_id])->all();
        }
        $disciplines = Discipline::find()->all();
        $courses = Course::find()->all();

        $testLecturer = CourseLecturer::find()->all();

        if (!empty($lecturers)) {
            return $this->render('view', [
                'course' => $course,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'lecturers' => $lecturers,
                'users' => $users,
                'courses' => $courses,
                'disciplines' => $disciplines,
                'lecturersCourses' => $lecturersCourses,
                'testLecturer' => $testLecturer
            ]);
        } else {
            throw new NotFoundHttpException('Преподавателя в этом курсе пока ещё не существует!');
        }
    }

    /**
     * Subscribe
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSubscribe($id)
    {
        $course = Course::findOne($id);
        if (!$course) {
            throw new NotFoundHttpException();
        }

        $course->subscribe(Yii::$app->user->id);

        return $this->redirect(Url::to(['/subscription']));
    }

    /**
     * Unsubscribe
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUnsubscribe($id)
    {
        $course = Course::findOne($id);
        if (!$course) {
            throw new NotFoundHttpException();
        }

        $course->unsubscribe(Yii::$app->user->id);

        return $this->redirect(Url::to(['/subscription']));
    }

}
