<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeType extends \app\models\ar\ChallengeType
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
