<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "personal_information".
 *
 * @property int $id
 * @property string|null $idno
 * @property int $user_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $other_names
 * @property string|null $date_of_birth
 * @property string|null $gender
 * @property string|null $phone
 * @property string|null $nationality
 * @property string|null $county
 * @property string|null $postal_address
 * @property string|null $date_created
 * @property string|null $date_modified
 *
 * @property \app\models\User $usr
 * @property Application[] $applications
 * @property Education[] $educations
 * @property Employment[] $employments
 * @property ProfessionalRegBodies[] $professionalRegBodies
 */
class PersonalInformation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'personal_information';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db2');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'number'],
            [['user_id', 'date_of_birth', 'gender', 'phone', 'last_name', 'first_name', 'nationality'], 'required'],
            [['date_of_birth', 'date_created', 'date_modified'], 'safe'],
            [['gender'], 'string'],
            [['idno', 'phone'], 'string', 'max' => 15],
            [['first_name', 'last_name', 'nationality', 'county'], 'string', 'max' => 50],
            [['other_names', 'postal_address'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'idno' => 'ID/Passport Number',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'other_names' => 'Other Names',
            'date_of_birth' => 'Date Of Birth',
            'gender' => 'Gender',
            'phone' => 'Phone',
            'nationality' => 'Nationality',
            'county' => 'County',
            'postal_address' => 'Postal Address',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
        ];
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Educations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEducations()
    {
        return $this->hasMany(Education::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Employments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployments()
    {
        return $this->hasMany(Employment::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ProfessionalRegBodies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfessionalRegBodies()
    {
        return $this->hasMany(ProfessionalRegBodies::className(), ['user_id' => 'id']);
    }
    
    /**
     * Gets query for [[ProfessionalRegBodies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsr()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }
    
    /**
     * Check if user has permission to view/update record
     * @param type $id
     * @return boolean
     */
    public static function canAccess($id)
    {
        $rec = PersonalInformation::findOne($id);
        if($rec && $rec->user_id == Yii::$app->user->identity->user_id){
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if($this->date_of_birth){
            $this->date_of_birth = date("Y-m-d", strtotime($this->date_of_birth));
        }
        return true;
    }
    
    /**
     * Check if logged in user can access an application details
     * @return boolean
     */
    public function checkUserCanAccess()
    {
        $user_grp = strtolower(\Yii::$app->user->identity->group);
        if(in_array($user_grp, ['admin', 'applicant'])){
            if($user_grp == 'admin'){
                return true;
            }
            return CompanyProfile::canAccess($this->company_id);
        }else{
            return false;
        }
    }
    
    public function getNames()
    {
        return $this->first_name . ' '. $this->last_name . " " . $this->other_names;
    }
}
