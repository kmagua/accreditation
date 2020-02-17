<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use kartik\icons\Icon;
use raoul2000\workflow\events\WorkflowEvent;

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
 * @property string $initial_approval_date
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
            [['company_id', 'accreditation_type_id', 'financial_status_amount'], 'required'],
            [['company_id', 'accreditation_type_id', 'user_id'], 'integer'],
            [['app_company_experience','app_staff'], 'required','on'=>'create_update'],
            [['financial_status_amount'], 'number'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['status'], 'string', 'max' => 50],
            [['date_created', 'last_updated','app_company_experience','app_staff', 'initial_approval_date'], 'safe'],
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
        if($this->scenario == 'create_update'){
            $this->refresh();
            $this->processSelectedStaff();
            $this->processSelectedCompanyExperience();
        }
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
    
    public function init()
    {
    	$this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/application-payment-confirmed'),
            [$this, 'sendPaymentApprovalEmail'],'application-payment-confirmed'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/application-payment-rejected'),
            [$this, 'sendPaymentApprovalEmail'],'application-payment-rejected'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/approval-payment-confirmed'),
            [$this, 'sendPaymentApprovalEmail'],'approval-payment-confirmed'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/approval-payment-rejected'),
            [$this, 'sendPaymentApprovalEmail'],'approval-payment-rejected'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/at-secretariat'),
            [$this, 'loadApplicationScores'], 1
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/at-committee'),
            [$this, 'loadApplicationScores'], 2
    	);
    }
    
    public function getApplicationProgress()
    {
        switch($this->status){
            case 'ApplicationWorkflow/draft':
                return $this->processDraft();
            case 'ApplicationWorkflow/application-paid':
                return $this->processApplicationFeePaid();
            case 'ApplicationWorkflow/application-payment-confirmed':
                return $this->assignCommitee(1);
            case 'ApplicationWorkflow/application-payment-rejected':
                return $this->processApplicationFeeRejected();
            case 'ApplicationWorkflow/at-secretariat':
                return $this->processAtSecretariat();
            case 'ApplicationWorkflow/assign-approval-committee':
                return $this->assignCommitee(2, "Assign Approval Committee Members");
            case 'ApplicationWorkflow/at-committee':
                return $this->processAtCommittee();
            case 'ApplicationWorkflow/approved':
                return $this->processApproved();
            case 'ApplicationWorkflow/certificate-paid':
                return $this->processApprovalPayment();           
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
                ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
    }
    
    public function processApplicationFeePaid()
    {
        return Html::a("Update Payment Status", ['application/approve-payment', 'id' => $this->id, 'l'=> 1], 
            ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Approve/Reject Payment Receipt</h3>'); return false;"]);
    }
    
    public function assignCommitee($level, $message = 'Assign secretariat members.')
    {
        return Html::a(Icon::show('users', ['class' => 'fa', 'framework' => Icon::FA]), [
            'application/committee-members', 'id' => $this->id, 'l'=> $level], 
                ['data-pjax'=>'0', 'title' => $message]);
    }
    
    /**
     * 
     */
    public function processApplicationFeeRejected()
    {
        return "TBA";
    }
    
    public function processAtSecretariat()
    {
        return Html::a(Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
            'application/approval', 'id' => $this->id, 'level'=> 1], 
                ['data-pjax'=>'0', 'title' =>'Review by ICTA Acceditation Secretariat']);
    }
        
    public function processAtCommittee()
    {
        return Html::a(Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
            'application/approval', 'id' => $this->id, 'level'=> 2], 
                ['data-pjax'=>'0', 'title' =>'Review by ICTA Approving Committee']);
    }
    
    public function processApproved()
    {
        return Html::a('MPESA', ['#'], ['oclick' =>'alert("Not Implemented")']) . ' &nbsp;&nbsp; '. Html::a(Icon::show('receipt', ['class' => 'fas',
            'framework' => Icon::FAS]), ['application/upload-receipt', 'id' => $this->id, 'l'=> 2], 
                ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Upload Certificate Payment Receipt</h3>'); return false;"]);
    }
    
    public function processApprovalPayment()
    {
        return Html::a("Update Payment Status", ['application/approve-payment', 'id' => $this->id, 'l'=> 2], 
            ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Approve/Reject Payment Receipt</h3>'); return false;"]);
    }
    
    public function processApprovalFeeRejected()
    {
        return "TBA";
    }
    
    public function processCompleted()
    {
        return Html::a('Download Certificate',[
            'application/download-cert', 'id' => $this->id], 
                ['data-pjax'=>'0', 'title' =>'Certificate Download']);
    }
    
    public function processRenewal()
    {
        return Html::a("Renew certificate", ['application/cert-renewal', 'id' => $this->id], 
            ['data-pjax'=>'0']);
    }
    
    /**
     * 
     * @param type $level Internal Approval Level : 1 = Secretariat, 2 = Committee
     */
    public function getPayableAtLevel($level)
    {
        if($level == 1){
            return 1500;
        }
        return 5000;
    }
    
    /**
     * 
     * @param type $status The new Workflow State
     */
    public function progressWorkFlowStatus($status)
    {
        $this->sendToStatus($status);
        $this->save(false);
    }
    
    public function sendPaymentApprovalEmail($outcome)
    {
        $outcome_array = explode("-", $outcome);
        $header = ucwords($outcome_array[0]. " Payment ". $outcome_array[0]);
        $additional = ".";
        if($outcome_array[2] == 'rejected'){
            $level = ($outcome_array[0] == 'application')? 1 : 2;
            $payment = Payment::find()->select('comment')->where(['application_id' => $this->id, 'level' => $level])->one();
            $additional = ' for the flowwing reason "' . $payment->comment .".";
        }
        $message = <<<MSG
                Dear {$this->user->getNames()},
                <p>Kindly note that your {$outcome_array[0]} payment to ICT Authority for accreditation has been {$outcome_array[2]}{$additional}</p>
                
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($this->company->company_email, $header, $message, $this->user->email);
    }
    
    /**
     * Load Application Score data
     * @param type $level (Workflow event object) Internal approval level ID 1 = Secretariat, 2 = Committee in $level->data
     */
    public function loadApplicationScores($level)
    {
        $score_items_sql ="SELECT id FROM `score_item`";
        $uid = \Yii::$app->user->identity->user_id;
        $score_items_data = \Yii::$app->db->createCommand($score_items_sql)->queryAll();
        foreach($score_items_data as $score_item_data){
            $insert_sql = "INSERT INTO application_score (application_id, score_item_id, committee_id, user_id)
                VALUES ({$this->id}, {$score_item_data['id']}, {$level->data}, $uid)
                ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP";
                
            \Yii::$app->db->createCommand($insert_sql)->execute();
        }
    }
    
    public static function preogressOnCommitteeApproval($id, $status, $level)
    {
        if($level == 1 && $status == 1){
            \Yii::$app->db->createCommand()
                ->update('application', ['status' => "ApplicationWorkflow/at-committee"], ['id'=>$id])->execute();
        }else if($level == 2 && $status == 1){
            \Yii::$app->db->createCommand()
                ->update('application', ['status' => "ApplicationWorkflow/approved"], ['id'=>$id])->execute();
        }else{
            \Yii::$app->db->createCommand()
                ->update('application', ['status' => "ApplicationWorkflow/draft"], ['id'=>$id])->execute();
        }
    }
}
