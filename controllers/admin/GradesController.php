<?php

namespace app\controllers\admin;

use app\models\ContactForm;
use app\models\Course;
use app\models\CourseSubscription;
use app\models\LoginForm;
use app\models\search\CourseSearch;
use app\models\User;
use Yii;
use yii\web\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class GradesController extends Controller
{
    public $layout = 'main';

    /**
     * @inheritdoc
     */
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

    public function actionIndex()
    {
        $notice = 'Здравствуй, администратор! До свидания, администратор!';

        return $this->render('index', [
            'notice' => $notice,
        ]);
    }

    public function actionList()
    {
        $courseSubscriptions = CourseSubscription::find()->all();
        $user = User::findOne(Yii::$app->request->get('user_id'));

        return $this->render('list', [
            'courseSubscriptions' => $courseSubscriptions,
            'user' => $user
        ]);
    }

}