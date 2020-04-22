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
 * @property float|null $cash_flow
 * @property float|null $turnover
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
            [['company_id', 'accreditation_type_id', 'cash_flow', 'financial_status_link', 'turnover', 'application_type'], 'required'],
            [['company_id', 'accreditation_type_id', 'user_id', 'application_type', 'parent_id'], 'integer'],
            [['app_company_experience','app_staff', 'cash_flow', 'financial_status_link', 'turnover'], 'required','on'=>'create_update'],
            [['cash_flow', 'turnover'], 'number'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['status', 'certificate_serial'], 'string', 'max' => 50],
            [['previous_category'], 'string', 'max' => 20],
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
            'financial_status_link' => 'Audited Accounts Document Link',
            'user_id' => 'User ID',
            'status' => 'Status',
            'declaration' => 'I declare that the information given here is correct to the best of my knowledge.',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
            'app_company_experience' => 'Select company work experience to include in this application',
            'app_staff' => 'Select Staff Members to inlucde in this application',
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
    
    public function beforeValidate() 
    {
        parent::beforeValidate();
        if($this->application_type == 2 && $this->previous_category ==''){
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
        $this->on(WorkflowEvent::afterEnterStatus('ApplicationWorkflow/rejected'),
            [$this, 'sendRejectedEmail'], 'rejected'
    	);
    }
    
    public function getApplicationProgress()
    {
        switch($this->status){
            case 'ApplicationWorkflow/draft':
                return $this->assignCommitee(1);
            /*case 'ApplicationWorkflow/application-paid':
                return $this->processConfirmPayment(1);
            case 'ApplicationWorkflow/application-payment-confirmed':
                return $this->assignCommitee(1);
            case 'ApplicationWorkflow/application-payment-rejected':
                return $this->processApplicationFeeRejected();*/
            case 'ApplicationWorkflow/at-secretariat':
                return $this->processInternalCommittee(1);
            case 'ApplicationWorkflow/assign-approval-committee':
                return $this->assignCommitee(2, "Assign Approval Committee Members");
            case 'ApplicationWorkflow/at-committee':
                return $this->processInternalCommittee(2);
            case 'ApplicationWorkflow/approved':
                return $this->paymentConfirmation(2);
            case 'ApplicationWorkflow/certificate-paid':
                return $this->processConfirmPayment(2);           
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
            if(\Yii::$app->user->identity->inGroup($grp)){
                return Html::a("Assign Members ". Icon::show('users', ['class' => 'fa', 'framework' => Icon::FA]), [
                    'application/committee-members', 'id' => $this->id, 'l'=> $level], 
                        ['data-pjax'=>'0', 'title' => $message]);
            }
            return "Pending assignment of " . (($level==1)?"secretariat members.":"committee members.");
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
        $group = ($level == 1)?'Secretariat':'Committee member';
        if(\Yii::$app->user->identity->inGroup($group)){
            $title = ($level == 1) ? 'Score by ICTA Acceditation Secretariat' : 'Score by ICTA Approving Committee';
            return Html::a("Score " .Icon::show('comments', ['class' => 'fas', 'framework' => Icon::FAS]), [
                'application/approval', 'id' => $this->id, 'level'=> $level], 
                    ['data-pjax'=>'0', 'title' => $title]);
        }else{
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
        return Html::a("Renew certificate", ['application/cert-renewal', 'id' => $this->id], 
            ['data-pjax'=>'0']);
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
            $score = null;
            $comment = null;
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
            $app->progressWorkFlowStatus('rejected');
        }
    }
    
    /**
     * Send email to applicant after application has been approved by ICTA
     * @param type $event
     */
    public function sendApprovalEmail($event)
    {
        $header = "Your Company's accreditation request has been approved by ICT Authority";
        $type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['application/download-cert', 'id' => $this->id], true);
        
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
        $link = \yii\helpers\Url::to(['application/view', 'id' => $this->id], true);
        $app_categorization = ApplicationClassification::find()->where(['application_id' => $this->id])->
            orderBy('application_id desc')->one();
        $comment = ($app_categorization)?$app_categorization->rejection_comment:"";
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that your Accreditation request for $type has been REJECTED by ICT Authority because of the following reason.</p>
                <p>$comment</p>
                <p>$link</p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($this->company->company_email, $header, $message, $this->user->email);
        
    }
    
    public function sendPaymentRequestEmail($event)
    {
        $header = "ICT Authority - Payment Request for Company Accreditation";
        $type = $this->accreditationType->name;
        $link = \yii\helpers\Url::to(['company-profile/view', 'id' => $this->company_id], true);
        
        $message = <<<MSG
                Dear {$this->user->full_name},
                <p>Kindly note that your Accreditation request for $type has been reviewed and approved by ICT Authority.
                    You are now required to make payment of KES: {$this->getPayableAtLevel()} to: <br>CITIBANK,<br>Name: ICT Authority,<br>Account No: 0300085016,<br>Branch: Upper Hill (code: 16000).
                        </p>
                <p>After payment, login to the system using this link $link and upload your receipt. You will get a notification email to download your certificate once payment is confirmed.</p>
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
        $sql = "SELECT icm.user_id, app.status  FROM `icta_committee_member` icm 
            JOIN `application_committe_member` acm ON acm.`committee_member_id` = icm.`id`
            JOIN `application` app ON app.`id` = acm.`application_id`
            WHERE `committee_id` = $level AND icm.user_id = " . \Yii::$app->user->identity->id . "
                AND acm.application_id = $id";
        $recs = \Yii::$app->db->createCommand($sql)->queryOne();
        if($recs && in_array($recs['status'], ['ApplicationWorkflow/at-committee', 'ApplicationWorkflow/at-secretariat'])){
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
}
