<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Application;

/**
 * ApplicationSearch represents the model behind the search form of `app\models\Application`.
 */
class ApplicationSearch extends Application
{
    public $company;
    public $accreditationType;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'company_id', 'accreditation_type_id', 'user_id'], 'integer'],
            [['financial_status_amount'], 'number'],
            [['financial_status_link', 'status', 'declaration', 'date_created', 'last_updated', 'company', 'accreditationType'], 'safe'],
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
        $query = Application::find();

        // add conditions that should always apply here

        $query->joinWith(['accreditationType', 'company']);
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
            'accreditation_type_id' => $this->accreditation_type_id,
            'financial_status_amount' => $this->financial_status_amount,
            'user_id' => $this->user_id,
            //'date_created' => $this->date_created,
            //'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'financial_status_link', $this->financial_status_link])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'company_profile.company_name', $this->company])
            ->andFilterWhere(['like', 'accreditation_type.name', $this->accreditationType])
            ->andFilterWhere(['like', 'declaration', $this->declaration]);

        return $dataProvider;
    }
}
