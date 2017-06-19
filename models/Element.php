<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class Element extends \app\models\ar\Element
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}
