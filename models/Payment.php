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
            [['date_created', 'last_update'], 'safe'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['receipt'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 200],
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
            }
            $transaction->commit();
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
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
        $status = $this->status == 'confirmed'?'completed':'approval-payment-rejected';
        $this->application->initial_approval_date = date("Y-m-d");
        $this->application->certificate_serial = strtoupper(dechex($this->application->id * 100000027));
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
}
