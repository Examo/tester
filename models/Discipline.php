<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 * @package app\models
 */
class Discipline extends \app\models\ar\Discipline {
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'position' => Yii::t('app', 'Position'),
        ];
    }
}