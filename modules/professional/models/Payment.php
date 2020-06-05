<?php

namespace app\modules\professional\models;

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
 * @property string $status
 * @property string|null $comment
 * @property int|null $confirmed_by
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
            [['application_id', 'level', 'confirmed_by'], 'integer'],
            [['billable_amount'], 'number'],
            [['status'], 'string'],
            [['date_created', 'last_update'], 'safe'],
            [['mpesa_code'], 'string', 'max' => 20],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf', 'maxSize'=> 1024*1024*2], 
            [['receipt'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 200],
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
            'billable_amount' => 'Billable Amount',
            'mpesa_code' => 'Mpesa Code',
            'receipt' => 'Receipt',
            'level' => 'Level',
            'status' => 'Status',
            'comment' => 'Comment',
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
                $this->receipt = 'uploads/receipts/prof-' . $this->application_id ."-" . microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->receipt):null;
                $this->application->status = 3; // this is for payment done pending confirmation
                $this->application->save(false);
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
     * 
     * @return string
     */
    public function savePaymentConfirmationDetails()
    {
        $this->application->initial_approval_date = date('Y-m-d');
        $this->application->status =( $this->status == 'confirmed' )?4:5;
        if( $this->application->cert_serial == '' ){
            $this->application->cert_serial = strtoupper(dechex($this->application->id * 100000081));
        }
        if($this->status == 'confirmed' && $this->application->parent_id != ''){
            Yii::$app->db2->createCommand()->update('accreditprof.application',
                ['status' => 4], ['id' =>$this->application->parent_id])->execute();
        }
        $this->application->save(false);
        return "Payment status updated successfully.";
    }
}
