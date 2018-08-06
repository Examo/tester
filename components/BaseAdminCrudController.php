<?php

namespace app\components;

use yii;
use yii\db\ActiveRecord;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

abstract class BaseAdminCrudController extends BaseAdminController
{

    /**
     * Provides model class name for CRUD
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * Provides search model class name for CRUD
     * @return string
     */
    abstract protected function getSearchModelClass();

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

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActiveRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = $this->getModelClass();

        if (($model = $class::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Called after successfull model saving
     * @param ActiveRecord $model
     * @return bool
     */
    protected  function saveModel($model) {
        return true;
    }

    /**
     * Lists all models.
     * @return mixed
     */
    public function actionIndex()
    {
        $class = $this->getSearchModelClass();

        $searchModel = new $class();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single model.
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
     * Deletes an existing model.
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $class = $this->getModelClass();
        /** @var yii\base\Model $model */
        $model = new $class();

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

        if ($model->load(Yii::$app->request->post()) && $model->save() && $this->saveModel($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
	

}