<?php

namespace app\widgets;

use app\models\search\QuestionSearch;
use yii;
use yii\base\Widget;

/**
 * Question Selection popup
 * @package app\widgets
 */
class QuestionSelection extends Widget
{

    public $id = false;

    public $pageSize = 10;

    /**
     * @inheritdoc
     */
    public function run()
    {
        // questions
        $searchModel = new QuestionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = $this->pageSize;

        // render
        echo $this->render('questionSelection/default', [
            'id' => $this->id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}