<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Application assets config
 * @package app\assets
 */
class MetronicAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\jui\JuiAsset',
    ];
}