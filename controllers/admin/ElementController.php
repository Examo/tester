<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\Element;
use app\models\search\ElementSearch;
use Yii;

/**
 * ElementController implements the CRUD actions for Element model.
 */
class ElementController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return Element::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ElementSearch::className();
    }
}
