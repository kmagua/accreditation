<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "score_item".
 *
 * @property int $id
 * @property string|null $category
 * @property string|null $specific_item
 * @property string|null $score_item
 * @property float|null $maximum_score
 * @property int $checkboxes
 * @property int $each_checkbox_marks
 * @property string|null $group
 * @property int|null $status
 * @property string $date_created
 * @property string $last_updated
 *
 * @property ApplicationScore[] $applicationScores
 */
class ScoreItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'score_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'status', 'checkboxes', 'each_checkbox_marks'], 'integer'],
            [['maximum_score'],'number'],
            [['date_created', 'last_updated'], 'safe'],
            [['category'], 'string', 'max' => 50],
            [['group'], 'string', 'max' => 20],
            [['specific_item'], 'string', 'max' => 70],
            [['score_item'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'specific_item' => 'Specific Item',
            'score_item' => 'Score Item',
            'maximum_score' => 'Maximum Score',
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
        return $this->hasMany(ApplicationScore::className(), ['score_item_id' => 'id']);
    }
}
