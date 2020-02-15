<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_staff".
 *
 * @property int $id
 * @property int $application_id
 * @property int|null $staff_id
 * @property string|null $role
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property Application $application
 * @property CompanyStaff $staff
 */
class ApplicationStaff extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id'], 'required'],
            [['application_id', 'staff_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['role'], 'string', 'max' => 30],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
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
            'application_id' => 'Application ID',
            'staff_id' => 'Staff ID',
            'role' => 'Role',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['id' => 'application_id']);
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
