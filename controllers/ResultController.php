<?php

namespace app\controllers;

use app\helpers\Json;
use app\models\Answer;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\User;
use Yii;
use yii\web\Controller;
use app\models\search\ResultSearch;
use yii\web\NotFoundHttpException;

/**
 * Class ResultController
 * @package app\controllers
 */
class ResultController extends Controller
{
    public $layout = 'metronic';

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ResultSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Answer::className();
    }


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if ( \Yii::$app->user->isGuest ) {
            $this->layout = 'metronic_sidebar';
            return $this->redirect('/home/index');
        } else {
            $class = $this->getSearchModelClass();

            $searchModel = new $class();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionView($id)
    {
        $data = $this->getData($id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'data' => $data
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();
        $userData = $this->getData($id);
        if ($data) {
            $data['Answer']['result'] = Json::encode($data['Answer']['result']);
        }

        if ($model->load($data) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            foreach ($model->errors as $error) {
                Yii::$app->session->setFlash(
                    'error',
                    $error[0]
                );
            }

            return $this->render('update', [
                'model' => $model,
                'userData' => $userData
            ]);
        }
    }

    protected function findModel($id)
    {
        $class = $this->getModelClass();

        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getData($id){

        $data = [];
        $attempt = Attempt::find()->where(['id' => $this->findModel($id)['attempt_id']])->one();
        $user_id = $attempt->user_id;
        $user = User::find()->where(['id' => $user_id])->one();
        $username = $user->username;
        $challenge_id = $attempt->challenge_id;
        $course_id = Challenge::find()->where(['id' => $challenge_id])->one();
        $course = Course::find()->where(['id' => $course_id])->one();
        $course_name = $course->name;
        $data['user_id'] = $user_id;
        $data['username'] = $username;
        $data['challenge_id'] = $challenge_id;
        $data['course_name'] = $course_name;

        return $data;

    }
}
