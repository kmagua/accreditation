<?php

namespace app\modules\profesional\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\profesional\models\PersonalInformation;

/**
 * PersonalInformationSearch represents the model behind the search form of `app\modules\profesional\models\PersonalInformation`.
 */
class PersonalInformationSearch extends PersonalInformation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['idno', 'first_name', 'last_name', 'other_names', 'date_of_birth', 'gender', 'phone', 'nationality', 'county', 'postal_address', 'date_created', 'date_modified'], 'safe'],
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
        $query = PersonalInformation::find();

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
            'date_of_birth' => $this->date_of_birth,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'idno', $this->idno])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'other_names', $this->other_names])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'nationality', $this->nationality])
            ->andFilterWhere(['like', 'county', $this->county])
            ->andFilterWhere(['like', 'postal_address', $this->postal_address]);

        return $dataProvider;
    }
}
