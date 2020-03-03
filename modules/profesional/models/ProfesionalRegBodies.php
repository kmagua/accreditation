<?php

namespace app\modules\profesional\models;

use Yii;

/**
 * This is the model class for table "profesional_reg_bodies".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $membership_no
 * @property string|null $upload
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property int|null $user_id
 *
 * @property PersonalInformation $user
 */
class ProfesionalRegBodies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesional_reg_bodies';
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
            [['date_created', 'date_modified'], 'safe'],
            [['user_id'], 'integer'],
            [['name', 'upload'], 'string', 'max' => 100],
            [['membership_no'], 'string', 'max' => 20],
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
            'name' => 'Name',
            'membership_no' => 'Membership No',
            'upload' => 'Upload',
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
