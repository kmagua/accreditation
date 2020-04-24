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
            [['user_id', 'category_id'], 'integer'],
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
            'declaration' => 'I declare that the information provided is correct to the best of my knowledge.',
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
        if($this->status == 1){
            return 'Pending Payment';
        }else if($this->status == 2){
            return 'Rejected';
        }else if($this->status == 3){
            return \yii\helpers\Html::a('Approve/Reject Payment', [
                '/professional/application/approve-payment', 'id'=>$this->id
            ], 
            ['onclick' => "getDataForm(this.href, '<h3>Upload Application Payment Receipt</h3>'); return false;"]);
        }else if($this->status == 4){
            return 'Complete';
        }else if($this->status == 5){
            return 'Payment Rejected';
        }
        return 'Pending Approval';
    }
}
