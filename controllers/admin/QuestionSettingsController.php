<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\QuestionSettings;
use app\models\search\QuestionSettingsSearch;
use yii\helpers\Json;
use Yii;

/**
 * QuestionSettingsController implements the CRUD actions for Question model.
 */
class QuestionSettingsController extends BaseAdminCrudController
{
    /**
     * @var string
     */
    public $layout = 'OLDMAIN/main';

    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return QuestionSettings::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return QuestionSettingsSearch::className();
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

        $data = Yii::$app->request->post();
        if ($data) {
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
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
        if ($data) {
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


    /**
     * @param int $id
     * @return mixed|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete($id)
    {
        /** @var QuestionSettings $model */
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
