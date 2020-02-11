<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "professional_certification".
 *
 * @property int $id
 * @property int $staff_id
 * @property string|null $qualification_type
 * @property string|null $other_description
 * @property string|null $certificate
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyStaff $staff
 */
class ProfessionalCertification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'professional_certification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id'], 'required'],
            [['staff_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['qualification_type', 'other_description'], 'string', 'max' => 50],
            [['certificate'], 'string', 'max' => 250],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyStaff::className(), 'targetAttribute' => ['staff_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'staff_id' => 'Staff ID',
            'qualification_type' => 'Qualification Type',
            'other_description' => 'Other Description',
            'certificate' => 'Certificate',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(CompanyStaff::className(), ['id' => 'staff_id']);
    }
}
