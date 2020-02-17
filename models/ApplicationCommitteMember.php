<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_committe_member".
 *
 * @property int $id
 * @property int $application_id
 * @property int $committee_member_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property IctaCommitteeMember $committeeMember
 * @property Application $application
 */
class ApplicationCommitteMember extends \yii\db\ActiveRecord
{
    public $committee_member_ids;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_committe_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'committee_member_id'], 'required'],
            [['application_id', 'committee_member_id'], 'integer'],
            [['date_created', 'last_updated', 'committee_member_ids'], 'safe'],
            [['committee_member_id'], 'exist', 'skipOnError' => true, 'targetClass' => IctaCommitteeMember::className(), 'targetAttribute' => ['committee_member_id' => 'id']],
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
            'committee_member_id' => 'Committee Member ID',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[CommitteeMember]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommitteeMember()
    {
        return $this->hasOne(IctaCommitteeMember::className(), ['id' => 'committee_member_id']);
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
    
    public function saveApplicationCommitteeMembers()
    {
        foreach($this->committee_member_ids as $member_id){
            $rec = ApplicationCommitteMember::find()->where(['committee_member_id'=>$member_id, 'application_id' => $this->application_id])->one();
            if(!$rec){                
                \Yii::$app->db->createCommand()->insert('accreditation.application_committe_member',[
                    'committee_member_id'=> $member_id, 'application_id'=> $this->application_id
                ])->execute();
            }
        }
        $cmi = implode(",", $this->committee_member_ids); 
        ApplicationCommitteMember::deleteAll("committee_member_id not in ($cmi) AND application_id={$this->application_id}");   
        return true;
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
     * 
     */
    public function loadApplicationCommitteeMembers()
    {
        $rec = ApplicationCommitteMember::find()->select('committee_member_id')->where(['application_id'=>$this->application_id])->all();
        if($rec){
            $this->committee_member_ids = array_column($rec, 'committee_member_id');
        }
    }
}
