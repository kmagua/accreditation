<?php

namespace app\modules\professional\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\professional\models\RenewalCpd;

/**
 * RenewalCpdSearch represents the model behind the search form of `app\modules\professional\models\RenewalCpd`.
 */
class RenewalCpdSearch extends RenewalCpd
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'renewal_id'], 'integer'],
            [['type', 'description', 'start_date', 'end_date', 'upload', 'date_created', 'last_modified'], 'safe'],
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
        $query = RenewalCpd::find();

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
            'renewal_id' => $this->renewal_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'date_created' => $this->date_created,
            'last_modified' => $this->last_modified,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'upload', $this->upload]);

        return $dataProvider;
    }
}
