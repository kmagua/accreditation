<?php

namespace app\modules\professional\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\professional\models\Education;

/**
 * EducationSearch represents the model behind the search form of `app\modules\professional\models\Education`.
 */
class EducationSearch extends Education
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level_id', 'user_id'], 'integer'],
            [['course', 'institution', 'completion_date', 'date_created', 'date_modified'], 'safe'],
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
        $query = Education::find();

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
            'level_id' => $this->level_id,
            'completion_date' => $this->completion_date,
            'date_created' => $this->date_created,
            'date_modified' => $this->date_modified,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'course', $this->course])
            ->andFilterWhere(['like', 'institution', $this->institution]);

        return $dataProvider;
    }
}
