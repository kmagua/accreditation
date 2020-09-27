<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff_experience".
 *
 * @property int $id
 * @property int $staff_id
 * @property string|null $organization
 * @property string|null $role
 * @property string|null $assignment
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $date_created
 * @property string|null $last_updated
 *
 * @property CompanyStaff $staff
 */
class StaffExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff_experience';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'assignment', 'organization','start_date','role'], 'required'],
            [['staff_id'], 'integer'],
            [['start_date', 'end_date', 'date_created', 'last_updated'], 'safe'],
            [['role', 'organization'], 'string', 'max' => 100],
            [['assignment'], 'string', 'max' => 200],
            [['assignment', 'organization', 'role'],'match', 'pattern' => '/^[0-9\sa-z-_]+$/i', 'message'=>'{attribute} can only have alphanumerics, underscore or a hyphen.'],
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
            'organization' => 'Organization',
            'role' => 'Role',
            'assignment' => 'Duties',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
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
    
    /**
     * overridden function
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if($this->start_date != ''){
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if($this->end_date != ''){
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }
        return true;
    }
}
