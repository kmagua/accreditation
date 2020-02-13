<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "company_document".
 *
 * @property int $id
 * @property int|null $company_id
 * @property int|null $document_type_id
 * @property string|null $date_created
 * @property string|null $last_updated
 * @property string|null $upload_file
 * 
 * @property DocumentType $documentType
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
            [['company_id', 'document_type_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['upload_file'], 'string'],
            [['uploadFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png,pdf,doc, jpg'],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
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
            'document_type_id' => 'Document Type ID',
             'upload_file' => 'Upload File', 
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[DocumentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentType()
    {
        return $this->hasOne(DocumentType::className(), ['id' => 'document_type_id']);
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
    
    
   
    
}
