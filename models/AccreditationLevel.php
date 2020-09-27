<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "icta_acceditation_level".
 *
 * @property int $id
 * @property string|null $name
 * @property float|null $accreditation_fee
 * @property float|null $renewal_fee
 * @property string $date_created
 * @property string $last_updated
 */
class AccreditationLevel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'icta_accreditation_level';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['accreditation_fee', 'renewal_fee'], 'number'],
            [['date_created', 'last_updated'], 'safe'],
            ['name', \app\components\AlNumValidator::className()],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'accreditation_fee' => 'Accreditation Fee',
            'renewal_fee' => 'Renewal Fee',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }
}
