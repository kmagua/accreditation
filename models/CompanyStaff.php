<?php

namespace app\models;

use Yii;
use \kartik\icons\Icon;

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
            [['company_id', 'first_name', 'last_name', 'gender', 'disability_status'], 'required'],
            //[['first_name', 'last_name'], 'match', 'pattern' => '/^[a-z]\w*$/i'],
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
            'dob' => 'Date of Birth',
            'disability_status' => 'Disabled?',
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
    
    /**
     * 
     * @param type $insert
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if($this->dob != ''){
            $this->dob = date('Y-m-d', strtotime($this->dob));
        }
        return true;
    }
    
    /**
     * Get staff name
     * @return type string
     */
    public function getNames()
    {
        return $this->first_name . ' '. $this->last_name;
    }
    
    /**
     * 
     * @param type $editable (Whether to show form)
     * @return type
     */
    public function getStaffDetailsLinks($editable=1)
    {       
        $ac_url = yii\helpers\Url::to(['academic-qualification/data', 'sid'=>$this->id, 'e'=>$editable]);
        $ac_link = \yii\helpers\Html::a('Academics ' . Icon::show('graduation-cap', ['class' => 'fas',
            'framework' => Icon::FAS]) . ' |', $ac_url, ['title' =>"Staff's Academic Qualifications",
            'onclick'=>"getDataForm('$ac_url', '<h3>Academic Qualifications for " . $this->getNames() . "</h3>'); return false;"]);
        
        $pc_url = yii\helpers\Url::to(['professional-certification/data', 'sid'=>$this->id, 'e'=>$editable]);
        $pc_link = \yii\helpers\Html::a('Certifications ' . Icon::show('certificate', ['class' => 'fas',
            'framework' => Icon::FAS]) . ' |', $pc_url, ['title' =>"Professional Certification",
            'onclick'=>"getDataForm('$pc_url', '<h3>Professional Certifications for " . $this->getNames() . "</h3>'); return false;"]);
        
        $xp_url = yii\helpers\Url::to(['staff-experience/data', 'sid'=>$this->id, 'e'=>$editable]);
        $xp_link = \yii\helpers\Html::a('Work History ' . Icon::show('tasks', ['class' => 'fas',
            'framework' => Icon::FAS]), $xp_url, ['title' =>"Work Experience",
            'onclick'=>"getDataForm('$xp_url', '<h3>Work Experience for " . $this->getNames() . "</h3>'); return false;"]);
        
        return $ac_link .'&nbsp;&nbsp;' . $pc_link . '&nbsp;&nbsp;' .$xp_link;
    }
}
