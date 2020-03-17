<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property string|null $status
 * @property string|null $declaration
 * @property string|null $initial_approval_date
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property PersonalInformation $user
 * @property Category $category
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
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
            [['user_id', 'category_id'], 'integer'],
            //[['declaration'], 'string'],
            ['declaration', 'integer', 'max' => 1, 'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            ['declaration', 'required', 'on' => ['create'], 'requiredValue' => 1, 
                'message' => 'You must declare that the information given is correct to the best of your knowledge.'],
            [['initial_approval_date', 'date_created', 'last_updated'], 'safe'],
            [['status'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalInformation::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'category_id' => 'Accreditation Category',
            'status' => 'Status',
            'declaration' => 'I declare that the information provided is correct to the best of my knowledge.',
            'initial_approval_date' => 'Initial Approval Date',
            'date_created' => 'Date Submitted',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(PersonalInformation::className(), ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
    
    /**
     * 
     */
    public function notifyUserOfApproval()
    {
        $header = "Your accreditation request has been approved by ICT Authority";
        $type = $this->category->name;
        $link = \yii\helpers\Url::to(['/professional/application/download-cert', 'id' => $this->id], true);
        
        $message = <<<MSG
            Dear {$this->user->first_name} {$this->user->last_name},
            <p>Kindly note that your Accreditation request for $type has been approved by ICT Authority.
                You can login in to the Authority's accreditation site using the link below to download your certificate.</p>
            <p>$link</p>
            <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        \app\models\Utility::sendMail($this->user->usr->email, $header, $message);
    }
}
