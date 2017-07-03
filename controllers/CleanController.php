<?php
namespace app\controllers;
use app\models\Clean;
use Yii;
use yii\web\Controller;

class CleanController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $learning = new Clean();
        return $this->render('index', ['learning' => $learning]);
    }
}