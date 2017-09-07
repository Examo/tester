<?php

namespace app\controllers;

use app\models\ar\ChallengeHasQuestion;
use app\models\ar\Food;
use app\models\ar\Question;
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

        $lastChallenge = Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
        
        $lastChallengeId = Attempt::find()->select(['challenge_id'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
        $lastChallengeQuestions = ChallengeHasQuestion::find()->where(['challenge_id' => $lastChallengeId])->all();
        $allLastChallengeQuestionsCost = 0;
        foreach ($lastChallengeQuestions as $lastChallengeQuestion){
            $lastChallengeQuestionCost = Question::find()->select('cost')->where(['id' => $lastChallengeQuestion->question_id])->one();
            $allLastChallengeQuestionsCost += $lastChallengeQuestionCost->cost;
        }
        $allLastChallengeQuestionsCost = ceil($allLastChallengeQuestionsCost / 5 * intval($lastChallenge->mark));

        $finishTime = Yii::$app->getFormatter()->asTimestamp($lastChallenge->finish_time);
        $time = Yii::$app->getFormatter()->asTimestamp(time());
        $scaleValue = $time - $finishTime;
        if ($scaleValue <= 100){
            
        }

        $allDoneChallenges = Attempt::find()->where(['user_id' => Yii::$app->user->id])->all();

        $allDoneChallengesCosts = [];
        $allLastCosts = [];
        $costAmount = 0;
        $nearlyFinishCostAmount = 0;
        $finishCostAmount = 0;
        for ($i =0; $i < count($allDoneChallenges); $i++){
            $cost = ChallengeHasQuestion::find()->select('question_id')->where(['challenge_id' => $allDoneChallenges[$i]->challenge_id])->all();
            $allDoneChallengesMarks = Attempt::find()->select('mark')->where(['user_id' => Yii::$app->user->id])->all();
            for ($o = 0; $o < count($cost); $o++){
                $lastCost = Question::find()->select('cost')->where(['id' => $cost[$o]->question_id])->all();
                foreach ($lastCost as $item) {
                    $allLastCosts[] = $item->cost;
                    $costAmount += $item->cost;
                    $nearlyFinishCostAmount += $item->cost;
                }
            }
            $finishCostAmount += ceil($nearlyFinishCostAmount / 5 * intval($allDoneChallengesMarks[$i]->mark));
            $nearlyFinishCostAmount = 0;
        }
        //$finishCostAmount = round($finishCostAmount);

        $foods = Food::find()->all();

        $challenges = [];
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }

        $attempt = Attempt::find()->select(['finish_time'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'feedingTests' => $feedingTests,
            'foods' => $foods,
            'challenges' => $challenges,
            'lastChallenge' => $lastChallenge,
            'finishTime' => $finishTime,
            'allLastChallengeQuestionsCost' => $allLastChallengeQuestionsCost,
            'allDoneChallenges' => $allDoneChallenges,
            'allDoneChallengesCosts' => $allDoneChallengesCosts,
            'costAmount' => $costAmount,
            'finishCostAmount' => $finishCostAmount,
            'scaleValue' => $scaleValue,
            'attempt' => $attempt
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
