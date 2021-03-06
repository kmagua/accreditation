<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int|null $application_id
 * @property float|null $billable_amount
 * @property string|null $mpesa_code
 * @property string|null $receipt
 * @property int|null $level
 * @property string|null $status
 * @property string|null $comment
 * @property string $date_created
 * @property string|null $last_update
 *
 * @property Application $application
 */
class Payment extends \yii\db\ActiveRecord
{
    public $upload_file;
    public $date_range;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'level'], 'integer'],
            [['billable_amount'], 'number'],
            [['date_created', 'last_update', 'date_range'], 'safe'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'maxSize'=> 1024*1024*2],            
            [['receipt'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 200],
            [['mpesa_code', 'comment'], \app\components\AlNumValidator::className()],
            [['mpesa_code', 'status'], 'string', 'max' => 20],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'application_id' => 'Application ID',
            'billable_amount' => 'Receipt Amount',
            'mpesa_code' => 'Mpesa Code',
            'receipt' => 'Receipt',
            'status' => 'Payment Status',
            'date_created' => 'Date Created',
            'last_update' => 'Last Update',
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['id' => 'application_id']);
    }
    
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) 
    {
        parent::beforeSave($insert);
        if($this->status == ''){
            $this->status = NULL;
        }
        return true;
    }
    
    /**
     * 
     * @throws \Exception
     */
    public function savePayment()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->status = 'paid';
            $this->upload_file = \yii\web\UploadedFile::getInstance($this, 'upload_file');
            //if file uploaded
            if($this->upload_file){
                $this->receipt = 'uploads/receipts/' . $this->application_id ."-" . $this->level .'-'. microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->receipt):null;
                $transaction->commit();
                return true;
            }            
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
        return false;
    }
    
    /**
     * 
     */
    public function updateApplicationPaymentStatus()
    {
        /*if($this->level == 1){
            $status = $this->status == 'confirmed'?'application-payment-confirmed':'application-payment-rejected';
        }else{
            $status = $this->status == 'confirmed'?'completed':'approval-payment-rejected';
        }*/
        $status = $this->status == 'confirmed'?'chair-approval':'approval-payment-rejected';
        $this->application->getInitApprovalDate();
        //update status of the parent to completed if renewal and payment is confirmed
        if($this->status == 'confirmed' && $this->application->parent_id != ''){
            Yii::$app->db->createCommand()->update('accreditcomp.application',
                ['status' => 'ApplicationWorkflow/chair-approval'], ['id' =>$this->application->parent_id])->execute();
        }
        //only set serial # for the original application
        $cert_serial_id = ($this->application->parent_id == '') ? $this->application->id : $this->application->parent_id;
        $this->application->certificate_serial = strtoupper(dechex($cert_serial_id * 100000027));
        $this->application->progressWorkFlowStatus($status);
    }
    
    /**
     * Receipt download
     * @return string
     */
    public function getReceipt($btn = false)
    {
        if($this->receipt != ''){
            $class = ($btn == true)?['data-pjax'=>'0', 'target'=>'_blank', 'class'=>'btn btn-danger'] : 
                ['data-pjax'=>"0", 'target'=>'_blank'];
            /*$text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->upload_file}'></span>" :
                \yii\helpers\Html::encode($this->upload_file);*/
            $path = Yii::getAlias('@web') .'/';
            return \yii\helpers\Html::a("<span class='glyphicon glyphicon-download-alt' title='Download Receipt'> Download Payment Receipt</span>",$path . $this->receipt,$class);
        }else{
            return '';
        }
    }
    
    /**
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->receipt != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->receipt}'></span>" :
                \yii\helpers\Html::encode($this->receipt);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->receipt,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
    
    /**
     * 
     * @param type $provider
     * @param type $fieldName
     * @return type
     */
    public static function getTotal($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        // add number_format() before return
        $sum = number_format( $total, 2 );

        return $sum;
    }
}
