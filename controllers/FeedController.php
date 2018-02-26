<?php

namespace app\controllers;

use app\models\ar\ChallengeHasQuestion;
use app\models\ar\Food;
use app\models\ar\Question;
use app\models\ar\ScaleFeed;
use app\models\Attempt;
use app\models\Course;
use Yii;
use app\models\ar\Feed;
use app\models\ar\FeedSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FeedController implements the CRUD actions for Feed model.
 */
class FeedController extends Controller
{
    public $layout = 'metronic_sidebar';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

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

    /**
     * Lists all Feed models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FeedSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $feedingTests = new Feed();

        $challenges = [];
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }

     //   $attempt = Attempt::find()->select(['finish_time'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'feedingTests' => $feedingTests,
            //'foods' => $foods,
            'challenges' => $challenges,
           // 'lastChallenge' => $lastChallenge,
           // 'finishTime' => $finishTime,
           // 'allLastChallengeQuestionsCost' => $allLastChallengeQuestionsCost,
           // 'allDoneChallenges' => $allDoneChallenges,
           // 'allDoneChallengesCosts' => $allDoneChallengesCosts,
           // 'costAmount' => $costAmount,
           // 'finishCostAmount' => $finishCostAmount,
          //  'scaleTwist' => $scaleTwist,
          //  'attempt' => $attempt,
           // 'scale' => $scale,
          //  'scaleValue' => $scaleValue,
          //  'scale' => $scale
        ]);
    }

    /**
     * Displays a single Feed model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Feed model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Feed();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Feed model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Feed model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Feed model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Feed the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Feed::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
