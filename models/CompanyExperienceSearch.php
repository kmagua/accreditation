<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyExperience;

/**
 * CompanyExperienceSearch represents the model behind the search form of `app\models\CompanyExperience`.
 */
class CompanyExperienceSearch extends CompanyExperience
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['id','organization_type', 'project_name', 'start_date', 'end_date', 'status', 'attachment', 'date_created', 'last_updated'], 'safe'],
            [['project_cost'], 'number'],
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
        $query = CompanyExperience::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            throw new \Exception(print_r($this->errors, true));
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'company_id' => $this->company_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'project_cost' => $this->project_cost,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'organization_type', $this->organization_type])
            ->andFilterWhere(['like', 'project_name', $this->project_name])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'attachment', $this->attachment]);

        return $dataProvider;
    }
}
