<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $password
 * @property string|null $role
 * @property string $date_created
 * @property string|null $last_updated
 *
 * @property Application[] $applications
 * @property CompanyProfile[] $companyProfiles
 * @property IctaCommitteeMember[] $ictaCommitteeMembers
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $username;
    public $authKey;
    public $accessToken;
    public $password_repeat;
    public $group;
    public $user_id;
    public $full_name;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role'], 'string'],
            [['email'], 'unique'],
            [['date_created', 'last_updated'], 'safe'],
            [['email'], 'string', 'max' => 30],
            [['first_name', 'last_name'], 'string', 'max' => 20],
            [['password', 'password_repeat'], 'string', 'max' => 100],
            [['password_repeat'], 'validatePasswordRepeat', 'on'=>'new_account'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password' => 'Password',            
            'role' => 'Role',
            'date_created' => 'Date Created',
            'last_updated' => 'Last Updated',
            'password_repeat' => 'Repeat Password'
        ];
    }
    
    /**
     * Validate password repeat
     */
    public function validatePasswordRepeat($attribute, $params)
    {
        if($this->password != $this->password_repeat){
            $this->addError($attribute, "Passwords do not match!");
        }
    }
	
    public function getUserName()
    {
            return $this->email;
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[CompanyProfiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanyProfiles()
    {
        return $this->hasMany(CompanyProfile::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[IctaCommitteeMembers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIctaCommitteeMembers()
    {
        return $this->hasMany(IctaCommitteeMember::className(), ['user_id' => 'id']);
    }
	
	/** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
        /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
/* modified */
    public static function findIdentityByAccessToken($token, $type = null)
    {
          return static::findOne(['access_token' => $token]);
    }
 
/* removed
    public static function findIdentityByAccessToken($token)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
*/
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username, 'status'=>1]);
    }

    /**
     * Finds user by password reset token
     *
     * @param  string      $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        if ($timestamp + $expire < time()) {
            // token expired
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token
        ]);
    }
    
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        //only on new record
        if($insert){
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);        
        }    
        return true;
    }
    
    /**
     * 
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert){
            $this->sendEmailConfirmationEmail();
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->username = $this->email;
        $this->setUserId();
        $this->setGroup();
        $this->full_name = $this->first_name . ' ' . $this->last_name;
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return "test" . $this->getId() . "key";
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    /*public function setPassword($password)
    {
        $this->password_hash = Security::generatePasswordHash($password);
    }*/

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Security::generateRandomKey();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Security::generateRandomKey() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    public function sendEmailConfirmationEmail()
    {
        $this->refresh(); // pull updates from table
        $hash = password_hash($this->id . '' . $this->status . strtolower($this->email), PASSWORD_DEFAULT);
        
        $link = urldecode(\yii\helpers\Url::to(['user/confirm-user-account', 'id' => $this->id, 'h'=>$hash], true));
        
        $text_link = "<a href='".$link. "' target='_blank'>click this link</a>";
        $msg = <<<MSG
            Dear $this->first_name ." " . $$this->last_name, <p>
            Your account has been registered but must be confimed before you can login. 
                Please $text_link or copy paste the link below to your broser to confirm you account. </p>
            $link
                
        Regards,
        ICT Authority Accreditation Team.
MSG;
        Utility::sendMail($this->email, "Account Confirmation", $msg);
    }
    
    /**
     * validate if the hash on link matches details in database
     * @param type $hash
     * @return type
     */
    public function validateAccount($hash)
    {
        return password_verify($this->id . '' . $this->status . strtolower($this->email), $hash);
    }
    
    public function setUserId()
    {
        $this->user_id = $this->id;
    }
    
    public function setGroup()
    {
        $this->group = $this->role;
    }
    
    public function inGroup($group)
    {
        $grp = strtolower($group);
        if($grp == 'admin'){
            return true;
        }
        return Yii::$app->user->identity->group == $grp;
    }
    
    public function isInternal()
    {
        return in_array(strtolower(Yii::$app->user->identity->group),['admin','secretariat','committe member']);        
    }
}
