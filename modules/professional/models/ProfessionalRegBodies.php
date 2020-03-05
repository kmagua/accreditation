<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "professional_reg_bodies".
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
class ProfessionalRegBodies extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'professional_reg_bodies';
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
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf'],
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
    
    /**
     * 
     * @throws \Exception
     */
    public function saveRecord()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->upload_file = \yii\web\UploadedFile::getInstance($this, 'upload_file');
            //if file uploaded
            if($this->upload_file){
                $this->upload = 'uploads/professional_memberships/' . $this->user_id ."-" . microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->upload):null;
            }
            $transaction->commit();
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
        return true;
    }
}
