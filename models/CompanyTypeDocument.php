<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_type_document".
 *
 * @property int $id
 * @property int $company_type_id
 * @property int $document_type_id
 * @property int|null $user_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyDocument[] $companyDocuments
 * @property CompanyType $companyType
 * @property DocumentType $documentType
 */
class CompanyTypeDocument extends \yii\db\ActiveRecord
{
    public $name;//used for JOIN searching
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_type_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_type_id', 'document_type_id'], 'required'],
            [['company_type_id', 'document_type_id', 'user_id'], 'integer'],
            [['date_created', 'last_updated', 'name'], 'safe'],
            [['company_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyType::className(), 'targetAttribute' => ['company_type_id' => 'id']],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_type_id' => 'Company Type ID',
            'document_type_id' => 'Document Type ID',
            'user_id' => 'User ID',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[CompanyDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDocuments()
    {
        return $this->hasMany(CompanyDocument::className(), ['company_type_doc_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyType()
    {
        return $this->hasOne(CompanyType::className(), ['id' => 'company_type_id']);
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
     * 
     * @param type $cti CompanyTypeID
     */
    public static function getApplicableDocumentTypes($cti)
    {
        $data = CompanyTypeDocument::find()
            ->select("ctd.id, dt.name name")
            ->from('company_type_document ctd')
            ->join('JOIN', 'company_type ct', 'ct.id = ctd.company_type_id')
            ->join('JOIN', 'document_type dt', 'dt.id = ctd.document_type_id')
            ->where(['ct.id'=>[$cti, -1000]])->all();
        return $data;
    }
}
