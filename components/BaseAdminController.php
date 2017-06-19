<?php

namespace app\components;

use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class BaseAdminController extends Controller
{

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (\Yii::$app->user->can('admin')) {
                return true;
            } else {
                throw new ForbiddenHttpException('Access denied');
            }
        }

        return false;
    }
}