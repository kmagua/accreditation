<?php

namespace app\modules\profesional\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\profesional\models\ProfesionalRegBodies;

/**
 * ProfesionalRegBodiesSearch represents the model behind the search form of `app\modules\profesional\models\ProfesionalRegBodies`.
 */
class ProfesionalRegBodiesSearch extends ProfesionalRegBodies
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'membership_no', 'upload', 'date_created', 'date_modified'], 'safe'],
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
        $query = ProfesionalRegBodies::find();

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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'membership_no', $this->membership_no])
            ->andFilterWhere(['like', 'upload', $this->upload]);

        return $dataProvider;
    }
}
