<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * @inheritdoc
 */
class QuestionSettings extends \app\models\ar\QuestionSettings
{
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'name' => Yii::t('app', 'Name'),
                'type_id' => Yii::t('questionSettings', 'Question Type ID'),
                'settings' => Yii::t('questionSettings', 'Settings'),
            ]
        );
    }
}
