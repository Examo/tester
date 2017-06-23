<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    public $layout = 'metronic';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if ( \Yii::$app->user->isGuest ) {
            $this->layout = 'metronic_sidebar';
            return $this->render('index');
        } else {
            return $this->redirect( Url::to(['home/index']) );
        }

    }

}
