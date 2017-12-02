<?php

namespace app\models;

use Yii;

class ElementsItem extends \app\models\ar\ElementsItem
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Название предмета'),
            'element_id' => Yii::t('app', 'Игровые элементы'),
        ];
    }
}