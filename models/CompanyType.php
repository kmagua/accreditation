<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_type".
 *
 * @property int $id
 * @property string|null $name
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyDocument[] $companyDocuments
 */
class CompanyType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_created', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 30],
            ['name', \app\components\AlNumValidator::className()],
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
        return $this->hasMany(CompanyDocument::className(), ['company_type_id' => 'id']);
    }
}
