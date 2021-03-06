<?php

namespace app\models\search;

use app\models\Answer;
use app\models\QuestionType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ResultSearch represents the model behind the search form about `app\models\Question`.
 */
class ResultSearch extends Answer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Answer::find()->innerJoinWith('question')->innerJoinWith('attempt')
            ->where(['question.question_type_id' => QuestionType::TYPE_TEXT_LONG]);

//        var_dump($query->all());
//        die();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

//        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'question_type_id' => $this->question_type_id,
//            'cost' => $this->cost,
//        ]);
//
//        $query->andFilterWhere(['like', 'text', $this->text])
//            ->andFilterWhere(['like', 'data', $this->data])
//            ->andFilterWhere(['like', 'hint', $this->hint])
//            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
