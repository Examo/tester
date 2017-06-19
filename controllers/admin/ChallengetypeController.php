<?php

namespace app\controllers\admin;

use app\components\BaseAdminCrudController;
use app\models\ChallengeType;
use app\models\search\ChallengeTypeSearch;
use Yii;

/**
 * ChallengeTypeController implements the CRUD actions for ChallengeType model.
 */
class ChallengetypeController extends BaseAdminCrudController
{

    /**
     * @inheritdoc
     */
    protected function getModelClass()
    {
        return ChallengeType::className();
    }

    /**
     * @inheritdoc
     */
    protected function getSearchModelClass()
    {
        return ChallengeTypeSearch::className();
    }

}
