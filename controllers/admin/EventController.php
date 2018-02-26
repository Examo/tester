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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Update a model.
     * If creation is successful, return OK.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = Event::findOne(['id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                return 'OK';
            }
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if (Yii::$app->request->isAjax) {
                return var_dump($model->getErrors());
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
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

        return $this->redirect(Yii::$app->request->referrer);
    }
}