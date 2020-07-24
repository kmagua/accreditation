<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "icta_committee".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $purpose
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property ApplicationScore[] $applicationScores
 * @property IctaCommitteeMember[] $ictaCommitteeMembers
 */
class IctaCommittee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'icta_committee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_created', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 40],
            [['purpose'], 'string', 'max' => 100],
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
            'purpose' => 'Purpose',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[ApplicationScores]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplicationScores()
    {
        return $this->hasMany(ApplicationScore::className(), ['committee_id' => 'id']);
    }

    /**
     * Gets query for [[IctaCommitteeMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIctaCommitteeMembers()
    {
        return $this->hasMany(IctaCommitteeMember::className(), ['committee_id' => 'id']);
    }
}
