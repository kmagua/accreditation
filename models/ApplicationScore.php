<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application_score".
 *
 * @property int $id
 * @property int|null $application_id
 * @property int|null $score_item_id
 * @property float|null $score
 * @property int|null $user_id
 * @property int|null $committee_id
 * @property string|null $comment
 * @property string $date_created
 * @property string $last_updated
 *
 * @property Application $application
 * @property ScoreItem $scoreItem
 * @property IctaCommittee $committee
 */
class ApplicationScore extends \yii\db\ActiveRecord
{
    public $committee_score; // application classification score
    public $classification; // application classification 'classification'
    public $maximum_score; 
    public $status; // application classification status
    public $rejection_comment;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application_score';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['application_id', 'score_item_id', 'user_id', 'committee_id', 'committee_score', 'status'], 'integer'],
            [['score'], 'number'],
            [['date_created', 'last_updated','score_item', 'specific_item', 'category', 'maximum_score'], 'safe'],
            [['classification'],'string', 'max'=>30],
            [['rejection_comment', 'comment'],'string', 'max'=>150],
            [['application_id'], 'exist', 'skipOnError' => true, 'targetClass' => Application::className(), 'targetAttribute' => ['application_id' => 'id']],
            [['score_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => ScoreItem::className(), 'targetAttribute' => ['score_item_id' => 'id']],
            [['committee_id'], 'exist', 'skipOnError' => true, 'targetClass' => IctaCommittee::className(), 'targetAttribute' => ['committee_id' => 'id']],
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
            'score_item_id' => 'Score Item ID',
            'score' => 'Score',
            'user_id' => 'User ID',
            'committee_id' => 'Committee ID',
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
     * Gets query for [[ScoreItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getScoreItem()
    {
        return $this->hasOne(ScoreItem::className(), ['id' => 'score_item_id']);
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
     * 
     * @return type
     */
    public function saveApplicationScore()
    {
        if($this->score == 1){
            if($this->scoreItem->checkboxes == 1){
                $this->score = $this->maximum_score;
            }
        }
        if(!$this->save()){
            echo '<pre> hapa', print_r($this->errors, true); exit;
        }
    }
}
