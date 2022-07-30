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
            [['id', 'company_id', 'accreditation_type_id', 'user_id', 'parent_id'], 'integer'],
            [['cash_flow', 'turnover'], 'number'],
            [['financial_status_link', 'status', 'declaration', 'date_created', 'last_updated', 'company', 'accreditationType', 'status_search', 'initial_approval_date'], 'safe'],
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
            'cash_flow' => $this->cash_flow,
            'turnover' => $this->turnover,
            'user_id' => $this->user_id,
            
            //'last_updated' => $this->last_updated,
        ]);
        $query->andWhere(['is', 'parent_id', new \yii\db\Expression('NULL')]);
        $query->andFilterWhere(['like', 'financial_status_link', $this->financial_status_link])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'company_profile.company_name', $this->company])
            ->andFilterWhere(['like', 'accreditation_type.name', $this->accreditationType])
            ->andFilterWhere(['like', 'initial_approval_date', $this->initial_approval_date])
            ->andFilterWhere(['like', 'declaration', $this->declaration]);
        
        $query->orderBy("id desc");

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchRenewals($params)
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
            'cash_flow' => $this->cash_flow,
            'turnover' => $this->turnover,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            
            //'last_updated' => $this->last_updated,
        ]);
        $query->andWhere(['is not', 'parent_id', new \yii\db\Expression('NULL')]);
        $query->andFilterWhere(['like', 'financial_status_link', $this->financial_status_link])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'company_profile.company_name', $this->company])
            ->andFilterWhere(['like', 'accreditation_type.name', $this->accreditationType])
            ->andFilterWhere(['like', 'declaration', $this->declaration]);
        
        $query->orderBy("id desc");

        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function getAccreditedList($params)
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
            'cash_flow' => $this->cash_flow,
            'turnover' => $this->turnover,
            'user_id' => $this->user_id,
            
            //'last_updated' => $this->last_updated,
        ]);
        $query->andWhere(['is', 'parent_id', new \yii\db\Expression('NULL')]);
        //$query->andFilterWhere(['like', 'financial_status_link', $this->financial_status_link])
        if($this->status){
            $query->andFilterWhere(['like', 'status', $this->status]);
        }else{
            $query->andFilterWhere(['in', 'status', ['ApplicationWorkflow/completed', 'ApplicationWorkflow/renewal']]);
        }
        
        if($this->initial_approval_date){
            $dates = explode(' - ', $this->initial_approval_date);
            $query->andFilterWhere(['>=', 'initial_approval_date', $dates[0]])
                ->andFilterWhere(['<=', 'initial_approval_date', $dates[1]]);
        }
        $query->andFilterWhere(['like', 'company_profile.company_name', $this->company])
            ->andFilterWhere(['like', 'accreditation_type.name', $this->accreditationType])
            
            ->andFilterWhere(['like', 'declaration', $this->declaration]);
        
        $query->orderBy("id desc");

        return $dataProvider;
    }
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function getMyListList($params)
    {
        $sql = "SELECT app.*
        FROM `application` app 
        JOIN `application_committe_member`  acm ON app.id = acm.`application_id`
        JOIN `icta_committee_member` icm ON icm.id = acm.`committee_member_id`
        JOIN `user` usr ON usr.id = icm.`user_id`
        WHERE usr.id=:uid  AND app.status IN('ApplicationWorkflow/at-secretariat', 'ApplicationWorkflow/at-committee', 'ApplicationWorkflow/com-rejected', 'ApplicationWorkflow/pdtp-reviewed')";
        if(in_array(\Yii::$app->user->identity->group, ['Chair', 'Director'])){
            $status = (\Yii::$app->user->identity->group == 'Chair')?'ApplicationWorkflow/chair-approval':'ApplicationWorkflow/director-approval';
            $sql = "SELECT app.*
                FROM `supplier_accreditation`.`application` app WHERE app.status= '$status'";
        }
        $query = Application::findBySql($sql, [':uid' => \Yii::$app->user->identity->user_id]);

        // add conditions that should always apply here

        //$query->joinWith(['accreditationType', 'company']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $dataProvider;
    }
    
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function getListOfAssignees($params)
    {
        $sql = "SELECT COUNT(usr.id) id, CONCAT_WS(' ', usr.first_name, usr.last_name) previous_category, CASE 
               WHEN app.status IN('ApplicationWorkflow/at-secretariat', 'ApplicationWorkflow/at-committee') THEN 'Pending review'
               -- WHEN app.status= THEN 'Pending review'
               ELSE 'Reviewed'
               END AS status_search
FROM `supplier_accreditation`.`application` app 
JOIN `supplier_accreditation`.`application_committe_member`  acm ON app.id = acm.`application_id`
JOIN `icta_committee_member` icm ON icm.id = acm.`committee_member_id`
JOIN `supplier_accreditation`.`user` usr ON usr.id = icm.`user_id`
GROUP BY usr.id, status_search";
        
        $query = Application::findBySql($sql);

        // add conditions that should always apply here

        //$query->joinWith(['accreditationType', 'company']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        return $dataProvider;
    }
}
