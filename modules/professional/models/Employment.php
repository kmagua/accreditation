<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "employment".
 *
 * @property int $id
 * @property string|null $organisation_name
 * @property string|null $organisation_email
 * @property string|null $organisation_phone
 * @property string|null $job_title
 * @property string|null $role
 * @property string|null $postal_address
 * @property string|null $website
 * @property string|null $supervisor_name
 * @property string|null $supervisor_designation
 * @property string|null $supervisor_email
 * @property string|null $supervisor_phone
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property int|null $user_id
 *
 * @property PersonalInformation $user
 */
class Employment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employment';
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
            [['role'], 'string'],
            [['user_id', 'organisation_name', 'job_title', 'organisation_name'], 'required'],
            [['date_created', 'date_modified'], 'safe'],
            [['user_id'], 'integer'],
            [['organisation_name'], 'string', 'max' => 100],
            [['organisation_email', 'postal_address', 'website', 'supervisor_name', 'supervisor_designation', 'supervisor_email', 'supervisor_phone'], 'string', 'max' => 50],
            [['organisation_phone'], 'string', 'max' => 15],
            [['job_title'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalInformation::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organisation_name' => 'Organisation Name',
            'organisation_email' => 'Organisation Email',
            'organisation_phone' => 'Organisation Phone',
            'job_title' => 'Job Title',
            'role' => 'Role',
            'postal_address' => 'Postal Address',
            'website' => 'Website',
            'supervisor_name' => 'Supervisor Name',
            'supervisor_designation' => 'Supervisor Designation',
            'supervisor_email' => 'Supervisor Email',
            'supervisor_phone' => 'Supervisor Phone',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(PersonalInformation::className(), ['id' => 'user_id']);
    }
}
