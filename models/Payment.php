<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int|null $application_id
 * @property float|null $billable_amount
 * @property string|null $mpesa_code
 * @property string|null $status
 * @property string $date_created
 * @property string|null $last_update
 *
 * @property Application $application
 */
class Payment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id'], 'integer'],
            [['billable_amount'], 'number'],
            [['date_created', 'last_update'], 'safe'],
            [['mpesa_code', 'status'], 'string', 'max' => 20],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
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
            'billable_amount' => 'Billable Amount',
            'mpesa_code' => 'Mpesa Code',
            'status' => 'Status',
            'date_created' => 'Date Created',
            'last_update' => 'Last Update',
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
}
