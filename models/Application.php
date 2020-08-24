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
            [$this, 'sendPaymentRequestEmail'], 'approved'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/sec-rejected'),
            [$this, 'sendRejectedEmail'], 'rejected'
    	);
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/com-rejected'),
            [$this, 'sendRejectedEmail'], 'rejected'
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
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['revert_rejection', 'required', 'on' => ['revert_rejection'], 'requiredValue' => 1, 
                'message' => 'You must confirm to have addressed issues raised in the rejection comment.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['status', 'certificate_serial'], 'string', 'max' => 50],
            [['previous_category', 'cash_flow', 'turnover'], 'string', 'max' => 20],
            //[['company_id', 'accreditation_type_id'], 'unique', 'targetAttribute' => ['accreditation_type_id'], 'message' => 'You have already submitted an application for the selected Accreditation Category'],
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
            'application_type' => 'New or Annual Renewal'
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
                    }
                }
            }
            $this->checkDuplicates();
        }
        
        return true;
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
                return $this->processRejected(2);
            case 'ApplicationWorkflow/certificate-paid':
                return $this->processConfirmPayment(2);           
            case 'ApplicationWorkflow/approval-payment-rejected':
                return $this->processApprovalFeeRejected();
            case 'ApplicationWorkflow/completed':
                return $this->processCompleted();
            case 'ApplicationWorkflow/renewal':
                return $this->processRenewal();
            case 'ApplicationWorkflow/renewed':
                return 'Pending Renewal Approval';
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
                    ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
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
    public function processApplicationFeeRejected()
    {
        return "TBA";
    }
    
    /**
     * Internal committees scoring
     * @param type $level
     * @return string
     */
    public function processInternalCommittee($level)
    {
        $group = ($level == 1)?'Secretariat':'Committee';
        if(Application::canApprove($level, $this->id)){
            $title = ($level == 1) ? 'Score by ICTA Acceditation Secretariat' : 'Score by ICTA Approving Committee';
            return Html::a("Score " .Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
                'application/approval', 'id' => $this->id, 'level'=> $level], 
                    ['data-pjax'=>'0', 'title' => $title]);
        }else{
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
        return "TBA";
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
        return Html::a("Revert After Rejection", 
            ['application/revert-rejection', 'id' => $this->id, 'l'=> $level], 
            [
                'data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Revert After Rejection</h3>'); return false;",
                'title' => 'Confirm that all issues during Rejection of application are resolved.'
            ]
        );
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
        $score_items_sql ="SELECT id FROM `score_item`";
        $uid = \Yii::$app->user->identity->user_id;
        $score_items_data = \Yii::$app->db->createCommand($score_items_sql)->queryAll();
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
                ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP";
                
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
        //$type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['/application/view', 'id' => $this->id], true);
        $score_link = \yii\helpers\Url::to(['/application/approval', 'id' => $this->id, 'level' => $level->data], true);
        $main_paragraph = ($prev_status != 'ApplicationWorkflow/sec-rejected')?"Kindly note that you have been selected to review/score an application for accreditation.":
            "Kindly note that an application that you(group) had rejected has been updated by the applicant. Kindly log on to the system to review again.";
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
     */
    public static function progressOnCommitteeApproval($id, $status, $level)
    {
        $app = Application::findOne($id);
        if($level == 1 && $status == 1){
            $app->progressWorkFlowStatus('assign-approval-committee');
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
                        </p>
                <p><strong>After payment, deliver the bank slip to ICTA - Telposta Towers 12th floor, Finance Department to be issued a receipt which you will then upload on the system using this link {$link}. You will get a notification email to download your certificate once your uploaded receipt is confirmed.</strong></p>
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
        $lvl = ($level == 1)?'ApplicationWorkflow/at-secretariat':'ApplicationWorkflow/at-committee';
        $sql = "SELECT icm.user_id, app.status  FROM `icta_committee_member` icm 
            JOIN `application_committe_member` acm ON acm.`committee_member_id` = icm.`id`
            JOIN `application` app ON app.`id` = acm.`application_id`
            WHERE `committee_id` = $level AND icm.user_id = " . \Yii::$app->user->identity->id . "
                AND acm.application_id = $id AND app.status = '$lvl'";
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
            WHERE dt.name = 'business permit' AND cd.company_id = $cid";
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
            $sql = "SELECT * FROM accreditcomp.application WHERE
                (parent_id = {$this->parent_id} or id = {$this->parent_id})
                 AND initial_approval_date is not null order by id desc limit 1";
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
        $sql = "SELECT `classification` FROM accreditcomp.application app 
            JOIN accreditcomp.`application_classification` ac ON ac.`application_id` = app.id  
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
        if($this->save()){
            $sql = "UPDATE `accreditcomp`.`application` set
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
        $company_docs = CompanyDocument::find()->where("company_id = $company_id")->one();
        if(!$company_docs){
            $missing[] = 'Company Registration and Compliance not uploaded.';
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
    
}
