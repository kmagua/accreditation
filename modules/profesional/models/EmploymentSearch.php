<?php

namespace app\modules\profesional\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\profesional\models\Employment;

/**
 * EmploymentSearch represents the model behind the search form of `app\modules\profesional\models\Employment`.
 */
class EmploymentSearch extends Employment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['organisation_name', 'organisation_email', 'organisation_phone', 'job_title', 'role', 'postal_address', 'website', 'supervisor_name', 'supervisor_designation', 'supervisor_email', 'supervisor_phone', 'date_created', 'date_modified'], 'safe'],
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
        $query = Employment::find();

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
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'organisation_name', $this->organisation_name])
            ->andFilterWhere(['like', 'organisation_email', $this->organisation_email])
            ->andFilterWhere(['like', 'organisation_phone', $this->organisation_phone])
            ->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'role', $this->role])
            ->andFilterWhere(['like', 'postal_address', $this->postal_address])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'supervisor_name', $this->supervisor_name])
            ->andFilterWhere(['like', 'supervisor_designation', $this->supervisor_designation])
            ->andFilterWhere(['like', 'supervisor_email', $this->supervisor_email])
            ->andFilterWhere(['like', 'supervisor_phone', $this->supervisor_phone]);

        return $dataProvider;
    }
}
