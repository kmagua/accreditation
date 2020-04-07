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
    public $certificate_upload;
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
            [['certificate_upload'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'pdf'], 'maxSize'=> 1024*1024*2],
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
    
    
    /**
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->certificate != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->certificate}'></span>" :
                \yii\helpers\Html::encode($this->certificate);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->certificate,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
    
    /**
     * 
     * @throws \Exception
     */
    public function saveQualification()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->certificate_upload = \yii\web\UploadedFile::getInstance($this, 'certificate_upload');
            //if file uploaded
            if($this->certificate_upload){
                $this->certificate = 'uploads/academic_certs/' . $this->staff_id ."-" . $this->level .'-'. microtime() .
                    '.' . $this->certificate_upload->extension;
            }
            if($this->save()){
                ($this->certificate_upload)? $this->certificate_upload->saveAs($this->certificate):null;
                $transaction->commit();
                return true;
            }
            
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
        return false;
    }
}
