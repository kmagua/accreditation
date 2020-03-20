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
            'committee_member_ids' => 'Committee Member(s)',
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
    
    public function saveApplicationCommitteeMembers($committee_id)
    {
        foreach($this->committee_member_ids as $member_id){
            $rec = ApplicationCommitteMember::find()->where(['committee_member_id'=>$member_id, 'application_id' => $this->application_id])->one();
            if(!$rec){                
                \Yii::$app->db->createCommand()->insert('application_committe_member',[
                    'committee_member_id'=> $member_id, 'application_id'=> $this->application_id
                ])->execute();
            }
        }
        $cmi = implode(",", $this->committee_member_ids);
        $sql = "DELETE acm FROM application_committe_member acm JOIN icta_committee_member icm ON icm.id=acm.committee_member_id
            WHERE (application_id = {$this->application_id} AND icm.committee_id = $committee_id) AND committee_member_id NOT IN ($cmi)";
        \Yii::$app->db->createCommand($sql)->execute();
        return true;
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
     * 
     */
    public function loadApplicationCommitteeMembers($level)
    {
        $rec = ApplicationCommitteMember::find()->from('application_committe_member acm')->select('committee_member_id')
            ->join("join", 'icta_committee_member icm', "acm.committee_member_id = icm.id")
            ->where(['application_id'=>$this->application_id, 'icm.committee_id' => $level])->all();
        if($rec){
            $this->committee_member_ids = array_column($rec, 'committee_member_id');
        }
    }
}
