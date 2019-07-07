<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
namespace app\helpers;
/**
 * Json is a helper class providing JSON data encoding and decoding.
 * It enhances the PHP built-in functions `json_encode()` and `json_decode()`
 * by supporting encoding JavaScript expressions and throwing exceptions when decoding fails.
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Json extends \yii\helpers\BaseJson
{
    /**
     * @param $str
     * @return mixed|string
     */
    public static function encodeForJsParse($str)
    {
        if (json_decode($str)) {
            $result = $str;
        } else {
            $result = $str ? str_replace(array("\r\n", "\r", "\n"), '', \yii\helpers\Html::encode($str)) : "''";
        }

        return $result;
    }
}