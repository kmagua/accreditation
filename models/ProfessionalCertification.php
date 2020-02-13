<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "professional_certification".
 *
 * @property int $id
 * @property int $staff_id
 * @property string|null $qualification_type
 * @property string|null $other_description
 * @property string|null $certificate
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyStaff $staff
 */
class ProfessionalCertification extends \yii\db\ActiveRecord
{
    public $certificate_upload;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'professional_certification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'certificate'], 'required'],
            [['staff_id'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['qualification_type', 'other_description'], 'string', 'max' => 50],
            [['certificate'], 'string', 'max' => 250],
            [['certificate_upload'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, pdf'],
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
            'qualification_type' => 'Qualification Type',
            'other_description' => 'Other Description',
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
     * Save record and upload file
     * @throws \Exception
     */
    public function saveProfessionalCertification()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->certificate_upload = \yii\web\UploadedFile::getInstance($this, 'certificate_upload');
            //if file uploaded
            if($this->certificate_upload){
                $this->certificate = 'uploads/professional_certs/' . $this->staff_id ."-" . $this->qualification_type .'-'. microtime() .
                    '.' . $this->certificate_upload->extension;
            }
            if($this->save()){
                ($this->certificate_upload)? $this->certificate_upload->saveAs($this->certificate):null;
            }
            $transaction->commit();
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
    }
}
