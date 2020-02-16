<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApplicationScore;

/**
 * ApplicationScoreSearch represents the model behind the search form of `app\models\ApplicationScore`.
 */
class ApplicationScoreSearch extends ApplicationScore
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'application_id', 'score_item_id', 'score', 'user_id', 'committee_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
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
        $query = ApplicationScore::find();

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
            'application_id' => $this->application_id,
            'score_item_id' => $this->score_item_id,
            'score' => $this->score,
            'user_id' => $this->user_id,
            'committee_id' => $this->committee_id,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        return $dataProvider;
    }
}
