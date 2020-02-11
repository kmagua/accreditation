<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CompanyStaff;

/**
 * CompanyStaffSearch represents the model behind the search form of `app\models\CompanyStaff`.
 */
class CompanyStaffSearch extends CompanyStaff
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'national_id'], 'integer'],
            [['first_name', 'last_name', 'kra_pin', 'gender', 'dob', 'disability_status', 'title', 'staff_type', 'status', 'date_created', 'last_updated'], 'safe'],
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
        $query = CompanyStaff::find();

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
            'company_id' => $this->company_id,
            'national_id' => $this->national_id,
            'dob' => $this->dob,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'kra_pin', $this->kra_pin])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'disability_status', $this->disability_status])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'staff_type', $this->staff_type])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
