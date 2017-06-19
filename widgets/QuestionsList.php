<?php

namespace app\widgets;

use yii;
use yii\web\AssetManager;
use yii\widgets\InputWidget;

/**
 * Questions List widget
 * @package app\widgets
 */
class QuestionsList extends InputWidget
{

    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @var string
     */
    public $id = false;

    /**
     * @var string Input name
     */
    public $name = false;

    /**
     * @var string QuestionSelection widget selector
     */
    public $modalSelector = '';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $questions = $this->model->{$this->attribute};

        $data = [];
        foreach ($questions as $item) {
            $data[$item->question->id] = $item->question->text;
        }

        // register assets
        $this->getView()->registerJsFile(
            $this->publishAsset('js/questions-list.js')
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/questions-list.css')
        );

        // render
        echo $this->render('questionsList/default', [
            'id' => $this->id,
            'data' => $data,
            'name' => $this->name ? $this->name : \yii\helpers\Html::getInputName($this->model, $this->attribute),
            'modalSelector' => $this->modalSelector,
        ]);
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'questionsList' . DIRECTORY_SEPARATOR;
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