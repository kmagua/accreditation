<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PreSystemAccreditedCompanies;

/**
 * PreSystemAccreditedCompaniesSearch represents the model behind the search form of `app\models\PreSystemAccreditedCompanies`.
 */
class PreSystemAccreditedCompaniesSearch extends PreSystemAccreditedCompanies
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cert_reference'], 'integer'],
            [['company_name', 'date_of_accreditation', 'valid_till', 'service_category', 'to_go', 'icta_grade'], 'safe'],
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
        $query = PreSystemAccreditedCompanies::find();

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
            'cert_reference' => $this->cert_reference,
        ]);

        $query->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'date_of_accreditation', $this->date_of_accreditation])
            ->andFilterWhere(['like', 'valid_till', $this->valid_till])
            ->andFilterWhere(['like', 'service_category', $this->service_category])
            ->andFilterWhere(['like', 'to_go', $this->to_go])
            ->andFilterWhere(['like', 'icta_grade', $this->icta_grade]);

        return $dataProvider;
    }
}
