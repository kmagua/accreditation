<?php

namespace app\models;

use Yii;
use app\components\AlphaValidator;

/**
 * This is the model class for table "company_profile".
 *
 * @property int $id
 * @property string $business_reg_no
 * @property string $company_name
 * @property string|null $registration_date
 * @property string|null $county
 * @property string|null $town
 * @property string|null $building
 * @property string|null $floor
 * @property string|null $telephone_number
 * @property string|null $company_email
 * @property string|null $company_type_id
 * @property string|null $postal_address
 * @property string|null $company_categorization
 * @property string|null $turnover
 * @property string|null $cashflow
 * @property int|null $user_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property Application[] $applications
 * @property CompanyDocument[] $companyDocuments
 * @property CompanyExperience[] $companyExperiences
 * @property User $user
 * @property CompanyStaff[] $companyStaff
 */
class CompanyProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_reg_no', 'company_name', 'registration_date', 'county', 'company_email', 'telephone_number', 'company_categorization', 'company_type_id'], 'required'],
            [['registration_date', 'date_created', 'last_updated'], 'safe'],
            [['company_type_id', 'company_categorization'], 'string'],
            [['user_id'], 'integer'],            
            [['business_reg_no', 'county', 'floor', 'turnover', 'cashflow'], 'string', 'max' => 20],
            [['company_name', 'telephone_number'], 'string', 'max' => 100],
            ['company_name', 'match', 'pattern' => '/^[0-9\sa-z-_]+$/i', 'message'=>'{attribute} can only have alphanumerics, underscore or a hyphen.'],
            ['business_reg_no', 'match', 'pattern' => '/^[0-9\s,a-z-]+$/i', 'message'=>'Business Registration Number can only have Alphanumerics and a hyphen.'],
            ['telephone_number', 'match', 'pattern' => '/^[0-9\s,]+$/', 'message'=>'Telephone can only have numbers and separated by a comma.'],
            [['company_email'], 'email'],
            ['town', AlphaValidator::className()],
            [['town', 'building'], 'string', 'max' => 40],
            [['postal_address'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_reg_no' => 'Business Reg No',
            'company_name' => 'Company Name',
            'registration_date' => 'Business/Company Registration Date',
            'county' => 'County',
            'town' => 'Town',
            'building' => 'Building',
            'floor' => 'Floor',
            'telephone_number' => 'Telephone Number',
            'company_email' => 'Company Email',
            'company_type_id' => 'Type Of Business',
            'postal_address' => 'Postal Address',
            'company_categorization' => 'Company Categorization',
            'turnover' => 'Turnover [KES]',
            'cashflow' => 'Cash Flow [KES]',
            'user_id' => 'User ID',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDocuments()
    {
        return $this->hasMany(CompanyDocument::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyExperiences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyExperiences()
    {
        return $this->hasMany(CompanyExperience::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[CompanyStaff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyStaff()
    {
        return $this->hasMany(CompanyStaff::className(), ['company_id' => 'id']);
    }
    
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) 
    {
        parent::beforeSave($insert);
        
        if($insert){
            $this->user_id = Yii::$app->user->identity->user_id;
        }
        $this->registration_date = date('Y-m-d', strtotime($this->registration_date));
        return true;
    }
    
    /**
     * Check if user has permission to view/update record
     * @param type $id
     * @return boolean
     */
    public static function canAccess($id)
    {
        $rec = CompanyProfile::findOne($id);
        if($rec && $rec->user_id == Yii::$app->user->identity->user_id){
            return true;
        }
        return false;
    }
}
