<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property string|null $status
 * @property string|null $declaration
 * @property string|null $initial_approval_date
 * @property string|null $cert_serial
 * @property int|null $parent_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property PersonalInformation $user
 * @property Category $category
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'parent_id'], 'integer'],
            //[['declaration'], 'string'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['initial_approval_date', 'date_created', 'last_updated'], 'safe'],
            [['status'], 'string', 'max' => 50],
            [['cert_serial'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalInformation::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Name',
            'category_id' => 'Accreditation Category',
            'status' => 'Status',
            'declaration' => 'I agree to abide by the code of Professional Conduct.',
            'initial_approval_date' => 'Initial Approval Date',
            'date_created' => 'Date Submitted',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(PersonalInformation::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    /**
     * 
     * @param type $insert
     */
    public function beforeSave($insert) 
    {
        if($this->status == ''){
            $this->status = null;
        }        
        parent::beforeSave($insert);
        return true;
    }
    
    /**
     * 
     * @param int $pid \app\modules\professional\models\PersonalInformation ID
     * @return \app\modules\professional\models\Application
     */
    public static function getAppModel($pid)
    {
        $sql = "SELECT * FROM `accreditprof`.`application` WHERE user_id = $pid and parent_id is null order by id";
        if (($model = Application::findBySql($sql)->one()) === null) {
            $model = new Application();
            $model->user_id = $pid;
        }
        return $model;
    }


    /**
     * 
     */
    public function notifyUserOfApproval($status, $comment)
    {
        $approval_status = ($status == 1)?'Approved':'Rejected';
        $header = "Your Professional accreditation request has been $approval_status by ICT Authority";
        $type = $this->category->name;
        $applicable_fee = $this->category->application_fee;
        $link = \yii\helpers\Url::to(['/professional/personal-information/view', 'id' => $this->id], true);
        $msg_status = ($status == 1)?"You can login in to the Authority's accreditation 
            site using the link below to upload your payment receipt for the applicable ayment of KES {$applicable_fee}.</p>
            <p>$link</p>": "Below is the stated reason for rejection</p>. <p><i>$comment</i></p>";
        $message = <<<MSG
            Dear {$this->user->first_name} {$this->user->last_name},
            <p>Kindly note that your Accreditation request for $type has been $approval_status by ICT Authority.
              $msg_status  
            <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        \app\models\Utility::sendMail($this->user->usr->email, $header, $message);
    }
    
    public function sendPaymentRequestEmail()
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
    
    public function savePayment()
    {
        $pay = new Payment();
        $pay->setAttributes([
            'application_id' => $this->id,
            'billable_amount' => $this->category->application_fee,
            'status' => 'new'
        ]);        
        return $pay->save(false);
    }
    
    /**
     * 
     * @return string
     */
    public function getStatus()
    {
        switch($this->status){
            case 1:
                return $this->getPaymentLink();
            case 2:
                return 'Rejected at Secretariat';
            case 3:
                return $this->confirmPayment();
            case 4:
                return \yii\helpers\Html::a('Download Certificate', [
                    '/professional/application/download-cert', 'id' => $this->id
                ]);
            case 5:
                return 'Payment Rejected';
            case 6:
                return $this->renewalLink();
            case 7:
                return 'Rejected at Committee';
            case 8:
                return $this->getApprovalLink(1);
            case 9:
                return $this->getApprovalLink(2);
            case 10:
                return $this->committeeMembersLink(2, 'Assign Committee Members');
            case 12:
                return $this->renewalLink();
            default :
                return $this->committeeMembersLink(1);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getUserStatus()
    {
        switch($this->status){
            case 1:
                return $this->getPaymentLink();
            case 2:
                return 'Rejected';
            case 3:
                return 'Pending Confirmation of Payment';
            case 4:
                return \yii\helpers\Html::a('Download Certificate', [
                    '/professional/application/download-cert', 'id'=>$this->id
                ]);
            case 5:
                return 'Payment Rejected';
            case 6:
                return $this->renewalLink();
            case 7:
                return 'Rejected';
            case 12:
                return $this->renewalLink();
            default :
                return 'Pending Approval';
        }
    }
    
    /**
     * creates an approval link
     */
    public function getApprovalLink($level)
    {
        $grp = ($level==1)?"Secretariat":"Committee member";
        if(\Yii::$app->user->identity->inGroup($grp)){
            $l = ($level == 1)?"Secretariat":"Committee";
            return \yii\helpers\Html::a("Approve at $l", [
                    '/professional/approval/create-ajax', 'app_id'=>$this->id, 'l' => $level
                ], 
                ['onclick' => "getDataForm(this.href, '<h3>Application Approval at {$l}</h3>'); return false;"]
            );
        }
        return 'Pending Approval';
    }
    
    /**
     * 
     * @return string
     */
    public function getPaymentLink()
    {
        if(PersonalInformation::canAccess($this->user_id)
            || Yii::$app->user->identity->inGroup('admin')){
            return \yii\helpers\Html::a('Upload Payment Receipt', [
                    '/professional/application/upload-receipt', 'id'=>$this->id
                ], 
                ['onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]
            );
        }
        return 'Pending Payment';
    }
    
    /**
     * 
     * @param type $level
     * @param type $message
     * @return string
     */
    public function committeeMembersLink($level, $message = 'Assign secretariat members')
    {
        if(\Yii::$app->user->identity->isInternal()){
            $grp = ($level==1)?"Secretariat":"Committee member";
            if(\Yii::$app->user->identity->inGroup($grp)){
                return \yii\helpers\Html::a("Assign {$grp} Members ". \kartik\icons\Icon::show('users', ['class' => 'fa', 'framework' => \kartik\icons\Icon::FA]), [
                    '/professional/application/committee-members', 'id' => $this->id, 'l'=> $level], 
                        ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Application {$grp} Members</h3>'); return false;",
                            'title' => $message]);
            }
            return "Pending assignment of " . (($level==1)?"secretariat members.":"committee members.");
        }
        return "Pending";
    }
    
    /**
     * 
     * @return type
     */
    public function confirmPayment()
    {
        if(\Yii::$app->user->identity->isInternal()){
            return \yii\helpers\Html::a("Confirm Payment ", [
                '/professional/application/approve-payment', 'id' => $this->id], 
                    ['data-pjax'=>'0', 'onclick' => "getDataForm(this.href, '<h3>Payment Confirmation</h3>'); return false;",
                        'title' => 'Confirm application\' payment']);
        }
    }
    
    /**
     * 
     * @return string
     */
    public function renewalLink()
    {
        if(PersonalInformation::canAccess($this->user_id) || \Yii::$app->user->identity->inGroup('admin')){
            return \yii\helpers\Html::a('Renew Certificate', [
                '/professional/application/renewal', 'id'=>$this->id, 'piid' => $this->user_id
            ]);
        }
        return 'Pending Renewal';
    }
    /**
     * 
     * @param type $parent_id
     * @param type $piid
     * @return \app\modules\professional\models\Application
     * @throws \Exception
     */
    public static function getRenewal($parent_id, $piid)
    {
        $sql = "SELECT *  
            FROM `accreditprof`.`application`
            WHERE parent_id = $parent_id AND (status <> 4 OR status IS NULL) ORDER BY id DESC";
        $application = Application::findBySql($sql)->one();
        if(!$application){
            $parent = Application::findOne($parent_id);
            $application = new Application();
            $application->setAttributes(['parent_id' => $parent_id, 'user_id' => $piid,
                'category_id' => $parent->category_id, 'declaration' => 1, 'status' => 12
            ]);
            $transaction = \Yii::$app->db->beginTransaction();
            try{
                $parent->status = 4;
                if($application->save(false) && $parent->save(false)){
                    $transaction->commit();
                }else{
                    throw new \Exception('Could not Create your Renewal.');
                }       
            }catch(\Exception $e){
                $transaction->rollBack();
            }
        }
        return $application;
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
     * @param type $mother_app_id
     * @return \yii\data\ActiveDataProvider
     */
    public static function getRenewals($mother_app_id)
    {
        $apps = Application::find()->select('id')->where(['parent_id' => $mother_app_id])->all();
        
        $query = RenewalCpd::find();
        
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        if($apps){
            $app_ids = array_column($apps, 'id');
            $query->andFilterWhere(['in', 'renewal_id', $app_ids]);
        }
        return $dataProvider;
    }
}