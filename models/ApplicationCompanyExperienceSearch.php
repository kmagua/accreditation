<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ApplicationCompanyExperience;

/**
 * ApplicationCompanyExperienceSearch represents the model behind the search form of `app\models\ApplicationCompanyExperience`.
 */
class ApplicationCompanyExperienceSearch extends ApplicationCompanyExperience
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'application_id', 'experience_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
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
        $query = ApplicationCompanyExperience::find();

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
            'experience_id' => $this->experience_id,
            'date_created' => $this->date_created,
            'last_updated' => $this->last_updated,
        ]);

        return $dataProvider;
    }
}
