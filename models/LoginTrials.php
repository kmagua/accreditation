<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_login_trial".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $number_of_trials
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property User $user
 */
class LoginTrials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_login_trial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'number_of_trials'], 'integer'],
            [['date_created', 'last_updated'], 'safe'],
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
            'number_of_trials' => 'Number Of Trials',
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
    
    /**
     * 
     * @param type $username
     */
    public static function checkAccountOnUnsuccessfulLogin($username)
    {
        $user = User::findOne(['email' => $username]);
        if($user){
            $sql = "INSERT INTO user_login_trial (user_id, number_of_trials) VALUES (:user_id, :number_of_trials) ON DUPLICATE KEY UPDATE number_of_trials = number_of_trials+1";
            Yii::$app->db->createCommand($sql, [':user_id' => $user->id, ':number_of_trials' => 1])->execute();
            $sql2 = "SELECT number_of_trials FROM user_login_trial where user_id = {$user->id}";
            $number_of_trials = Yii::$app->db->createCommand($sql2)->queryScalar();
            if($number_of_trials >= 3){
                $user->status = 0;
                $user->save(false);
                \Yii::$app->session->setFlash('logins_exceeded','You have exceeded the allowable trials for logging in and your account has been disabled.'
                        . ' Use reset password feature to set up a new password.');
                return;
            }
            $remaining_trials = 3 - $number_of_trials;
            \Yii::$app->session->setFlash('logins_exceeded',"You have $remaining_trials remaining trials before your account is locked.");
            return;
        }        
    }
    
    /**
     * 
     * @param type $uid
     */
    public static function setStatusToZero($uid)
    {
        $sql = "INSERT INTO user_login_trial (user_id, number_of_trials) VALUES (:user_id, 0) ON DUPLICATE KEY UPDATE number_of_trials = 0";
        Yii::$app->db->createCommand($sql, [':user_id' => $uid])->execute();
    }
}
