<?php

namespace app\models;

use Yii;

/**
 * @inheritdoc
 */
class ChallengeMark extends \app\models\ar\ChallengeMark
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'challenge_id' => Yii::t('challenge', 'Challenge'),
            'value_from' => Yii::t('challengeMark', 'Value From'),
            'value_to' => Yii::t('challengeMark', 'Value To'),
            'mark' => Yii::t('challengeMark', 'Mark'),
        ];
    }
}
