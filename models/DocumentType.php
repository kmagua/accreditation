<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string|null $name
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property CompanyDocument[] $companyDocuments
 */
class DocumentType extends \yii\db\ActiveRecord
{
    public $applicable_app_types;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [            
            [['date_created', 'last_updated', 'applicable_app_types'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['status'], 'integer'],
            [['applicable_app_types'], 'required'],
            //[['documents_upload'], 'string', 'max' => 255],
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
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
            'applicable_app_types' => 'Uploadable on which type(s) of Application?'
        ];
    }

    /**
     * Gets query for [[CompanyDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyDocuments()
    {
        return $this->hasMany(CompanyDocument::className(), ['document_type_id' => 'id']);
    }
    
    /**
     * 
     */
    public function saveCompanyTypeDocument()
    {
        foreach ($this->applicable_app_types as $applicable_app_type){
            $rec = CompanyTypeDocument::find()->where(
                    ['company_type_id' => $applicable_app_type, 'document_type_id' => $this->id])->one();
            if(!$rec){
                \Yii::$app->db->createCommand()->insert('company_type_document',[
                    'company_type_id'=> $applicable_app_type, 'document_type_id'=> $this->id
                ])->execute();
            }
        }
        /*$staff = implode(",", $this->applicable_app_types); 
        ApplicationStaff::deleteAll("staff_id not in ($staff) AND application_id={$this->id}");*/
    }
    
    /**
     * Sets $applicable_app_types to values of selected experiences
     * 
     */
    public function loadCompanyTypeDocument()
    {
        $rec = CompanyTypeDocument::find()->select('company_type_id')->where(['document_type_id'=>$this->id])->all();
        if($rec){
            $this->applicable_app_types = array_column($rec, 'company_type_id');
        }
    }
}
