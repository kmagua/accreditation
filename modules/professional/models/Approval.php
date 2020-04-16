<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "approval".
 *
 * @property int $id
 * @property int $application_id
 * @property int $level
 * @property int $status 0 => new, 1 => Approved, 2 => Rejected
 * @property string $comment
 * @property int $user_id Approver
 * @property string $date_created
 * @property string $last_updated
 *
 * @property Application $application
 * @property \app\models\User $user
 */
class Approval extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'approval';
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
            [['application_id', 'level', 'user_id', 'status'], 'required'],
            [['application_id', 'level', 'status', 'user_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['comment'], 'string', 'max' => 200],
            [['application_id', 'level'], 'unique', 'targetAttribute' => ['application_id', 'level']],
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
            'level' => 'Approval Level',
            'status' => 'Status',
            'user_id' => 'Approver',
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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\app\models\User::className(), ['id' => 'user_id']);
    }
    
    /**
     * 
     * @return boolean
     */
    public function beforeValidate() 
    {
        parent::beforeValidate();
        if($this->status == 2 && $this->comment==''){
            $this->addError('comment', 'Comment cannot be empty for a rejected application.');
        }
        return true;
    }
}
