<?php

namespace app\controllers;

use app\helpers\Json;
use app\models\Answer;
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->request->post();

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
}
