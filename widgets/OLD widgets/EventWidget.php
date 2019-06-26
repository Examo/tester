<?php

namespace app\widgets;

use Yii;
use yii\web\AssetManager;
use yii\web\View;
use app\models\Event;

/**
 * Class EventWidget
 * @package app\widgets
 */
class EventWidget extends \yii\base\Widget
{
    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @var string Input name
     */
    public $name = 'event';

    /**
     * @var string
     */
    public $course_id = '';

    public function run()
    {
        $model = new Event();

        if (!empty($this->course_id)) {
            $events = Event::find()->where(['course_id' => $this->course_id])->all();
            $model->course_id = $this->course_id;
        } else {
            $events = Event::find()->all();
        }

        $colorSetting = [
            [
            '#0071c5' => 'Темно-синий',
            '#40E0D0' => 'Бирюзовый',
            '#008000' => 'Зеленый',
            '#FFD700' => 'Желтый',
            '#FF8C00' => 'Оранжевый',
            '#FF0000' => 'Красный',
            '#000'    => 'Черный',
            ],
            ['options' => [
                'id' => 'color',
                '#0071c5' => ['style' => 'color:#0071c5'],
                '#40E0D0' => ['style' => 'color:#40E0D0'],
                '#008000' => ['style' => 'color:#008000'],
                '#FFD700' => ['style' => 'color:#FFD700'],
                '#FF0000' => ['style' => 'color:#FF0000'],
                '#000' => ['style' => 'color:#000'],
                ],
             'prompt' => 'Выбрать цвет'
            ]
        ];


        // register assets
        $this->getView()->registerJsFile(
            $this->publishAsset('js/jquery.js'),
            ['position' => View::POS_END]
        ); $this->getView()->registerJsFile(
            $this->publishAsset('js/bootstrap.js'),
            ['position' => View::POS_END]
        ); $this->getView()->registerJsFile(
            $this->publishAsset('js/moment.min.js'),
            ['position' => View::POS_END]
        );
        $this->getView()->registerJsFile(
            $this->publishAsset('js/fullcalendar.min.js'),
            ['position' => View::POS_END]
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/fullcalendar.css'),
            ['depends' => 'app\assets\AppAsset']
        );

        // render widget
        echo $this->render('eventWidget/default', [
            'name' => $this->name,
            'events' => $events,
            'model' => $model,
            'colorSetting' => $colorSetting
        ]);
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'eventWidget' . DIRECTORY_SEPARATOR;
    }

    /**
     * Publish widget asset
     * @param $src Filename
     * @return string URL
     */
    public function publishAsset($src)
    {
        $path = Yii::getAlias($this->getAssetsPath() . $src);
        if (!$this->assetManager) {
            $this->assetManager = new AssetManager();
        }
        $return = $this->assetManager->publish($path);
        return $return[1];
    }
}