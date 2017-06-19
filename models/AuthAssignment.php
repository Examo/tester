<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class AuthAssignment extends \app\models\ar\AuthAssignment
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_name' => Yii::t('user', 'Item Name'),
            'user_id' => Yii::t('user', 'User'),
            'created_at' => Yii::t('user', 'Created At'),
        ];
    }
}
