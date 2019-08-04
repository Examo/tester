<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\Question;
use app\models\search\QuestionSearch;
use yii\helpers\Json;
use Yii;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends BaseAdminCrudController
{
    public $layout = 'OLDMAIN/main';
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Question::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return QuestionSearch::className();
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
        $model->right_points = 0;
        $model->wrong_points = 0;

        $data = Yii::$app->request->post();
        if ($data) {
            if ((int)$data['Question']['question_type_id'] === 8) {
                $data['Question']['comment'] = Json::encode($data['Question']['comment']);
                $data['Question']['hint'] = Json::encode($data['Question']['hint']);
            } else {
                $data['Question']['comment'] = implode($data['Question']['comment']);
                $data['Question']['hint'] = implode($data['Question']['hint']);
            }
            Yii::$app->request->setBodyParams($data);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveModel($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            foreach ($model->errors as $error) {
                Yii::$app->session->setFlash(
                    'error',
                    $error[0]
                );
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
        if ($data) {
            if ((int)$data['Question']['question_type_id'] === \app\models\QuestionType::TYPE_THREE_QUESTION) {
                $data['Question']['comment'] = Json::encode($data['Question']['comment']);
                $data['Question']['hint'] = Json::encode($data['Question']['hint']);
            } else {
                $data['Question']['comment'] = implode($data['Question']['comment']);
                $data['Question']['hint'] = implode($data['Question']['hint']);
            }
            Yii::$app->request->setBodyParams($data);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveModel($model)) {
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


    public function actionDelete($id)
    {
        /** @var Question $model */
        $model = $this->findModel($id);

        $challenges = $model->getChallenges()->all();

        if ( count($challenges) && !Yii::$app->request->post('confirm') ) {
            return $this->render('delete', [
                'model' => $model,
                'challenges' => $challenges
            ]);
        }

        return parent::actionDelete($id);
    }


}
