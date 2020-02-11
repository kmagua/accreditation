<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "academic_qualification".
 *
 * @property int $id
 * @property int|null $staff_id
 * @property string $level
 * @property string $course_name
 * @property resource $certificate
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyStaff $staff
 */
class AcademicQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'academic_qualification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id'], 'integer'],
            [['level', 'course_name', 'certificate'], 'required'],
            [['level'], 'string'],
            [['date_created', 'last_updated'], 'safe'],
            [['course_name', 'certificate'], 'string', 'max' => 100],
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
            'level' => 'Level',
            'course_name' => 'Course Name',
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
