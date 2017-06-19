<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\Question;
use app\models\search\QuestionSearch;
use Yii;

/**
 * QuestionController implements the CRUD actions for Question model.
 */
class QuestionController extends BaseAdminCrudController
{
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
