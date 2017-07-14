<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\helpers\QuestionChooser;
use app\helpers\Subset;
use app\models\Challenge;
use app\models\ChallengeMark;
use app\models\ChallengeSettings;
use app\models\Course;
use app\models\Question;
use app\models\search\ChallengeSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * ChallengeController implements the CRUD actions for Challenge model.
 */
class ChallengeController extends BaseAdminCrudController
{

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

}
