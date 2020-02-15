<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_company_experience".
 *
 * @property int $id
 * @property int $application_id
 * @property int|null $experience_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property Application $application
 * @property CompanyExperience $experience
 */
class ApplicationCompanyExperience extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_company_experience';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id'], 'required'],
            [['application_id', 'experience_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
            [['experience_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyExperience::className(), 'targetAttribute' => ['experience_id' => 'id']],
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
            'experience_id' => 'Experience ID',
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
     * Gets query for [[Experience]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExperience()
    {
        return $this->hasOne(CompanyExperience::className(), ['id' => 'experience_id']);
    }
}
