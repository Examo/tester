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
class QuestionEditor extends InputWidget
{

    /**
     * @var string jQuery-selector for question type switcher
     */
    public $switcher = '#question_type_id';

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
            $this->publishAsset('js/question-editor.js')
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/question-editor.css')
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
        echo $this->render('questionEditor/default', [
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
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'questionEditor' . DIRECTORY_SEPARATOR;
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