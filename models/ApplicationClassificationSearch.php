<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApplicationClassification;

/**
 * ApplicationClassificationSearch represents the model behind the search form of `app\models\ApplicationClassification`.
 */
class ApplicationClassificationSearch extends ApplicationClassification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'application_id', 'icta_committee_id', 'status', 'user_id'], 'integer'],
            [['score'], 'number'],
            [['classification', 'rejection_comment', 'date_created', 'last_updated'], 'safe'],
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
        $query = ApplicationClassification::find();

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
            'icta_committee_id' => $this->icta_committee_id,
            'score' => $this->score,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'classification', $this->classification])
            ->andFilterWhere(['like', 'rejection_comment', $this->rejection_comment]);

        return $dataProvider;
    }
}
