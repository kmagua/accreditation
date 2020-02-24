<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "icta_committee_member".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $committee_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property ApplicationCommitteMember[] $applicationCommitteMembers
 * @property IctaCommittee $committee
 * @property User $user
 */
class IctaCommitteeMember extends \yii\db\ActiveRecord
{
    public $committee_members;
    public $full_name; // for user
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'icta_committee_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'committee_id'], 'integer'],
            [['date_created', 'last_updated', 'committee_members'], 'safe'],
            [['committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => IctaCommittee::className(), 'targetAttribute' => ['committee_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'committee_id' => 'Committee ID',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[ApplicationCommitteMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationCommitteMembers()
    {
        return $this->hasMany(ApplicationCommitteMember::className(), ['committee_member_id' => 'id']);
    }

    /**
     * Gets query for [[Committee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCommittee()
    {
        return $this->hasOne(IctaCommittee::className(), ['id' => 'committee_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * 
     */
    public function saveMembers()
    {
        foreach($this->committee_members as $member){
            $rec = IctaCommitteeMember::find()->where(['committee_id'=>$this->committee_id, 'user_id' =>$member])->one();
            if(!$rec){                
                \Yii::$app->db->createCommand()->insert('accreditation.icta_committee_member',[
                    'committee_id'=> $this->committee_id, 'user_id'=>  $member
                ])->execute();
            }
        }
        $cm = implode(",", $this->committee_members); 
        IctaCommitteeMember::deleteAll("user_id not in ($cm) AND committee_id={$this->committee_id}");   
        return true;
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
     * 
     */
    public function loadCommitteeMembers()
    {
        $rec = IctaCommitteeMember::find()->select('user_id')->where(['committee_id'=>$this->committee_id])->all();
        if($rec){
            $this->committee_members = array_column($rec, 'user_id');
        }
    }
    
    public function getIctaCommitteeMembers($committee_id)
    {
        IctaCommitteeMember::find()->where(['committee_id' => $committee_id])->all();
    }
    
    /**
     * 
     * @param type $committee_id
     * @return type
     */
    public static function findCommitteeMembersArray($committee_id)
    {
        $exp = new \yii\db\Expression("icm.id, CONCAT_WS(' ', first_name, last_name) full_name");
        $sql = "SELECT $exp "
                . "FROM `icta_committee_member` icm JOIN `user` u ON u.id=icm.user_id WHERE `committee_id`=$committee_id";
        return IctaCommitteeMember::findBySql($sql)->all();
    }
}
