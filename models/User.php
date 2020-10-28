<?php

namespace app\models;

use Yii;
use kartik\password\StrengthValidator;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $kra_pin_number
 * @property string $email
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $password
 * @property string|null $role
 * @property int $status
 * @property string $recent_passwords
 * @property string $last_login_date
 * @property string $last_password_change_date
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
    public $captcha;
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
            [['email', 'first_name'], 'required'],
            [['role'], 'string'],
            [['status'], 'number'],
            [['captcha', 'password'], 'required', 'on'=>'register'],
            [['captcha'], 'required', 'on'=>['password_reset']],  
            ['captcha', 'captcha','captchaAction'=>'/site/captcha'], // add this code to your rules.
            [['password'], 'required', 'on'=>['register', 'password_update', 'register_internal']],
            [['email', 'kra_pin_number'], 'unique'],
            [['date_created', 'last_updated', 'last_login_date', 'last_password_change_date'], 'safe'],
            [['email'], 'string', 'max' => 60],
            [['password'], StrengthValidator::className(), 'min' => 8, 'upper' => 1, 'lower' => 1, 'digit' => 1,
                'special' => 1, 'userAttribute'=>'email'],
            ['email', 'email'],
            ['first_name', 'match', 'pattern' => '/^[0-9\sa-z-_()&]+$/i', 'message'=>'{attribute} can only have alphanumerics, underscore or a hyphen.'],
            [['last_name'], 'string', 'max' => 20],
            [['password', 'password_repeat', 'first_name'], 'string', 'max' => 100],
            [['password_repeat'], 'validatePasswordRepeat', 'on'=>['register', 'password_update', 'register_internal']],
            ['recent_passwords', 'string', 'max' => 500]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kra_pin_number' => 'Company KRA PIN Number',
            'email' => 'Company Email',
            'first_name' => 'Account/Company Name',
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
        $rec = static::findOne(['email' => $username]);        
        return $rec;
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
    
    /**
     * 
     */
    /*public function beforeValidate() 
    {
        parent::beforeValidate();
        if($this->scenario == 'register_internal'){
            $this->generateKRAPIN();
        }
        return true;
    }*/
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        //only on new record or password change
        if($insert || $this->scenario == 'password_update'){
            $password_before_hash = $this->password;
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            if(!$this->customPasswordChecks($insert, $password_before_hash)){
                $this->addError('password', 'Password must not match any of the last 3 passwords you have used.');
                return false;
            }
            $this->last_password_change_date = date('Y-m-d 00:00:00');
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
        
        $this->addToInternalCommittee();
        
        if($insert && $this->scenario == 'register'){
            $this->sendEmailConfirmationEmail();
        }
        return true;
    }

    /**
     * 
     * @return boolean
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->username = $this->email;
        $this->setUserId();
        $this->setGroup();
        $this->full_name = trim($this->first_name);
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
        $hash = Utility::generateRandomString();
        $insert_sql = "INSERT INTO password_reset (user_id, hash)
            VALUES ({$this->id}, :hash) ON DUPLICATE KEY UPDATE hash = :hash";
        $reset_rec = \Yii::$app->db->createCommand($insert_sql, [':hash' => $hash])->execute();
        //$hash = password_hash($this->id . '' . $this->status . strtolower($this->email), PASSWORD_DEFAULT);
        if($reset_rec){
            $link = \yii\helpers\Url::to(['user/confirm-user-account', 'id' => $this->id, 'h'=>$hash], true);

            $text_link = "<a href='".$link. "' target='_blank'>click this link</a>";
            $msg = <<<MSG
                Dear {$this->first_name} {$this->last_name}, <p>
                Your account has been registered and needs your activation before you can login. 
                    Please $text_link or copy paste the link below to your browser to activate your account. </p>
                $link

            Regards,
            ICT Authority Accreditation Team.
MSG;
            Utility::sendMail($this->email, "Account Confirmation - ICT Authority Accreditation", $msg);
        }
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
    
    /**
     * 
     * @param type $group
     * @return boolean
     */
    public function inGroup($group, $include_adm = true)
    {
        $grp = strtolower($group);
        $usr_grp = strtolower(Yii::$app->user->identity->group);
        if($usr_grp == 'admin' && $include_adm == true){
            return true;
        }
        return $usr_grp == $grp;
    }
    
    /**
     * 
     * @return type
     */
    public function isInternal($reviewers = false)
    {
        if($reviewers){
            return in_array(strtolower(Yii::$app->user->identity->group),['secretariat','committee member']);
        } else {
            return in_array(strtolower(Yii::$app->user->identity->group),['admin','secretariat','committee member', 'su']);
        }
    }
    
     /**
     * 
     * @return type
     */
    public function isAdmin($include_SU = true)
    {
        $grps = ($include_SU)?['admin', 'su']:['admin'];
        return in_array(strtolower(Yii::$app->user->identity->group),$grps);        
    }
    
    /**
     * 
     * @return type
     */
    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * 
     */
    public function generateKRAPIN()
    {
        $new_pin = Utility::generateRandomString(11);
        $user = User::findOne(['kra_pin_number'=> $new_pin]);
        if($user){
            $this->generateKRAPIN();
        }
        $this->kra_pin_number = $new_pin;
    }
    
    /**
     * 
     * @return type
     */
    public function addToInternalCommittee()
    {
        $role = strtolower($this->role);        
        if(in_array($role, ['secretariat','committee member'])){
            $level = ($role == 'secretariat') ? 1:2;
            $rec = IctaCommitteeMember::find()->where(['committee_id'=> $level, 'user_id' =>$this->id])->one();
            if(!$rec){
                \Yii::$app->db->createCommand()->insert('icta_committee_member',[
                    'committee_id'=> $level, 'user_id'=> $this->id, 'status' => 1
                ])->execute();
            }else{
                $rec->status = 1;
                $rec->save(false);
            }
            return;
        }
        //echo $this->id, ' - ', $level; exit;
        IctaCommitteeMember::updateAll(['status' => 0],"user_id  = {$this->id}");
    }
    
    /**
     * 
     * @param type $uid
     */
    public static function updateLastLoginTime($uid)
    {
        $sql = "UPDATE user set last_login_date =:time_now WHERE id =:uid ";
        Yii::$app->db->createCommand($sql, [':uid' => $uid, ':time_now' => date('Y-m-d H:i:s')])->execute();
    }
    
    /**
     * 
     * @param type $insert
     * @return boolean
     */
    public function customPasswordChecks($insert, $password_before_hash)
    {
        if($insert){
            $this->recent_passwords = $this->password;           
        }else{
            return $this->passwordDoesNotMatchThreerecent($password_before_hash);
        }
        return true;
    }
    
    /**
     * 
     * @param type $password_before_hash
     * @return boolean
     */
    public function passwordDoesNotMatchThreerecent($password_before_hash)
    {
        $passwords = explode(':::::', $this->recent_passwords);
        
        foreach($passwords as $old_password){
            if(password_verify($password_before_hash, $old_password)){
                return false;
            }
        }
        if(count($passwords) == 3){
            $this->recent_passwords = $passwords[1] . ':::::' . $passwords[2] . ':::::' . $this->password;
        }else{
            $this->recent_passwords = $this->recent_passwords . ':::::' . $this->password;
        }
        return true;
    }
}
