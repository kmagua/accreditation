<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProfessionalCertification;

/**
 * ProfessionalCertificationSearch represents the model behind the search form of `app\models\ProfessionalCertification`.
 */
class ProfessionalCertificationSearch extends ProfessionalCertification
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'staff_id'], 'integer'],
            [['qualification_type', 'other_description', 'certificate', 'date_created', 'last_updated'], 'safe'],
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
        $query = ProfessionalCertification::find();

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
            'staff_id' => $this->staff_id,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        $query->andFilterWhere(['like', 'qualification_type', $this->qualification_type])
            ->andFilterWhere(['like', 'other_description', $this->other_description])
            ->andFilterWhere(['like', 'certificate', $this->certificate]);

        return $dataProvider;
    }
}
