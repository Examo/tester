<?php

namespace app\models\ar;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ar\Feed;

/**
 * FeedSearch represents the model behind the search form about `app\models\ar\Feed`.
 */
class FeedSearch extends Feed
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_subscription_id', 'user_id', 'week_id'], 'integer'],
            [['challenges_done'], 'safe'],
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
        $query = Feed::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'course_subscription_id' => $this->course_subscription_id,
            'user_id' => $this->user_id,
            'week_id' => $this->week_id,
        ]);

        $query->andFilterWhere(['like', 'challenges_done', $this->challenges_done]);

        return $dataProvider;
    }
}
