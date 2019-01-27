<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\helpers\QuestionChooser;
use app\helpers\Subset;
use app\models\ChallengeHasQuestion;
use yii\helpers\Json;
use app\models\ar\ChallengeFood;
use app\models\ar\Food;
use app\models\Challenge;
use app\models\ChallengeMark;
use app\models\ChallengeSettings;
use app\models\Course;
use app\models\Question;
use app\models\ElementsItem;
use app\models\search\ChallengeSearch;
use dektrium\user\models\UserSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\User;

/**
 * ChallengeController implements the CRUD actions for Challenge model.
 */
class ChallengeController extends BaseAdminCrudController
{
    public $layout = 'main';

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Challenge::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ChallengeSearch::className();
    }

    /**
     * @inheritdoc
     */
    protected function saveModel($model)
    {
        $model->setMode(Yii::$app->request->post('mode'), Yii::$app->request->post());

        Subset::save(
            ChallengeMark::className(),
            Yii::$app->request->post(),
            ['challenge_id' => $model->id]
        );

        $modelSettings = $model->settings ? $model->settings : new ChallengeSettings();
        $modelSettings->load(Yii::$app->request->post());
        $modelSettings->challenge_id = $model->id;
        return $modelSettings->save();
    }

    /**
     * Generate random questions sequence
     * @return array
     */
    public function actionGenerate()
    {
        $generator = new QuestionChooser();

        foreach (Yii::$app->request->post('rules') as $rule) {
            $generator->addRule($rule['type'], $rule['count']);
        }

        $questions = Question::find()
            ->where([
                'id' => $generator->generate()
            ])
            ->all();

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ArrayHelper::map($questions, 'id', 'text');
    }

    public function actionCreate()
    {
        $class = $this->getModelClass();
        $model = new $class();

        $food = new Food();

        $challengeFood = new ChallengeFood();

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveModel($model)) {
          //  $challengeFood->id = $model->id;
         //   $challengeFood->food_id = $model->food_id;
         //   $challengeFood->challenge_id = $model->id;
         //   $challengeFood->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'food' => $food,
                'challengeFood' => $challengeFood
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $challengeFood = ChallengeFood::find()->where(['id' => $id])->one();
        $food = Food::find()->all();

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveModel($model)) {
       //     $challengeFood->id = $model->id;
       //     $challengeFood->food_id = $model->food_id;
       //     $challengeFood->challenge_id = $model->id;
       //     $challengeFood->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'food' => $food,
                'challengeFood' => $challengeFood
            ]);
        }
    }

    public function actionView($id)
    {
        $food = ElementsItem::find()->select('name')->where(['id' => $id])->one();
        return $this->render('view', [
            'model' => $this->findModel($id),
            //'challengeFood' => ChallengeFood::find()->where(['id' => $id])->one(),
            'food' => $food
        ]);
    }

    public function actionWeek()
    {
        //$this->layout = 'metronic_sidebar';
        $course = Course::findOne(Yii::$app->request->get('course_id')) ;

        if (!empty($course) && !empty($course->challenges)) {
            return $this->render('week',
                [
                    'course' => $course,
                    'challenges' => $course->challenges,
                ]);
        } else {
            throw new NotFoundHttpException('Тестов по этому курсу пока ещё не существует!');
        }
    }

    public function actionWeeks()
    {
        //$this->layout = 'metronic_sidebar';
        $courses = Course::find()->all();

        if (!empty($courses)) {
            return $this->render('weeks/index',
                [
                    'courses' => $courses,
                ]);
        } else {
            throw new NotFoundHttpException('Тестов по этому курсу пока ещё не существует!');
        }
    }

    public function actionStat()
    {
        //$this->layout = 'metronic_sidebar';
        $course = Course::find()->all();
        $challenge = Challenge::findOne(Yii::$app->request->get('challenge_id'));
        $usernames = \dektrium\user\models\User::find()->all();

        $questions = ChallengeHasQuestion::find()->innerJoinWith('question')->where(['challenge_has_question.challenge_id' => $challenge->id])->all();
        //\yii\helpers\VarDumper::dump($questions[0]['question'], 10, true);

        if (!empty($challenge)) {
            return $this->render('stat',
                [
                    'course' => $course,
                    'challenge' => $challenge,
                    'usernames' => $usernames,
                    'questions' => $questions
                ]);
        } else {
            throw new NotFoundHttpException('Теста с указанным ID не существует!');
        }
    }

    public function actionElements()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $result = [];
                $element_id[] = $parents[0];
                $model = new ElementsItem;
                $out = $model->find()->where(['element_id' => $element_id])->all();

                foreach ($out as $key => $value) {
                    $result[] = ['id' => $key, 'name' => $value];
                }

                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

}
