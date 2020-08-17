<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "company_document".
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $company_type_doc_id
 * @property string|null $date_created
 * @property string|null $last_updated
 * @property string|null $upload_file
 * 
 * @property CompanyTypeDocument $companyTypeDoc
 * @property CompanyProfile $company
 */
class CompanyDocument extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $uploadFile;
    public static function tableName()
    {
        return 'company_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'company_type_doc_id'], 'integer'],
            [['company_id', 'company_type_doc_id'], 'required'],
            [['date_created', 'last_updated'], 'safe'],
            [['upload_file'], 'string'],
            [['company_id', 'company_type_doc_id'], 'unique', 'targetAttribute' => ['company_id', 'company_type_doc_id'], 'message' => 'Document Type already uploaded.'],
            [['uploadFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png','pdf','doc', 'jpg'] , 'maxSize'=> 1024*1024*2],
            [['company_type_doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyTypeDocument::className(), 'targetAttribute' => ['company_type_doc_id' => 'id']],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyProfile::className(), 'targetAttribute' => ['company_id' => 'id']],
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
            'company_type_doc_id' => 'Document Type',
            'upload_file' => 'Document/Certificate', 
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[DocumentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyTypeDoc()
    {
        return $this->hasOne(CompanyTypeDocument::className(), ['id' => 'company_type_doc_id']);
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
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->upload_file != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->upload_file}'></span>" :
                \yii\helpers\Html::encode($this->upload_file);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->upload_file,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
    
    public function saveCompanyDocument()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->uploadFile = \yii\web\UploadedFile::getInstance($this, 'uploadFile');
            //if file uploaded
            if($this->uploadFile){
                $this->upload_file = 'uploads/company_documents/' . $this->company_id ."-" . $this->companyTypeDoc->documentType->name .'-'. microtime() .
                    '.' . $this->uploadFile->extension;
            }
            if($this->save()){                
                ($this->uploadFile)? $this->uploadFile->saveAs($this->upload_file):null;
                $transaction->commit();
                return true;
            }
            
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
        return false;
    }
}
