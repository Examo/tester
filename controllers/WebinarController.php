<?php

namespace app\controllers;

use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\Webinar;
use dektrium\user\models\UserSearch;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\models\Event;

/**
 * Class WebinarController
 * @package app\controllers
 */
class WebinarController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'metronic_sidebar';
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionWebinar()
    {
        $this->layout = 'metronic_sidebar';
        $webinarID = Yii::$app->request->get('id');

        return $this->render(
            'webinar',
            [
                'webinarID' => $webinarID
            ]
        );
    }

}
