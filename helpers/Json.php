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
        $result = '';

        if (json_decode($str)) {
            $arr = json_decode($str);
            foreach ($arr as &$ar) {
                $ar =  htmlentities(html_entity_decode($ar, ENT_QUOTES), ENT_QUOTES, "UTF-8");
            }
            $result = addslashes(json_encode($arr));
        } else {
            if (!empty($str)) {
                $result = $str ? str_replace(array("\r\n", "\r", "\n"), '', \yii\helpers\Html::encode($str)) : "''";
            }
        }

        return $result;
    }
}