<?php

namespace app\helpers;

/**
 * SubsetWidget result saver
 * @package app\helpers
 */
class Subset
{

    /**
     * Save child models in MANY-TO-MANY relation
     * @param $class Child class name
     * @param array Form data
     * @param array $defaults Important attributes values (e.g. FK value)
     * @param string $formName
     * @param string $idField
     */
    public static function save($class, $data, $defaults = [], $formName = null, $idField = 'id')
    {
        $formName = is_null($formName) ? (new $class)->formName() : $formName;

        $data = isset($data[$formName]) ? $data[$formName] : array();

        $count = count(reset($data));

        $models = [];
        if (isset($data[$idField])) {
            $ids = $data[$idField];
            $models = $class::find()
                ->where(['id' => $ids])
                ->indexBy($idField)
                ->all();
        }

        for ($i = 0; $i < $count; $i++) {
            if (isset($data[$idField]) && isset($models[$data[$idField][$i]])) {
                $model = $models[$data[$idField][$i]];
            } else {
                $model = new $class;
            }

            foreach ($data as $attribute => $values) {
                if ($attribute == $idField) {
                    continue;
                }

                $model->{$attribute} = $values[$i];
            }

            foreach ($defaults as $attribute => $value) {
                $model->{$attribute} = $value;
            }

            $model->save();
        }
    }

}