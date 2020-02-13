<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyProfile;

/**
 * CompanyProfileSearch represents the model behind the search form of `app\models\CompanyProfile`.
 */
class CompanyProfileSearch extends CompanyProfile
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['business_reg_no', 'company_name', 'registration_date', 'county', 'town', 'building', 'floor', 'telephone_number', 'company_email', 'company_type_id', 'postal_address', 'company_categorization', 'date_created', 'last_updated'], 'safe'],
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
        $query = CompanyProfile::find();

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
            'registration_date' => $this->registration_date,
            'user_id' => $this->user_id,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
            'company_type_id' => $this->company_type_id,
        ]);

        $query->andFilterWhere(['like', 'business_reg_no', $this->business_reg_no])
            ->andFilterWhere(['like', 'company_name', $this->company_name])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'town', $this->town])
            ->andFilterWhere(['like', 'building', $this->building])
            ->andFilterWhere(['like', 'floor', $this->floor])
            ->andFilterWhere(['like', 'telephone_number', $this->telephone_number])
            ->andFilterWhere(['like', 'company_email', $this->company_email])
            ->andFilterWhere(['like', 'postal_address', $this->postal_address])
            ->andFilterWhere(['like', 'company_categorization', $this->company_categorization]);

        return $dataProvider;
    }
}
