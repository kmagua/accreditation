<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accreditation_type".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string $date_created
 * @property string|null $last_updated
 */
class AccreditationType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accreditation_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_created', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Accreditation Type',
            'description' => 'Description',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }
}
