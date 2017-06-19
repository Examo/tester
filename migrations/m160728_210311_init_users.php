<?php

use yii\db\Migration;
use dektrium\user\models\User;

class m160728_210311_init_users extends Migration
{
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // init admin role
        $role = $auth->createRole('admin');
        $auth->add($role);

        // create admin
        $user = Yii::createObject([
            'class'    => User::className(),
            'scenario' => 'create',
            'email'    => 'admin@localho.st',
            'username' => 'admin',
            'password' => '123456',
        ]);

        if ( $user->create() ) {
            $auth->assign($role, $user->getId());
        } else {
            return false;
        }
    }

    public function safeDown()
    {
        Yii::$app->authManager->removeAll();
    }
}
