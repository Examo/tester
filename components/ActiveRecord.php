<?php

namespace app\components;

use Yii;
use Yii\helpers\ArrayHelper;

/**
 * ActiveRecord class with extended abilities
 * @package app\components
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * Get records as id => $field array
     * @param ActiveRecord[]|int[] $exclude Elements to exclude
     * @param string $field
     * @return array
     */
    public static function getList($exclude = [], $field = 'name')
    {
        $ids = [];
        foreach ($exclude as $item) {
            $ids[] = is_object($item) ? $item->id : $item;
        }

        $condition = count($ids) ? ['not in', 'id', $ids] : '';

        return ArrayHelper::map(static::find()->where($condition)->all(), 'id', $field);
    }
}