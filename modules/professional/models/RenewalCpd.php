<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "renewal_cpd".
 *
 * @property int $id
 * @property int $renewal_id
 * @property string $type
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property string $upload
 * @property string $date_created
 * @property string $last_modified
 *
 * @property Application $renewal
 */
class RenewalCpd extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'renewal_cpd';
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
            [['renewal_id', 'type', 'start_date', 'end_date', 'upload'], 'required'],
            [['renewal_id'], 'integer'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'pdf'], 'maxSize'=> 1024*1024*2],
            [['start_date', 'end_date', 'date_created', 'last_modified'], 'safe'],
            [['type'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 150],
            [['upload'], 'string', 'max' => 100],
            [['renewal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['renewal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'renewal_id' => 'Renewal ID',
            'type' => 'Type',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'upload' => 'Upload',
            'date_created' => 'Date Created',
            'last_modified' => 'Last Modified',
            'upload_file' => 'Certificate/Paper/Evidence',
        ];
    }

    /**
     * Gets query for [[Renewal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRenewal()
    {
        return $this->hasOne(Application::className(), ['id' => 'renewal_id']);
    }
    
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) 
    {
        parent::beforeSave($insert);
        $this->start_date = date('Y-m-d', strtotime($this->start_date));
        $this->end_date = date('Y-m-d', strtotime($this->end_date));
        return true;
    }
    
    /**
     * 
     * @throws \Exception
     */
    public function saveRecord()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->upload_file = \yii\web\UploadedFile::getInstance($this, 'upload_file');
            //if file uploaded
            if($this->upload_file){
                $this->upload = 'uploads/professional_memberships/cpd_' . $this->renewal_id ."-" . microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->upload):null;
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
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->upload != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->upload}'></span>" :
                \yii\helpers\Html::encode($this->upload);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->upload,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
}
