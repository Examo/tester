<?php

namespace app\controllers\admin;

use app\models\ElementsItem;
use app\models\search\ElementsItemSearch;
use app\components\BaseAdminCrudController;

class ElementsItemController extends BaseAdminCrudController
{
    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return ElementsItem::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ElementsItemSearch::className();
    }
}