<?php
namespace app\controllers;
use app\models\Game;
use Yii;
use yii\web\Controller;

class GameController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $learning = new Game();
        return $this->render('index', ['learning' => $learning]);
    }
}