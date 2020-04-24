<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "education".
 *
 * @property int $id
 * @property int|null $level_id
 * @property string|null $course
 * @property string|null $institution
 * @property string|null $completion_date
 * @property string|null $upload
 * @property string|null $date_created
 * @property string|null $date_modified
 * @property int|null $user_id
 *
 * @property EducationLevel $level
 * @property PersonalInformation $user
 */
class Education extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'education';
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
            [['level_id', 'user_id'], 'integer'],
            [['level_id', 'user_id', 'institution', 'course', 'completion_date'], 'required'],
            [['completion_date', 'date_created', 'date_modified'], 'safe'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'pdf'], 'maxSize'=> 1024*1024*2],
            [['course', 'institution'], 'string', 'max' => 100],
            [['upload'], 'string', 'max' => 255],
            [['level_id'], 'exist', 'skipOnError' => true, 'targetClass' => EducationLevel::className(), 'targetAttribute' => ['level_id' => 'id']],
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
            'level_id' => 'Level ID',
            'course' => 'Course',
            'institution' => 'Institution',
            'completion_date' => 'Completion Date',
            'upload' => 'Certificate',
            'date_created' => 'Date Created',
            'date_modified' => 'Date Modified',
            'user_id' => 'User ID',
        ];
    }

    /**
     * Gets query for [[Level]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLevel()
    {
        return $this->hasOne(EducationLevel::className(), ['id' => 'level_id']);
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
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if($this->completion_date){
            $this->completion_date = date("Y-m-d", strtotime($this->completion_date));
        }
        return true;
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
                $this->upload = 'uploads/prof_academic_certs/' . $this->user_id ."-" . microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->upload):null;
                $transaction->commit();
                return true;
            }
            
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;           
        }
        return false;
    }
    
    /**
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->upload != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->upload}'></span>" :
                \yii\helpers\Html::encode($this->upload);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->upload,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
}
