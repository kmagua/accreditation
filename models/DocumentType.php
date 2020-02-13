<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string|null $name
 * @property string $documents_upload
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyDocument[] $companyDocuments
 */
class DocumentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['documents_upload'], 'required'],
            [['date_created', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['documents_upload'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'documents_upload' => 'Documents Upload',
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
        return $this->hasMany(CompanyDocument::className(), ['document_type_id' => 'id']);
    }
}
