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
            [['date_created', 'last_updated'], 'safe'],
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
}
