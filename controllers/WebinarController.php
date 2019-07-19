<?php

namespace app\controllers;

use app\widgets\WebinarChallengesWidget;
use Yii;

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

    /**
     * @return string
     * @throws \Exception
     */
    public function actionWidget()
    {
        $this->layout = false;
        $webinarID = Yii::$app->request->get('id');

        return WebinarChallengesWidget::widget(['webinarId' => $webinarID]);
    }
}
