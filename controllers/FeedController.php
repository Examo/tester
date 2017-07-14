<?php
namespace app\controllers;
use app\models\Feed;
use Yii;
use yii\web\Controller;

class FeedController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            if ( \Yii::$app->user->isGuest ) {
                $this->redirect( ['user/login'] );
                return false;
            }

            return true;
        }

        return false;
    }

    public function actionIndex() // основной экшн
    {
        $feedingTests = new Feed();
        return $this->render('index', [
            'feedingTests' => $feedingTests
        ]);
    }
}