<?php


namespace app\controllers\admin;

use Yii;
use app\models\Event;
use yii\web\Controller;

class EventController extends Controller
{
    /**
     * Creates a new model.
     * If creation is successful, return redirect.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Event();

        if ($model->load(Yii::$app->request->post()) && !$model->save()) {
            if ($model->hasErrors()) {
                $errors = '';
                foreach ($model->getErrors() as $error) {
                    $errors .= $error . ', ';
                }
                return $errors;
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return $model;
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Update a model.
     * If creation is successful, return OK.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Event::findOne(['id' => $id]);

        if ($model->load(Yii::$app->request->post()) && !$model->save()) {
            if (Yii::$app->request->isAjax) {
                return var_dump($model->getErrors());
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return $model;
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Delete a model.
     * If creation is successful, return redirect.
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = Event::findOne(['id' => $id]);

        if ($model) {
            $model->delete();
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
            return ['id' => $id];
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}