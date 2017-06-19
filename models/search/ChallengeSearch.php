<?php

namespace app\models\search;

use app\models\Challenge;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ChallengeSearch represents the model behind the search form about `app\models\Challenge`.
 */
class ChallengeSearch extends Challenge
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'challenge_type_id', 'element_id', 'subject_id', 'grade_number', 'exercise_number', 'exercise_challenge_number'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = Challenge::find();

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
            'course_id' => $this->course_id,
            'challenge_type_id' => $this->challenge_type_id,
            'element_id' => $this->element_id,
            'subject_id' => $this->subject_id,
            'grade_number' => $this->grade_number,
            'exercise_number' => $this->exercise_number,
            'exercise_challenge_number' => $this->exercise_challenge_number,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
