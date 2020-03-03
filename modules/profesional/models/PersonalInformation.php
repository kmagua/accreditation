<?php

namespace app\modules\profesional\models;

use Yii;

/**
 * This is the model class for table "personal_information".
 *
 * @property int $id
 * @property string|null $idno
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
 * @property Application[] $applications
 * @property Education[] $educations
 * @property Employment[] $employments
 * @property ProfesionalRegBodies[] $profesionalRegBodies
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
            'idno' => 'Idno',
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
     * Gets query for [[ProfesionalRegBodies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProfesionalRegBodies()
    {
        return $this->hasMany(ProfesionalRegBodies::className(), ['user_id' => 'id']);
    }
}
