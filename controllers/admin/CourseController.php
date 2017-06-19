<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\helpers\Subset;
use app\models\Course;
use app\models\search\CourseSearch;
use app\models\Subject;
use Yii;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Course::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return CourseSearch::className();
    }

    /**
     * @inheritdoc
     */
    public function saveModel($model)
    {
        Subset::save(
            Subject::className(),
            Yii::$app->request->post(),
            ['course_id' => $model->id]
        );

        return true;
    }
}
