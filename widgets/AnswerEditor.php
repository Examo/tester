<?php

namespace app\widgets;

use app\helpers\QuestionClientizer;
use app\models\Question;
use app\models\QuestionType;
use Yii;
use yii\web\AssetManager;
use yii\web\View;

/**
 * Answer Editor Widget
 * @package app\widgets
 */
class AnswerEditor extends \yii\base\Widget
{

    /**
     * @var AssetManager
     */
    private $assetManager;

    /**
     * @var string Input name
     */
    public $name = 'answer';

    /**
     * @var Question
     */
    public $question = null;

    /**
     * @var Answer
     */
    public $answer = null;

    /**
     * @var Immediate_result
     */
    public $immediate_result = null;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $types = QuestionType::getList([], 'sysname');

        // register assets
        $this->getView()->registerJsFile(
            $this->publishAsset('js/jquery.ui.touch.js'),
            ['position' => View::POS_BEGIN]
        );
        $this->getView()->registerJsFile(
            $this->publishAsset('js/answer-editor.js'),
            ['position' => View::POS_BEGIN]
        );
        $this->getView()->registerCssFile(
            $this->publishAsset('css/answer-editor.css')
        );
        foreach ($types as $id => $sysname) {
            $this->getView()->registerJsFile(
                $this->publishAsset('js/' . $sysname . '.js'),
                ['position' => View::POS_BEGIN]
            );
            $this->getView()->registerCssFile(
                $this->publishAsset('css/' . $sysname . '.css')
            );
        }

        // render widget
        echo $this->render('answerEditor/default', [
            'name' => $this->name,
            'types' => $types,
            'type' => $this->question->questionType->sysname,
            'data' => QuestionClientizer::prepare($this->question),
            'comment' => $this->answer ? $this->question->getComment() : null,
            'current' => $this->question->id,
            'answer' => $this->answer,
            'immediate_result' => $this->immediate_result,
        ]);
    }

    /**
     * @return string
     */
    public function getAssetsPath()
    {
        return $this->getViewPath() . DIRECTORY_SEPARATOR . 'answerEditor' . DIRECTORY_SEPARATOR;
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