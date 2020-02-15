<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use kartik\icons\Icon;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $company_id
 * @property int|null $accreditation_type_id
 * @property float|null $financial_status_amount
 * @property string|null $financial_status_link
 * @property int|null $user_id
 * @property string|null $status
 * @property int|null $declaration
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyProfile $company
 * @property User $user
 * @property AccreditationType $accreditationType
 * @property ApplicationCommitteMember[] $applicationCommitteMembers
 * @property ApplicationCompanyExperience[] $applicationCompanyExperiences
 * @property ApplicationScore[] $applicationScores
 * @property ApplicationStaff[] $applicationStaff
 * @property Payment[] $payments
 */
class Application extends \yii\db\ActiveRecord
{
    public $app_company_experience; // to capture selected company experience for this application
    public $app_staff; // to capture selected company staff for this application
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'accreditation_type_id', 'financial_status_amount','app_company_experience','app_staff'], 'required'],
            [['company_id', 'accreditation_type_id', 'user_id'], 'integer'],
            [['financial_status_amount'], 'number'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['status'], 'string'],
            [['date_created', 'last_updated','app_company_experience','app_staff'], 'safe'],
            [['financial_status_link'], 'string', 'max' => 250],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyProfile::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['accreditation_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => AccreditationType::className(), 'targetAttribute' => ['accreditation_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'accreditation_type_id' => 'Accediation Category',
            'financial_status_amount' => 'Financial Status Amount (KES)',
            'financial_status_link' => 'Financial Status Document Link',
            'user_id' => 'User ID',
            'status' => 'Status',
            'declaration' => 'I declare that the information given here is correct to the best of my knowledge.',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(CompanyProfile::className(), ['id' => 'company_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /** 
    * Gets query for [[AccreditationType]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getAccreditationType() 
    { 
        return $this->hasOne(AccreditationType::className(), ['id' => 'accreditation_type_id']); 
    }
    /**
     * Gets query for [[ApplicationCommitteMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCommitteMembers()
    {
        return $this->hasMany(ApplicationCommitteMember::className(), ['application_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicationCompanyExperiences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCompanyExperiences()
    {
        return $this->hasMany(ApplicationCompanyExperience::className(), ['application_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicationScores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationScores()
    {
        return $this->hasMany(ApplicationScore::className(), ['application_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicationStaff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationStaff()
    {
        return $this->hasMany(ApplicationStaff::className(), ['application_id' => 'id']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasMany(Payment::className(), ['application_id' => 'id']);
    }
    
    /**
     * Overridden function
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) 
    {
        parent::beforeSave($insert);
        if($insert){
            $this->user_id = Yii::$app->user->identity->user_id;
        }        
        return true;
    }
    
    /**
     * Overridden function
     * @param type $insert
     * @param type $changedAttributes
     * @return boolean
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->refresh();
        $this->processSelectedStaff();
        $this->processSelectedCompanyExperience();
        return true;
    }
    
    /**
     * 
     * @return type
     */
    public function behaviors()
    {
    	return[
            \raoul2000\workflow\base\SimpleWorkflowBehavior::className()
        ];
    }
    
    /**
     * Adds record to the ApplicationConpanyExperience table for all the selected AppicationStaff
     */
    public function processSelectedStaff()
    {
        foreach ($this->app_staff as $staff_id){
            $rec = ApplicationStaff::find()->where(['application_id' => $this->id, 'staff_id' => $staff_id])->one();
            if(!$rec){
                \Yii::$app->db->createCommand()->insert('accreditation.application_staff',[
                    'staff_id'=> $staff_id, 'application_id'=> $this->id,
                    'role' => CompanyStaff::findOne($staff_id)->staff_type
                ])->execute();
            }
        }
        $staff = implode(",", $this->app_staff); 
        ApplicationStaff::deleteAll("staff_id not in ($staff) AND application_id={$this->id}");
    }
    
    /**
     * Adds record to the ApplicationConpanyExperience table for all the selected CompamyExperiences
     */
    public function processSelectedCompanyExperience()
    {
        foreach ($this->app_company_experience as $company_exp_id){
            $rec = ApplicationCompanyExperience::find()->where(['application_id'=>$this->id, 'experience_id' =>$company_exp_id])->one();
            if(!$rec){                
                \Yii::$app->db->createCommand()->insert('accreditation.application_company_experience',[
                    'experience_id'=>  $company_exp_id, 'application_id'=>  $this->id
                ])->execute();
            }
        }
        $we = implode(",", $this->app_company_experience); 
        ApplicationCompanyExperience::deleteAll("experience_id not in ($we) AND application_id={$this->id}");        
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
     * 
     */
    public function loadExperienceData()
    {
        $rec = ApplicationCompanyExperience::find()->select('experience_id')->where(['application_id'=>$this->id])->all();
        if($rec){
            $this->app_company_experience = array_column($rec, 'experience_id');
        }
    }
    
    /**
     * Sets app_staff to values of selected staff members
     * 
     */
    public function loadStaffData()
    {
        $rec = ApplicationStaff::find()->select('staff_id')->where(['application_id'=>$this->id])->all();
        if($rec){
            $this->app_staff = array_column($rec, 'staff_id');
        }
    }
    
    /**
     * delete data in related child tables
     * @return boolean
     */
    public function beforeDelete() 
    {
        parent::beforeDelete();
        
        ApplicationCompanyExperience::deleteAll("application_id={$this->id}");
        ApplicationStaff::deleteAll("application_id={$this->id}");
        
        return true;
    }
    
    public function getApplicationProgress()
    {
        switch($this->status){
            case 'ApplicationWorkflow/draft':
                return $this->processDraft();
            case 'ApplicationWorkflow/application-paid':
                return $this->processApplicationFeePaid();
            case 'ApplicationWorkflow/application-payment-confirmed':
                return $this->processApplicationFeeConfimed();
            case 'ApplicationWorkflow/application-payment-rejected':
                return $this->processApplicationFeeRejected();
            case 'ApplicationWorkflow/at-secretariat':
                return $this->processAtSecretariat();
            case 'ApplicationWorkflow/at-committee':
                return $this->processAtCommittee();
            case 'ApplicationWorkflow/approved':
                return $this->processApproved();
                case 'ApplicationWorkflow/approval-payment':
                return $this->processApprovalPayment();
            case 'ApplicationWorkflow/approval-payment-confirmed':
                return $this->processApprovalFeeConfimed();
            case 'ApplicationWorkflow/approval-payment-rejected':
                return $this->processApprovalFeeRejected();
            case 'ApplicationWorkflow/completed':
                return $this->processCompleted();
            case 'ApplicationWorkflow/renewal':
                return $this->processRenewal();
        }
    }
    
    /**
     * 
     * @return type
     */
    public function processDraft()
    {
        return Html::a('MPESA', ['#'], ['oclick' =>'alert("Not Implemented")']) . ' &nbsp;&nbsp; '. Html::a(Icon::show('receipt', ['class' => 'fas',
            'framework' => Icon::FAS]), ['application/upload-receipt', 'id' => $this->id, 'l'=> 1], 
                ['data-pjax'=>'0', 'onclick' => "getStaffForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
    }
    
    public function processApplicationFeePaid()
    {
        
    }
    
    public function processApplicationFeeConfimed()
    {
        
    }
    
    public function processApplicationFeeRejected()
    {
        
    }
    
    public function processAtSecretariat()
    {
        
    }
        
    public function processAtCommittee()
    {
        
    }
    
    public function processApproved()
    {
        
    }
    
    public function processApprovalPayment()
    {
        
    }
    
    public function processApprovalFeeConfimed()
    {
        
    }
    
    public function processApprovalFeeRejected()
    {
        
    }
    
    public function processCompleted()
    {
        
    }    
    
    public function processRenewal()
    {
        
    }    
}
