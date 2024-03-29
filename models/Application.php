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
 * @property string|null $cash_flow
 * @property string|null $turnover
 * @property string|null $financial_status_link
 * @property int|null $user_id
 * @property string|null $status
 * @property string|null $certificate_serial
 * @property int|null $declaration
 * @property string $initial_approval_date
 * @property int $application_type
 * @property string $previous_category
 * @property int|null $parent_id
 * @property string|null $mpesa_account
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
    public $revert_rejection; //used to capture rejection reversion declaration
    public $status_search;
    public $ceremonial_approval;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }
    
    /**
     * 
     */
    public function init()
    {
    	$this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/draft'),
            [$this, 'checkCompanyExists'],'draft'
    	);
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
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/completed'),
            [$this, 'sendApprovalEmail'], 'completed'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/approved'),
            [$this, 'processAfterCommitteeApproval'], 'approved'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/sec-rejected'),
            [$this, 'sendRejectedEmail'], 'rejected'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/com-rejected'),
            [$this, 'sendRejectedEmailCommittee'], 'rejected'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/chair-approval'),
            [$this, 'notifyCeremonialApprovers'], 1
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/director-approval'),
            [$this, 'notifyCeremonialApprovers'], 2
    	);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'accreditation_type_id', 'application_type'], 'required'],
            [['company_id', 'accreditation_type_id', 'user_id', 'application_type', 'parent_id'], 'integer'],
            [['app_staff', 'financial_status_link'], 'required','on'=>'create_update'],
            ['ceremonial_approval', 'required', 'on'=>'chair_approval', 'requiredValue' => 1, 'message' => 'You must check that you agree with the scores'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['revert_rejection', 'required', 'on' => ['revert_rejection'], 'requiredValue' => 1, 
                'message' => 'You must confirm to have addressed issues raised in the rejection comment.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['status', 'certificate_serial'], 'string', 'max' => 50],
            [['previous_category', 'cash_flow', 'turnover'], 'string', 'max' => 20],
            [['mpesa_account'], 'string', 'max' => 10],
            //[['accreditation_type_id'], 'unique', 'targetAttribute' => ['accreditation_type_id', 'company_id'], 'message' => 'You have already submitted an application for the selected Accreditation Category'],
            [['date_created', 'last_updated', 'app_company_experience', 'app_staff', 'initial_approval_date'], 'safe'],
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
            'accreditation_type_id' => 'Accreditation Category',
            'cash_flow' => 'Cash Flow (KES)',
            'turnover' => 'Turnover (KES)',
            'financial_status_link' => 'Bank statements and audited accounts for the past three (3) years',
            'user_id' => 'User ID',
            'status' => 'Status',
            'declaration' => 'I agree to abide by the Code of Conduct (see home page)',
            'revert_rejection' => 'I confirm that issues raised in the rejection comment have been addressed.',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
            'app_company_experience' => 'Select relevant work experience for this application',
            'app_staff' => 'Select staff for this application',
            'previous_category' => 'Previously Assigned Category',
            'application_type' => 'New or Annual Renewal',
            'status_search' => 'Status',
            'ceremonial_approval' => 'I agree with the ratings for this application.',
            'initial_approval_date' => 'Approval/Renewal Date'
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
     * 
     * @return boolean
     */
    public function beforeValidate() 
    {
        parent::beforeValidate();
        if($this->application_type == 2 && $this->previous_category =='' && $this->parent_id == ''){
            $this->addError('previous_category', 'Previous category cannot be blank for renewals.');
        }
        if($this->isNewRecord){
            $other = Application::find()->where(['company_id' => $this->company_id,
                'accreditation_type_id' => $this->accreditation_type_id, 'application_type'=>1
            ])->one();
            if($other){
                if($this->application_type == 1){
                    $this->addError('accreditation_type_id', 'You have already submitted an application for the selected Accreditation Category.');
                }else{
                    if($this->parent_id == ''){
                        $this->addError('application_type', 'An existing record already exists for the category. Kindly renew from that application.');
                    }else{
                        $this->validateOtherCHildIsNotPending();
                    }
                }
            }
            $this->checkDuplicates();
        }        
        return true;
    }
    
    public function validateOtherCHildIsNotPending()
    {
        $latest_renewal = Application::find()->where(['company_id' => $this->company_id,
                'accreditation_type_id' => $this->accreditation_type_id, 'parent_id'=> $this->parent_id
            ])->orderBy('id desc')->one();
        if($latest_renewal){
            if(!in_array($latest_renewal->status, ['ApplicationWorkflow/renewal', 'ApplicationWorkflow/completed'])){
                $this->addError('accreditation_type_id', 'Another Renewal record that has not been approved to the last stage exists and hence cannot add a new one.');
            }
        }        
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
            $this->turnover = $this->company->turnover;
            $this->cash_flow = $this->company->cashflow;
            
            $string = 'SUP-' . Utility::generateMpesaAccountString();
            while(!$this->isUnique($string)){
                $string =  'SUP-'. Utility::generateMpesaAccountString();
            }
            $this->mpesa_account = $string;
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
                \Yii::$app->db->createCommand()->insert('application_staff',[
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
                \Yii::$app->db->createCommand()->insert('application_company_experience',[
                    'experience_id'=>  $company_exp_id, 'application_id'=>  $this->id
                ])->execute();
            }
        }
        $we = implode(",", $this->app_company_experience); 
        ApplicationCompanyExperience::deleteAll("experience_id not in ($we) AND application_id={$this->id}");        
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
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
    
    /**
     * check for duplicate applications using accreditation type and company ID
     */
    public function checkDuplicates()
    {
        if($this->parent_id == ''){
            $application = Application::find()->where(['company_id' => $this->company_id, 
                'accreditation_type_id' => $this->accreditation_type_id])->one();
            if($application){
                $this->addError('accreditation_type_id', 
                    "Your company already has an application for {$this->accreditationType->name}");
            }
        }
    }
    
    /**
     * 
     * @return type
     */
    public function getApplicationProgress()
    {
        switch($this->status){
            case 'ApplicationWorkflow/draft':
                return $this->assignCommitee(1);            
            case 'ApplicationWorkflow/at-secretariat':
                return $this->processInternalCommittee(1);
            case 'ApplicationWorkflow/assign-approval-committee':
                return $this->assignCommitee(2, "Assign Approval Committee Members");
            case 'ApplicationWorkflow/at-committee':
                return $this->processInternalCommittee(2);
            case 'ApplicationWorkflow/approved':
                return $this->paymentConfirmation(2);
            case 'ApplicationWorkflow/sec-rejected':
                return $this->processRejected(1);
            case 'ApplicationWorkflow/com-rejected':
                return $this->processCommRejected();
            case 'ApplicationWorkflow/certificate-paid':
                return $this->processConfirmPayment(2);           
            case 'ApplicationWorkflow/approval-payment-rejected':
                return $this->processApprovalFeeRejected();
            case 'ApplicationWorkflow/completed':
                return $this->processCompleted();
            case 'ApplicationWorkflow/renewal':
                return $this->processRenewal();
            case 'ApplicationWorkflow/renewed':
                return 'Certificate Renewed';
            case 'ApplicationWorkflow/chair-approval':
                return $this->processCeremonialAPproval(1);
            case 'ApplicationWorkflow/director-approval':
                return $this->processCeremonialAPproval(2);
            case 'ApplicationWorkflow/pdtp-reviewed':
                return $this->processInternalCommittee(1);
        }
    }
    
    /**
     * 
     * @return type
     */
    public function paymentConfirmation($level)
    {
        if($this->checkUserCanAccess()){            
            //return Html::a('MPESA', ['#'], ['oclick' =>'alert("Not Implemented"); return false;']) . ' &nbsp;&nbsp; '. 
            return Html::a('Upload Payment Receipt ' . Icon::show('receipt', ['class' => 'fas',
                'framework' => Icon::FAS]), ['application/upload-receipt', 'id' => $this->id, 'l'=> $level], 
                    ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"])
                . \yii\helpers\Html::a('&nbsp;&nbsp;&nbsp;Pay with MPESA', [
                    '/application/lipa-na-mpesa', 'id'=>$this->id
                ], 
                ['onclick' => "getDataForm(this.href, '<h3>Pay With MPESA</h3>'); return false;", 'class'=>'text-danger']
            ); 
        }
        return "Pending Payment";
    }
    
    /**
     * Internal payment confirmation
     * @param type $level
     * @return string
     */
    public function processConfirmPayment($level)
    {
        if(\Yii::$app->user->identity->isInternal()){        
            return Html::a("Update Payment Status", ['application/approve-payment', 'id' => $this->id, 'l'=> $level], 
                ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Approve/Reject Payment Receipt</h3>'); return false;"]);
        }
        return "Pending Payment Confirmation";
    }
    
    /**
     * Assign committee members to an application
     * @param type $level
     * @param type $message
     * @return string
     */
    public function assignCommitee($level, $message = 'Assign secretariat members.')
    {
        if(\Yii::$app->user->identity->isInternal()){
            $grp = ($level==1)?"Secretariat":"Committee member";
            $param = ($level==1)?"secAssigner":"committeeAssigner";
            if(in_array(Yii::$app->user->identity->username, \Yii::$app->params[$param]) ){
                return Html::a("Assign Members ". Icon::show('users', ['class' => 'fa', 'framework' => Icon::FA]), [
                    'application/committee-members', 'id' => $this->id, 'l'=> $level], 
                        ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Application {$grp} Members</h3>'); return false;",
                            'title' => $message]);
            }
            return "Pending assignment at " . (($level==1)?"secretariat.":"committee.");
        }
        return "Pending";
    }
    
    /**
     * 
     */
    public function processApplicationFeeRejected($level = 2)
    {
        return 'TBA';
    }
    
    /**
     * Internal committees scoring
     * @param type $level
     * @return string
     */
    public function processInternalCommittee($level, $from_rejection = false)
    {
        $group = ($level == 1)?'Secretariat':'Committee';
        if(Application::canApprove($level, $this->id)){
            //$other_params = ? ['level'=> $level, 'rej'=>1] : ['level'=> $level];
            $title = ($level == 1) ? 'Score by ICTA Acceditation Secretariat' : 'Score by ICTA Approving Committee';
            $pdtp_scored = ($this->status == 'ApplicationWorkflow/pdtp-reviewed')?'<span style="color:red">(PDTP reviewed) </span>':'';
            return Html::a("Score $pdtp_scored" .Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
                'application/approval', 'id' => $this->id, 'level'=> $level, 'rej'=> ($from_rejection)? 1:null], 
                    ['data-pjax'=>'0', 'title' => $title]);
        }else{
            if($level == 1 && $this->status == 'ApplicationWorkflow/at-secretariat' && Yii::$app->user->identity->inGroup('pdtp')){
                return Html::a("PDTP Score " .Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
                    'application/approval', 'id' => $this->id, 'level'=> $level, 'rej'=> ($from_rejection)? 1:null], 
                        ['data-pjax'=>'0', 'title' => 'PDTP Scoring']);
            }
            if(\Yii::$app->user->identity->isInternal()){
                return "at $group";
            }
            return "Pending";
        }
    }
    
    /**
     * 
     * @return string
     */
    public function processApprovalFeeRejected()
    {
        if($this->checkUserCanAccess()){
            //return Html::a('MPESA', ['#'], ['oclick' =>'alert("Not Implemented"); return false;']) . ' &nbsp;&nbsp; '. 
            return Html::a('Upload Payment Receipt ' . Icon::show('receipt', ['class' => 'fas',
                'framework' => Icon::FAS]), ['application/upload-receipt', 'id' => $this->id, 'l'=> 2], 
                    ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
        }
        return "Receipt invalidated";
    }
    
    /**
     * link to download certificate
     * @return type
     */
    public function processCompleted()
    {        
        $latest = Application::find()->where(['parent_id' => $this->id])->orderBy('id desc')->one();
        if($latest){
            return Html::a('Download Certificate',[
                'application/download-cert', 'id' => $latest->id], 
                    ['data-pjax'=>'0', 'title' =>'Certificate Download']);
        }
        return Html::a('Download Certificate',[
            'application/download-cert', 'id' => $this->id], 
                ['data-pjax'=>'0', 'title' =>'Certificate Download']);
    }
    
    /**
     * 
     * @return type
     */
    public function processRenewal()
    {
        if($this->checkUserCanAccess()){ 
            return Html::a("Renew certificate", ['application/renew-cert', 'id' => $this->id,
                'cid' =>$this->company_id, 't' =>$this->accreditation_type_id], ['data-pjax'=>'0']
            );
        }
        return 'Pending Renewal';
    }
    
    /**
     * 
     * @param type $level Internal Approval Level : 1 = Secretariat, 2 = Committee
     */
    public function getPayableAtLevel($level='')
    {
        /*if($level == 1){
            return 1500;
        }*/
        $ac = ApplicationClassification::find()->where(['application_id'=>$this->id, 'icta_committee_id' => 2])->one();
        if($ac){
            $field = ($this->application_type == 1)?'accreditation_fee':'renewal_fee';
            $level_details = AccreditationLevel::findOne(['name' => $ac->classification]);
            if($level_details){
                return $level_details->{$field};
            }
        }
        return "Invalid payment amount";
    }
    
    /**
     * 
     * @param type $status The new Workflow State
     */
    public function progressWorkFlowStatus($status)
    {
        $this->sendToStatus($status);
        return $this->save(false);
    }
    
    /**
     * 
     * @param type $level 1=>Sec, 2 => Committee
     * @return type
     */
    public function processRejected($level)
    {
        if($this->checkUserCanAccess()){ 
            return Html::a("Revert After Rejection", 
                ['application/revert-rejection', 'id' => $this->id, 'l'=> $level], 
                [
                    'data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Revert After Rejection</h3>'); return false;",
                    'title' => 'Confirm that all issues during Rejection of application are resolved.'
                ]
            );
        }
        return 'Pending applicant re-review';
    }
    
    /**
     * 
     * @return type
     */
    public function processCommRejected()
    {
        return $this->processInternalCommittee(1, $from_rejection = true);
    }
    
    /**
     * Send email to applicant after payment has been approved at ICTA
     * @param type $outcome Workflow event object
     */
    public function sendPaymentApprovalEmail($outcome)
    {
        $outcome_array = explode("-", $outcome->data);
        $header = ucwords($outcome_array[0]. " Payment ". $outcome_array[0]);
        $additional = ".";
        if($outcome_array[2] == 'rejected'){
            $level = ($outcome_array[0] == 'application')? 1 : 2;
            $payment = Payment::find()->select('comment')->where(['application_id' => $this->id, 'level' => $level])->one();
            $additional = ' for the following reason "' . $payment->comment .".";
        }
        $message = <<<MSG
                Dear {$this->user->full_name},
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
        $score_items_sql ="SELECT id FROM `score_item` order by order_col asc";
        $uid = \Yii::$app->user->identity->user_id;
        $score_items_data = \Yii::$app->db->createCommand($score_items_sql)->queryAll();
        ApplicationClassification::setClassificationItemsToNull($level->data, $this->id);
        foreach($score_items_data as $score_item_data){
            $score = $comment = null;
            if($level->data == 2){
                $level_one_sql = 'SELECT ifnull(score,0) score, comment FROM application_score where '
                        . " application_id = {$this->id} and committee_id = 1 and score_item_id = {$score_item_data['id']}";
                $level_one_score = \Yii::$app->db->createCommand($level_one_sql)->queryOne();
                if($level_one_score){                    
                    $score = ($level_one_score['score'] > 0)? $level_one_score['score']:null;
                    $comment = $level_one_score['comment'];
                }
            }
            $insert_sql = "INSERT INTO application_score (application_id, score_item_id, committee_id, user_id, score, comment)
                VALUES ({$this->id}, {$score_item_data['id']}, {$level->data}, $uid, :score, :comment)
                ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP, score = :score, comment=:comment";
                
            \Yii::$app->db->createCommand($insert_sql, [':score' => $score, ':comment' => $comment])->execute();
        }
        //throw new \Exception(print_r($level->getStartStatus()->getId(), true));
        $this->sendReviewersEmail($level);
    }
    
    /**
     * 
     * @param type $level
     */
    public function sendReviewersEmail($level)
    {
        $prev_status = $level->getStartStatus()->getId();
        $sql = "SELECT `email` FROM `application_committe_member` acm JOIN `icta_committee_member` icm ON icm.id=acm.`committee_member_id`
            JOIN `user` u ON u.id=icm.user_id WHERE `committee_id` = {$level->data} AND `application_id` = {$this->id}";
        $assigned_members = User::findBySql($sql)->all();
        if(!$assigned_members){
            return;
        }
        $emails = \yii\helpers\ArrayHelper::getColumn($assigned_members, 'email');
        $header = "Accreditation review/score invitation";
        $mail_msg = ($prev_status == 'ApplicationWorkflow/com-rejected')?'reviewed by secretariat':'updated by the applicant';
        $link = \yii\helpers\Url::to(['/application/view', 'id' => $this->id], true);
        $score_link = \yii\helpers\Url::to(['/application/approval', 'id' => $this->id, 'level' => $level->data], true);
        $main_paragraph = (!in_array($prev_status, ['ApplicationWorkflow/sec-rejected', 'ApplicationWorkflow/com-rejected']))?"Kindly note that you have been selected to review/score an application for accreditation.":
            "Kindly note that an application that you(group) had rejected has been {$mail_msg}. Kindly log on to the system to review again.";
        $message = <<<MSG
        Dear All,
        <p>$main_paragraph</p>
        <p>Use this ($link) link to view the application details.</p>
        <p>Use this ($score_link) link to score the application.</p>
        <p>Thank you,<br>ICT Authority Accreditation.</p>                
MSG;
        Utility::sendMail($emails, $header, $message);
    }
    
    /**
     * update status after committee scoring
     * @param type $id
     * @param type $status
     * @param type $level
     * @param type $rej (should come set to 1 if this application had been rejected by committee [level 2])
     */
    public static function progressOnCommitteeApproval($id, $status, $level, $rej)
    {
        $app = Application::findOne($id);
        if($level == 1 && $status == 1){
            if($rej == 1){
                $app->progressWorkFlowStatus('at-committee');
            }else{
                if(Yii::$app->user->identity->inGroup('pdtp', false)){
                    $app->progressWorkFlowStatus('pdtp-reviewed');
                }else{
                    $app->progressWorkFlowStatus('assign-approval-committee');
                }
            }
        }else if($level == 2 && $status == 1){
            $app->progressWorkFlowStatus('approved');            
        }else{
            $new_status = ($level == 1)? 'sec-rejected' : 'com-rejected';
            $app->progressWorkFlowStatus($new_status);
        }
        return $app->parent_id;
    }
    
    /**
     * Send email to applicant after application has been approved by ICTA
     * @param type $event
     */
    public function sendApprovalEmail($event)
    {
        $header = "Your Company's accreditation request has been approved by ICT Authority";
        $type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['/application/download-cert', 'id' => $this->id], true);
        
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that your Accreditation request for $type has been approved by ICT Authority.
                    You can login in to the Authority's accreditation site using the link below to download your certificate.</p>
                <p>$link</p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($this->company->company_email, $header, $message, $this->user->email);
    }
    
    /**
     * Send email to applicant after application has been approved by ICTA
     * @param type $event
     */
    public function sendRejectedEmail($event)
    {
        $header = "Your Company's accreditation request has been Rejected by ICT Authority";
        $type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['/application/view', 'id' => $this->id], true);
        $app_categorization = ApplicationClassification::find()->where(['application_id' => $this->id])->
            orderBy('application_id desc')->one();
        $comment = ($app_categorization)?$app_categorization->rejection_comment:"";
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that your Accreditation request for $type has been REJECTED by ICT Authority because of the following reason.</p>
                <p>$comment</p>
                <p>You can revert the application after updating your application according to the above comment(s).</p><br>$link
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($this->company->company_email, $header, $message, $this->user->email);
        
    }
    
    /**
     * Send email to applicant after application has been approved by ICTA
     * @param type $event
     */
    public function sendRejectedEmailCommittee($event)
    {
        $header = "An application you approved has been reverted back to you at Committee level";
        //$type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['/application/view', 'id' => $this->id], true);
        $app_categorization = ApplicationClassification::find()->where(['application_id' => $this->id])->
            orderBy('application_id desc')->one();
        $sec_approver = $this->getApprovers(1);
        if(!$sec_approver){
            return;
        }
        $send_to = array_column($sec_approver, 'email');
        $comment = ($app_categorization)?$app_categorization->rejection_comment:"";
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that an application you had reviewed and forwarded to the committee for consideration has been reverted back to you with the coomment below.</p>
                <p>$comment</p>
                <p>You can review your score or reject the application (with comment) to be returned back to the applicant company for action.</p><br>$link
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($send_to, $header, $message, $this->user->email);
    }
    
    /**
     * 
     * @param type $event
     */
    public function processAfterCommitteeApproval($event)
    {
        $this->updateApplicationPaymentOnERP();
        $this->sendPaymentRequestEmail($event);
    }
    
    /**
     * 
     * @param type $event
     */
    public function sendPaymentRequestEmail($event)
    {
        $header = "ICT Authority - Payment Request for Company Accreditation";
        $type = $this->accreditationType->name;
        
        $link = \yii\helpers\Url::to(['/company-profile/view', 'id' => $this->company_id], true);
        $ac = ApplicationClassification::find()->where(['application_id'=>$this->id, 'icta_committee_id' => 2])->one();
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that your Accreditation request for $type has been reviewed and graded [{$ac->classification}]  by ICT Authority.
                    You are now required to make payment of KES: {$this->getPayableAtLevel()} to: <br>CITIBANK,<br>Name: ICT Authority,<br>Account No: 0300085016,<br>Branch: Upper Hill (code: 16000).
                    <br>
                    Or MPESA: <br>
                    Paybill No: <strong>7864821</strong><br/>
                    Account Name: <strong>ICTA</strong>
                        </p>
                <p><strong>After payment, deliver the bank slip/MPesa Code to ICTA - Telposta Towers 12th floor, Finance Department to be issued a receipt which you will then upload on the system using this link {$link}. You will get a notification email to download your certificate once your uploaded receipt is confirmed.</strong></p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($this->company->company_email, $header, $message, $this->user->email);
    }
    
    /**
     * Check if logged in user can access an application details
     * @return boolean
     */
    public function checkUserCanAccess()
    {
        $user_grp = strtolower(\Yii::$app->user->identity->group);
        if(in_array($user_grp, ['admin', 'applicant'])){
            if($user_grp == 'admin'){
                return true;
            }
            return CompanyProfile::canAccess($this->company_id);
        }else{
            return false;
        }
    }
    /**
     * Check if the logged in user can approve a request. Have to be selected for the level.
     * @param type $level
     * @param type $id
     * @return boolean
     */
    public static function canApprove($level, $id)
    {
        if(\Yii::$app->user->identity->isAdmin()){
            return true;
        }
        //$lvl = ($level == 1)?"ApplicationWorkflow/at-secretariat', 'ApplicationWorkflow/com-rejected":'ApplicationWorkflow/at-committee';
        $and = " AND app.status = 'ApplicationWorkflow/at-committee'";
        if($level == 1){
            $and = " AND (app.status = 'ApplicationWorkflow/at-secretariat' OR app.status = 'ApplicationWorkflow/pdtp-reviewed' OR app.status = 'ApplicationWorkflow/com-rejected')";
        }
        $sql = "SELECT icm.user_id, app.status  FROM `icta_committee_member` icm 
            JOIN `application_committe_member` acm ON acm.`committee_member_id` = icm.`id`
            JOIN `application` app ON app.`id` = acm.`application_id`
            WHERE `committee_id` = $level AND icm.user_id = " . \Yii::$app->user->identity->id . "
                AND acm.application_id = $id $and";
        $rec = \Yii::$app->db->createCommand($sql)->queryOne();
        if($rec){
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param type $cid Company ID
     * @return type
     */
    public static function getBizPermit($cid)
    {
        $sql = "SELECT cd.* FROM `company_document` cd 
            JOIN `company_type_document` ctd ON cd.`company_type_doc_id`=ctd.id
            JOIN `document_type` dt ON dt.id = ctd.`document_type_id`
            WHERE cd.company_id = $cid"; // removed dt.name = 'business permit' AND
        $query = CompanyDocument::findBySql($sql);
        $provider = new \yii\data\ActiveDataProvider(['query' => $query]);
        return $provider;
    }
    
    /**
     * 
     * @param type $parent_id Application ID
     * @param type $cid CompanyProfile ID
     * @param type $t AccreditationType ID
     */
    public function initRenewal($parent_id,$cid, $t)
    {
        $classification = null;
        $ac = ApplicationClassification::find()->where(['application_id' => $parent_id, 'icta_committee_id' => 2])->orderBy('id desc')->one();
        if($ac){
            $classification = $ac->classification;
        }
        $this->setAttributes([
            'parent_id' => $parent_id,
            'company_id' => $cid,
            'accreditation_type_id' => $t,
            'status' => 'ApplicationWorkflow/draft',
            'application_type' => 2,
            'previous_category'=> $classification,
        ]);
    }
    
    /**
     * assign the initial approval date
     */
    public function getInitApprovalDate()
    {
        if($this->parent_id == ''){
            $this->initial_approval_date = date('Y-m-d');
        }else{
            $sql = "SELECT * FROM supplier_accreditation.application WHERE
                (id = {$this->parent_id} OR parent_id = {$this->parent_id})
                 AND ifnull(initial_approval_date, '1900-01-01') !='1900-01-01' order by id desc limit 1";
            //latest approved application
            $laa = Application::findBySql($sql)->one();
            if($laa){
                $expiry = strtotime($laa->initial_approval_date . ' + 1 year');
                if($expiry > strtotime('now')){
                    $this->initial_approval_date = date('Y-m-d', $expiry);
                }else{
                    $this->initial_approval_date = date('Y-m-d');
                }
            }else{
                //it should never have to get here but if it does, well!!
                $this->initial_approval_date = date('Y-m-d');
            }
        }
    }
    
    /**
     * get latest accreditation category for the application, based on child parent relationship of applications
     * @return type
     */
    public function getLatestCategory()
    {
        $parent_id = $this->parent_id;
        $sql = "SELECT `classification` FROM supplier_accreditation.application app 
            JOIN supplier_accreditation.`application_classification` ac ON ac.`application_id` = app.id  
            WHERE (app.parent_id = $parent_id OR app.id = $parent_id) AND `icta_committee_id` = 2
            ORDER BY app.id DESC LIMIT 1";
        $data = \Yii::$app->db->createCommand($sql)->queryScalar();
        return $data;
    }
    
    /**
     * 
     * @return type
     */
    public function getExpiryDate()
    {
        $app = Application::find()->where('parent_id = ' . $this->id)->orderBy('id DESC')->one();
        if(!$app || $app->initial_approval_date == ''){
            $app = $this;
        }
        return date('d-m-Y', strtotime($app->initial_approval_date . "+1 year"));
    }
    
    /**
     * Save renewal and update parent status
     * @return boolean
     */
    public function saveRenewal()
    {
        $this->application_type = 2;
        if($this->save()){
            $sql = "UPDATE `supplier_accreditation`.`application` set
                status = 'ApplicationWorkflow/renewed' where id = {$this->parent_id}";
            $app = \Yii::$app->db->createCommand($sql)->execute();
            return true;
        }
        return false;
    }
    
    /**
     * 
     */
    public function getClassification()
    {
        $or = ($this->parent_id != '')?" OR application_id = {$this->parent_id}":"";
        $app_class = ApplicationClassification::find()
            ->where("application_id = {$this->id} $or")->orderBy('id DESC')->one();
        if($app_class){
            return $app_class->classification;
        }
        return '';
    }
    
    /**
     * 
     * @param type $company_id
     */
    public static function checkCompletedSections($company_id)
    {
        $missing = [];
        $sql = "SELECT count(dt.id) id FROM `company_document` cd 
            JOIN `company_type_document` ctd ON ctd.id = cd.`company_type_doc_id` 
            JOIN `document_type` dt ON dt.id = ctd.`document_type_id` 
            WHERE dt.id IN(1,2,3) AND cd.`company_id` = $company_id";
        $company_docs = CompanyDocument::findBySql($sql)->one();
        if(!$company_docs || $company_docs->id != 3){
            $missing[] = 'You are missing either All or one of the following documents (Business permit, Certificate of Incorporation, KRA tax compliance).';
        }
        $company_exp = CompanyExperience::find()->where("company_id = $company_id")->one();
        if(!$company_exp){
            $missing[] = 'You have not added details of projects done by your company.';
        }
        $staff = CompanyStaff::find()->where("company_id = $company_id")->one();
        if(!$staff){
            $missing[] = 'You have not entered details about the company\'s staff.';
        }
        return $missing;
    }
    
    /**
     * 
     * @param type $level
     * @return type
     */
    public function getApprovers($level)
    {
        $sql = "SELECT usr.email, usr.id FROM `icta_committee_member` icm
            JOIN `application_committe_member` acm ON acm.`committee_member_id` = icm.`id`
            JOIN `application` app ON app.`id` = acm.`application_id`
            JOIN `user` usr ON usr.id = icm.user_id
            WHERE icm.`committee_id` = {$level} AND acm.application_id = {$this->id}";
            
        return \Yii::$app->db->createCommand($sql)->queryAll();
    }
    
    public function processCeremonialAPproval($level)
    {
        $text = ($level == 1)?'Chair Review':'Director Review';
        $email = Yii::$app->user->identity->username;
        if(in_array(\Yii::$app->user->identity->group, ['Chair', 'Director', 'Admin']) || in_array($email, Yii::$app->params['assignedGrandApprover'])){
            $text = 'Chair/Director Review';
            return Html::a($text, [
                'application/ceremonial-approval', 'id' => $this->id, 'l'=> $level], 
                    ['data-pjax'=>'0', 'title' => $text, 
                        'onclick' => "getDataForm(this.href, '<h3>{$text}</h3>'); return false;"]);
        }
        if(\Yii::$app->user->identity->isInternal()){
            return 'Pending ' . $text;
        }
        return 'Pending';
    }
    
    /**
     * 
     * @param type $level
     */
    public function notifyCeremonialApprovers($level)
    {
        $lvl = $level->data;
        $header = 'Chair/Director Review invitation for an Accreditation Application';
        //$role = ($lvl == 1)?'Chair':'Director';
        $company_name = $this->company->company_name;
        $users = User::find()->where("role IN('Chair', 'Director')")->all();
        if($users){
            $emails = array_column($users, 'email');
            $link = \yii\helpers\Url::to(['/application/my-assigned'], true);
            $ac = ApplicationClassification::find()->where(['application_id'=>$this->id, 'icta_committee_id' => 2])->one();
            $message = <<<MSG
                    Dear Chair/Director,
                    <p>You are invited to review an accreditation application for <strong>$company_name</strong> that has been ranked <strong>{$ac->classification}</strong> by committee. You can access it on the link below.</p>
                    <p>$link</p>
                    <p>Thank you,<br>ICT Authority Accreditation.</p>
MSG;
        Utility::sendMail($emails, $header, $message);
        }        
    }
    
    /**
     * 
     * @return boolean
     */
    public function validatePayment()
    {
        if(in_array($this->status, ['ApplicationWorkflow/approved', 'ApplicationWorkflow/certificate-paid'])){            
            return true;
        }
        return false;
    }
    
    public function getLevelReviewer($level)
    {
        $sql = "SELECT GROUP_CONCAT(u. `first_name`, ' ', `last_name`) first_name FROM `application_committe_member` acm JOIN `icta_committee_member` icm ON acm.`committee_member_id`= icm.id
            JOIN `user` u ON u.id = icm.`user_id`
            WHERE `application_id` = {$this->id} AND icm.`committee_id` = {$level} 
            GROUP BY application_id";
        $reviewers = User::findBySql($sql)->one();
        if($reviewers){
            return $reviewers->first_name;
        }
        return "";
    }
    
    public function checkCompanyExists()
    {
        $biz_reg = $this->company->business_reg_no;
        
        $cURLConnection = curl_init();
        $query = http_build_query(array('business_reg_no' => $biz_reg));
        $url = "https://ess.icta.go.ke/acredapitest/api/file/verify-customer?$query";
//echo print_r($query); exit;
        // construct curl resource
        //$curl = curl_init("https://api.copernica.com/v1/$resource?$query");
        curl_setopt_array($cURLConnection, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
          //CURLOPT_CUSTOMREQUEST => "PUT",
          //CURLOPT_POSTFIELDS => json_encode( array( 'business_reg_no'=> $biz_reg) ), // Data sent in json format.
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "x-api-key: 4a5fcaa28975b99da6b8221f8fdf7b72A"
          ),
        ));        

        $response = curl_exec($cURLConnection); 
        $err = curl_error($cURLConnection);
        curl_close($cURLConnection);
        if ($err) {
          //$msg = "cURL Error #:" . $err; exit;
        }
       // echo $url . " ------- " .$response; exit;
        $obj = json_decode($response);
        //print_r($obj); exit;
        if(isset($obj->Title) && $obj->Title == 'error'){
            $this->registerBizRegNumberInERP($biz_reg);
        }else{
            $msg = "Hapa; exists";
        }
        /*$myfile = fopen("testfile_check.txt", "a");
        
        fwrite($myfile, $msg);
        fclose($myfile);*/
    }
    
    public function registerBizRegNumberInERP($reg_no)
    {
        $curl = curl_init();        
        $company = $this->company;

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ess.icta.go.ke/acredapitest/api/file/register-customer",
            CURLOPT_RETURNTRANSFER => true,          
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => json_encode(array( 'business_reg_no'=> $reg_no, "company_name" => $company->company_name,
              "registration_date"=> $company->registration_date, "county"=> $company->county, "town"=> $company->town,
                "street"=> $company->street,  "building"=> $company->building, "telephone_number"=> $company->telephone_number,
                "company_email"=> $company->company_email, "company_type_id"=> $company->company_type_id, "postal_address"=> $company->postal_address,
                "company_categorization"=> $company->company_categorization, "turnover"=> $company->turnover, "cashflow"=> $company->cashflow,
                "user_id"=> $company->user_id, "floor"=> $company->floor
            ) ), // Data sent in json format.
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "content-type: application/json",
            "x-api-key: 4a5fcaa28975b99da6b8221f8fdf7b72A",
            "Authorization: Bearer vTMKamOLPcWKiC2p9cc4DnUIB3OQ"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        /*$myfile = fopen("testfile_reg_biz.txt", "a");
        if ($err) {
          $msg = "cURL Error #:" . $err;
        } else {
          $msg = "OK #:" . $response;
        }
        fwrite($myfile, $msg);
        fclose($myfile);*/
    }
    
    public function updateApplicationPaymentOnERP()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://ess.icta.go.ke/acredapitest/api/file/create-customer-invoice",
            CURLOPT_RETURNTRANSFER => true,          
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POSTFIELDS => json_encode(
                array(
                    "business_reg_no" => $this->company->business_reg_no,
                    "application_id" => $this->id,
                    "amount_payable" => $this->getPayableAtLevel(),
                    "type_of_application" => $this->application_type
                ) 
            ), // Data sent in json format.
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json",
                "x-api-key: 4a5fcaa28975b99da6b8221f8fdf7b72A",
                "Authorization: Bearer vTMKamOLPcWKiC2p9cc4DnUIB3OQ"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        /*$myfile = fopen("testfile.txt", "a");
        if ($err) {
          $msg = "cURL Error #:" . $err;
        } else {
          $msg = "OK #:" . $response;
        }
        fwrite($myfile, $msg);
        fclose($myfile);*/
        //exit;
    }
    
    public function isUnique($mpesa_acct)
    {
        $app = Application::findOne(['mpesa_account' => $mpesa_acct]);
        if($app){
            return false;
        }
        return true;
    }
    
    public function genMpesaCode()
    {
        $string = 'SUP-' . Utility::generateMpesaAccountString();
        while(!$this->isUnique($string)){
            $string =  'SUP-'. Utility::generateMpesaAccountString();
        }
        $this->mpesa_account = $string;
        if($this->save(false)){
            return true;
        }
        return false;
    }
    
    public static function getAllAppsPerCategory($approved = false)
    {
        $and = '';
        if($approved){
            $and = 'WHERE a.status = "ApplicationWorkflow/completed"';
        }
        $sql = "SELECT COUNT(*) AS id, at.`name` FROM `application` a JOIN `accreditation_type` `at` 
            ON at.id=a.`accreditation_type_id` $and GROUP BY a.`accreditation_type_id`";
        $recs = Yii::$app->db->createCommand($sql)->queryAll();
        return $recs;
    }
    
    public static function getStatusSummary()
    {
        $sql = "SELECT COUNT(*) id, SUBSTR(`status`, 21, 50) `status` FROM `application` GROUP BY `status`";
        $recs = Yii::$app->db->createCommand($sql)->queryAll();
        return $recs;
    }
    
    public static function getAccreditationLevelData()
    {
        $sql = "SELECT COUNT(*) id, `classification` FROM `application_classification` 
                WHERE `classification` IS NOT NULL AND `classification` != 'reapply' GROUP BY `classification`";
        $recs = Yii::$app->db->createCommand($sql)->queryAll();
        return $recs;
    }
}
