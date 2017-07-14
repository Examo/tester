<?php

namespace app\controllers;

use app\models\Course;
use app\models\search\CourseSearch;
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

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

        return $this->render('view', [
            'course' => $course,
        ]);
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
