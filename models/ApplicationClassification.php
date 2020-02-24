<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_classification".
 *
 * @property int $id
 * @property int $application_id
 * @property int $icta_committee_id
 * @property float|null $score
 * @property string|null $classification
 * @property int|null $status
 * @property string|null $rejection_comment
 * @property int|null $user_id
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property IctaCommittee $ictaCommittee
 * @property User $user
 * @property Application $application
 */
class ApplicationClassification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_classification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'icta_committee_id'], 'required'],
            [['application_id', 'icta_committee_id', 'user_id', 'status'], 'integer'],
            [['score'], 'number'],
            [['date_created', 'last_updated'], 'safe'],
            [['classification'], 'string', 'max' => 30],
            [['rejection_comment'], 'string', 'max' => 150],
            [['application_id', 'icta_committee_id'], 'unique', 'targetAttribute' => ['application_id', 'icta_committee_id']],
            [['icta_committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => IctaCommittee::className(), 'targetAttribute' => ['icta_committee_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'icta_committee_id' => 'Icta Committee ID',
            'score' => 'Score',
            'classification' => 'Classification',
            'user_id' => 'User ID',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[IctaCommittee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIctaCommittee()
    {
        return $this->hasOne(IctaCommittee::className(), ['id' => 'icta_committee_id']);
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
     * Gets query for [[Application]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplication()
    {
        return $this->hasOne(Application::className(), ['id' => 'application_id']);
    }
    
    public static function saveClassification($app_id, $score, $category, $icta_comm, $status, $rejection_comment)
    {
        $uid = \Yii::$app->user->identity->user_id;
        $insert_sql = "INSERT INTO application_classification (application_id, icta_committee_id, user_id, score, classification, status, rejection_comment)
            VALUES ($app_id, $icta_comm, $uid, $score, '$category', $status, :rejection_comment)
            ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP, score = $score, classification = '$category'";
                
        return \Yii::$app->db->createCommand($insert_sql, [':rejection_comment' => $rejection_comment])->execute();
    }
}
