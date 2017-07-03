<?php
namespace app\controllers;
use app\models\Learn;
use Yii;
use yii\web\Controller;

class LearnController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $learning = new Learn();
        return $this->render('index', ['learning' => $learning]);
    }
}