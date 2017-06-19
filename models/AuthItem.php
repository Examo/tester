<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class AuthItem extends \app\models\ar\AuthItem
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'type' => Yii::t('user', 'Type'),
            'description' => Yii::t('user', 'Description'),
            'rule_name' => Yii::t('user', 'Rule Name'),
            'data' => Yii::t('user', 'Data'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }

}
