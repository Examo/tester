<?php

namespace app\controllers;

use app\models\AuthAssignment;
use app\models\Course;
use app\models\CourseLecturer;
use app\models\CourseSubscription;
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

        $courseTime = $challengesCount = $webinarsCount = $homeworksCount = $examsCount = $webinarsDone = [];

        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        $subscription = CourseSubscription::find()->all();

        $userSubscription = CourseSubscription::find()->where(['user_id' => Yii::$app->user->id])->all();
        $subscriptionStart = CourseSubscription::find()->one();

        foreach ($userSubscription as $key => $course) {
            $courseTime[$course->course_id] = $subscriptionStart->getCourseStart($course->course_id);
            $challengesCount[$course->course_id] = $subscriptionStart->getChallenges($course->course_id);
            $webinarsCount[$course->course_id] = $subscriptionStart->getWebinarsCount($course->course_id);
            $webinarsDone[$course->course_id] = $subscriptionStart->getWebinarChallengesCheck($course->course_id);
            $homeworksCount[$course->course_id] = $subscriptionStart->getHomeworksCount($course->course_id);
            $examsCount[$course->course_id] = $subscriptionStart->getExamsCount($course->course_id);
        }

        $courses = Course::find()->all();
        $courseSubscription = [];
        foreach ($subscription as $key => $item) {
            foreach ($courses as $course) {
                if ($item->course_id == $course->id) {
                    $courseSubscription[$item->course_id][] = true;
                }
            }
        }
        $numberOfPupils = [];
        foreach ($courseSubscription as $courseNumber => $courses) {
            $numberOfPupils[$courseNumber] = count($courses);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'subscription' => $subscription,
            'courseSubscription' => $courseSubscription,
            'numberOfPupils' => $numberOfPupils,
            'courseTime' => $courseTime,
            'challengesCount' => $challengesCount,
            'webinarsCount' => $webinarsCount,
            'webinarsDone' => $webinarsDone,
            'homeworksCount' => $homeworksCount,
            'examsCount' => $examsCount
        ]);
    }

    /**
     * Subscriptions catalog
     *
     * @return string
     */
    public function actionAll()
    {
        $courseTime = $challengesCount = $webinarsCount = $homeworksCount = $examsCount = $webinarsDone = [];

        $courses = Course::find()->all();
        $subscriptionStart = new CourseSubscription();

        foreach ($courses as $key => $course) {
            $courseTime[$course->id] = $subscriptionStart->getCourseStart($course->id);
            $challengesCount[$course->id] = $subscriptionStart->getChallenges($course->id);
            $webinarsCount[$course->id] = $subscriptionStart->getWebinarsCount($course->id);
            $webinarsDone[$course->id] = $subscriptionStart->getWebinarChallengesCheck($course->id);
            $homeworksCount[$course->id] = $subscriptionStart->getHomeworksCount($course->id);
            $examsCount[$course->id] = $subscriptionStart->getExamsCount($course->id);
        }

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
        
        $testLecturer = CourseLecturer::find()->all();

        $subscription = CourseSubscription::find()->all();
        $courses = Course::find()->all();
        $courseSubscription = [];
        foreach ($subscription as $key => $item) {
            foreach ($courses as $course) {
                if ($item->course_id == $course->id) {
                    $courseSubscription[$item->course_id][] = true;
                }
            }
        }
        $numberOfPupils = [];
        foreach ($courseSubscription as $courseNumber => $courses) {
            $numberOfPupils[$courseNumber] = count($courses);
        }

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
                    'testLecturer' => $testLecturer,
                    'numberOfPupils' => $numberOfPupils,
                    'courseTime' => $courseTime,
                    'challengesCount' => $challengesCount,
                    'webinarsCount' => $webinarsCount,
                    'webinarsDone' => $webinarsDone,
                    'homeworksCount' => $homeworksCount,
                    'examsCount' => $examsCount
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

        $testLecturer = CourseLecturer::find()->all();

        $subscription = CourseSubscription::find()->all();
        $subscriptionStart = CourseSubscription::find()->one();
        $courseTime = $subscriptionStart->getCourseStart($id);
        $challengesCount = $subscriptionStart->getChallenges($id);
        $webinarsCount = $subscriptionStart->getWebinarsCount($id);
        $webinarsDone = $subscriptionStart->getWebinarChallengesCheck($id);
        $homeworksCount = $subscriptionStart->getHomeworksCount($id);
        $examsCount = $subscriptionStart->getExamsCount($id);

        $courses = Course::find()->all();
        $courseSubscription = [];
        foreach ($subscription as $key => $item) {
            foreach ($courses as $courseItem) {
                if ($item->course_id == $courseItem->id) {
                    $courseSubscription[$item->course_id][] = true;
                }
            }
        }
        $numberOfPupils = [];
        foreach ($courseSubscription as $courseNumber => $courses) {
            $numberOfPupils[$courseNumber] = count($courses);
        }

        $courseRating = $subscriptionStart->getCourseRating($id);

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
                'testLecturer' => $testLecturer,
                'numberOfPupils' => $numberOfPupils,
                'courseTime' => $courseTime,
                'challengesCount' => $challengesCount,
                'webinarsCount' => $webinarsCount,
                'webinarsDone' => $webinarsDone,
                'homeworksCount' => $homeworksCount,
                'examsCount' => $examsCount,
                'courseRating' => $courseRating
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
