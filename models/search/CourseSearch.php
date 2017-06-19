<?php

namespace app\models\search;

use app\models\Course;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * CourseSearch represents the model behind the search form about `app\models\Course`.
 */
class CourseSearch extends Course
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position'], 'integer'],
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
     * Search in all courses
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        return $this->getDataProvider(
            Course::find(),
            $params
        );
    }

    /**
     * Search in active courses
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchActive($user, $params)
    {
        return $this->getDataProvider(
            Course::findSubscribed($user),
            $params
        );
    }

    /**
     * Search in unsubscribed courses
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchAvailable($user, $params)
    {
        return $this->getDataProvider(
            Course::findAvailable($user),
            $params
        );
    }

    /**
     * Search in subscribed courses
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchSubscribed($user, $params)
    {
        return $this->getDataProvider(
            Course::findSubscribed($user),
            $params
        );
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param ActiveQuery $query
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    protected function getDataProvider(ActiveQuery $query, $params)
    {
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
            'position' => $this->position,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
