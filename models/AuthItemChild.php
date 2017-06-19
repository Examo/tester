<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class AuthItemChild extends \app\models\ar\AuthItemChild
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parent' => Yii::t('user', 'Parent'),
            'child' => Yii::t('user', 'Child'),
        ];
    }
}
