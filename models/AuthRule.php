<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class AuthRule extends \app\models\ar\AuthRule
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'data' => Yii::t('user', 'Data'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }
}
