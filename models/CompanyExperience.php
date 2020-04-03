<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company_experience".
 *
 * @property int $id
 * @property int|null $company_id
 * @property string|null $organization_type
 * @property string|null $project_name
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $status
 * @property float|null $project_cost
 * @property string|null $attachment
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property ApplicationCompanyExperience[] $applicationCompanyExperiences
 * @property CompanyProfile $company
 */
class CompanyExperience extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company_experience';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id'], 'integer'],
            [['company_id', 'project_cost', 'project_name', 'organization_type'], 'required'],
            [['organization_type', 'status'], 'string'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, pdf, doc, jpg'],
            [['start_date', 'end_date', 'date_created', 'last_updated'], 'safe'],
            [['project_cost'], 'number'],
            [['upload_file'],'required', 'on' => 'create'],
            [['project_name', 'attachment'], 'string', 'max' => 250],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyProfile::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'organization_type' => 'Organization Type',
            'project_name' => 'Project Name',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'project_cost' => 'Project Cost (KES)',
            'attachment' => 'Attachment',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
            'upload_file' => 'Proof Documents for the Project [LPO, Recommendation letter] (Combine in one file)',
        ];
    }

    /**
     * Gets query for [[ApplicationCompanyExperiences]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCompanyExperiences()
    {
        return $this->hasMany(ApplicationCompanyExperience::className(), ['experience_id' => 'id']);
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(CompanyProfile::className(), ['id' => 'company_id']);
    }
    
     /**
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->attachment != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->attachment}'></span>" :
                \yii\helpers\Html::encode($this->attachment);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->attachment,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
    
    /**
     * 
     * @throws \Exception
     */
    public function saveCompanyExperience()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->upload_file = \yii\web\UploadedFile::getInstance($this, 'upload_file');
            //if file uploaded
            if($this->upload_file){
                $this->attachment = 'uploads/company_exp_docs/' . $this->company_id .'-'. microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->attachment):null;
            }
            $transaction->commit();
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;
        }
        return true;
    }
    
    /**
     * overridden function
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        if($this->start_date != ''){
            $this->start_date = date('Y-m-d', strtotime($this->start_date));
        }
        if($this->end_date != ''){
            $this->end_date = date('Y-m-d', strtotime($this->end_date));
        }
        return true;
    }
}
