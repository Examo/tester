<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\Discipline;
use app\models\search\DisciplineSearch;
use Yii;

/**
 * DisciplineController implements the CRUD actions for Discipline model.
 */
class DisciplineController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Discipline::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return DisciplineSearch::className();
    }
}
