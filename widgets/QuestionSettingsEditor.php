<?php

namespace app\widgets;

use app\models\QuestionType;
use Yii;
use yii\web\AssetManager;
use yii\widgets\InputWidget;

/**
 * Question Editor Widget
 * @package app\widgets
 */
class QuestionSettingsEditor extends InputWidget
{

    /**
     * @var string jQuery-selector for question type switcher
     */
    public $switcher = '#type_id';

    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $types = QuestionType::getList([], 'sysname');

        // register assets
        $this->getView()->registerJsFile(
            $this->publishAsset('js/question-settings-editor.js')
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/question-settings-editor.css')
        );
        foreach ($types as $id => $sysname) {
            $this->getView()->registerJsFile(
                $this->publishAsset('js/' . $sysname . '.js')
            );
            $this->getView()->registerCssFile(
                $this->publishAsset('css/' . $sysname . '.css')
            );
        }

        // render widget
        echo $this->render('questionSettingsEditor/default', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'data' => $this->model->{$this->attribute},
            'switcher' => $this->switcher,
            'types' => $types
        ]);
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'questionSettingsEditor' . DIRECTORY_SEPARATOR;
    }

    /**
     * Publish widget asset
     * @param $src string
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