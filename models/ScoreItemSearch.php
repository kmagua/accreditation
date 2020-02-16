<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ScoreItem;

/**
 * ScoreItemSearch represents the model behind the search form of `app\models\ScoreItem`.
 */
class ScoreItemSearch extends ScoreItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'maximum_score'], 'integer'],
            [['category', 'specific_item', 'score_item', 'date_created', 'last_updated'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ScoreItem::find();

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
            'maximum_score' => $this->maximum_score,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'specific_item', $this->specific_item])
            ->andFilterWhere(['like', 'score_item', $this->score_item]);

        return $dataProvider;
    }
}
