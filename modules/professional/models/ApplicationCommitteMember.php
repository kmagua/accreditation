<?php

namespace app\modules\professional\models;

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
            [['application_id', 'committee_member_id'], 'required'],
            [['application_id', 'committee_member_id'], 'integer'],
            [['date_created', 'last_updated', 'committee_member_ids'], 'safe'],
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
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['id' => 'application_id']);
    }
    
    /**
     * Sets app_company_experience to values of selected experiences
     * 
     */
    public function loadApplicationCommitteeMembers($level)
    {
        $rec = ApplicationCommitteMember::find()->from('application_committe_member acm')->select('committee_member_id')
            ->join("join", 'supplier_accreditation.icta_committee_member icm', "acm.committee_member_id = icm.id")
            ->where(['application_id'=>$this->application_id, 'icm.committee_id' => $level])->all();
        if($rec){
            $this->committee_member_ids = array_column($rec, 'committee_member_id');
        }
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
        $sql = "DELETE acm FROM application_committe_member acm JOIN supplier_accreditation.icta_committee_member icm ON icm.id=acm.committee_member_id
            WHERE (application_id = {$this->application_id} AND icm.committee_id = $committee_id) AND committee_member_id NOT IN ($cmi)";
        \Yii::$app->db->createCommand($sql)->execute();
        return true;
    }
}
