<?php
namespace app\controllers;
use app\models\Feeding;
use Yii;
use yii\web\Controller;

class FeedingController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $feedingTests = new Feeding();
        return $this->render('index', ['feedingTests' => $feedingTests]);
    }
}