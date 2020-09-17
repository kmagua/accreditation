<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pre_system_accredited_companies".
 *
 * @property int $id
 * @property int|null $cert_reference
 * @property string|null $company_name
 * @property string|null $date_of_accreditation
 * @property string|null $valid_till
 * @property string|null $service_category
 * @property string|null $to_go
 * @property string|null $icta_grade
 */
class PreSystemAccreditedCompanies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pre_system_accredited_companies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cert_reference'], 'integer'],
            [['company_name'], 'string', 'max' => 200],
            [['date_of_accreditation', 'valid_till'], 'string', 'max' => 12],
            [['service_category'], 'string', 'max' => 50],
            [['to_go', 'icta_grade'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cert_reference' => 'Cert Reference',
            'company_name' => 'Company Name',
            'date_of_accreditation' => 'Date Of Accreditation',
            'valid_till' => 'Valid Till',
            'service_category' => 'Service Category',
            'to_go' => 'To Go',
            'icta_grade' => 'Icta Grade',
        ];
    }
}
