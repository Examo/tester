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
    const TYPE_SELECT_ONE       = 1;
    const TYPE_SELECT_MULTIPLE  = 2;
    const TYPE_TEXT_SHORT       = 3;
    const TYPE_TEXT_LONG        = 4;
    const TYPE_DICTATION        = 5;
    const TYPE_ASSOC            = 6;
    const TYPE_ASSOC_TABLE      = 7;

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'sysname' => Yii::t('questionType', 'Sysname'),
        ];
    }

}
