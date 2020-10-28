<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "password_reset".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string $hash
 * @property string $date_created
 * @property string $last_updated
 *
 * @property User $user
 */
class PasswordReset extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'password_reset';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'hash'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
            [['hash'], 'string', 'max' => 120],
            [['user_id'], 'unique'],
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
            'status' => 'Status',
            'hash' => 'Hash',
            'date_created' => 'Date Created',
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
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function passwordReset($email)
    {
        $sql = "select * from user where email=:kra_pin_number";
        $user = User::findBySql($sql, [':kra_pin_number' => $email])->one();
        if($user){
            $hash = Utility::generateRandomString();
            $insert_sql = "INSERT INTO password_reset (user_id, hash)
                VALUES ({$user->id}, :hash) ON DUPLICATE KEY UPDATE hash = :hash, status = 0";
            $rst = \Yii::$app->db->createCommand($insert_sql, [':hash' => $hash])->execute();
            if($rst){
                PasswordReset::sendEmail($user, $hash, $email);
            }
        }
    }
    
    public static function sendEmail($user, $hash, $username)
    {
        $header = "Password Reset - ICT Authority Accreditation";
        
        $link = \yii\helpers\Url::to(['user/set-new-password', 'id' => $user->id, 'ph'=>$hash], true);
        $text_link = "<a href='".$link. "' target='_blank'>link</a>";
        $message = <<<MSG
                Dear $username,
                <p>We have received your request to reset password and have provided the link below to facilitate the same.
                    Copy and paste the $text_link to your browser to change password.</p>
                <p>$link</p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        Utility::sendMail($user->email, $header, $message);
    }
}
