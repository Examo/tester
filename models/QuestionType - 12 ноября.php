<?php

namespace app\models;

use Yii;
use yii\helpers\Json;

/**
 * @inheritdoc
 */
class QuestionType extends \app\models\ar\QuestionType
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'sysname' => Yii::t('questionType', 'Sysname'),
        ];
    }

}
