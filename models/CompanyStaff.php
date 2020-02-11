<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_staff".
 *
 * @property int $id
 * @property int $company_id
 * @property string $first_name
 * @property string $last_name
 * @property int|null $national_id
 * @property string|null $kra_pin
 * @property string|null $gender
 * @property string|null $dob
 * @property string|null $disability_status
 * @property string|null $title
 * @property string|null $staff_type
 * @property string|null $status 0 for inactive, 1 for active
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property AcademicQualification[] $academicQualifications
 * @property ApplicationStaff[] $applicationStaff
 * @property CompanyProfile $company
 * @property ProfessionalCertification[] $professionalCertifications
 * @property StaffExperience[] $staffExperiences
 */
class CompanyStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'first_name', 'last_name'], 'required'],
            [['company_id', 'national_id'], 'integer'],
            [['gender', 'disability_status', 'staff_type', 'status'], 'string'],
            [['dob', 'date_created', 'last_updated'], 'safe'],
            [['first_name', 'last_name'], 'string', 'max' => 30],
            [['kra_pin'], 'string', 'max' => 15],
            [['title'], 'string', 'max' => 100],
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
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'national_id' => 'National ID',
            'kra_pin' => 'Kra Pin',
            'gender' => 'Gender',
            'dob' => 'Dob',
            'disability_status' => 'Disability Status',
            'title' => 'Title',
            'staff_type' => 'Staff Type',
            'status' => 'Status',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[AcademicQualifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcademicQualifications()
    {
        return $this->hasMany(AcademicQualification::className(), ['staff_id' => 'id']);
    }

    /**
     * Gets query for [[ApplicationStaff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationStaff()
    {
        return $this->hasMany(ApplicationStaff::className(), ['staff_id' => 'id']);
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
     * Gets query for [[ProfessionalCertifications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfessionalCertifications()
    {
        return $this->hasMany(ProfessionalCertification::className(), ['staff_id' => 'id']);
    }

    /**
     * Gets query for [[StaffExperiences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaffExperiences()
    {
        return $this->hasMany(StaffExperience::className(), ['staff_id' => 'id']);
    }
}
