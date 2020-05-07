<?php

namespace app\modules\professional\models;

use Yii;

/**
 * This is the model class for table "references".
 *
 * @property int $id
 * @property int $user_id reference topersonal information
 * @property string $upload
 * @property string $type
 * @property string $date_created
 * @property string $last_updated
 *
 * @property PersonalInformation $user
 */
class Reference extends \yii\db\ActiveRecord
{
    public $upload_file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'references';
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
            [['user_id', 'upload', 'type'], 'required'],
            [['upload_file'], 'required', 'on'=>'new'],
            [['user_id'], 'integer'],
            [['type'], 'string'],
            [['upload_file'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'pdf'], 'maxSize'=> 1024*1024*2],
            [['date_created', 'last_updated'], 'safe'],
            [['upload'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => PersonalInformation::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'upload' => 'Letter',
            'type' => 'Type',
            'date_created' => 'Date Added',
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
     * 
     * @throws \Exception
     */
    public function saveRecord()
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->upload_file = \yii\web\UploadedFile::getInstance($this, 'upload_file');
            //if file uploaded
            if($this->upload_file){
                $this->upload = 'uploads/references/' . $this->user_id ."-" . microtime() .
                    '.' . $this->upload_file->extension;
            }
            if($this->save()){
                ($this->upload_file)? $this->upload_file->saveAs($this->upload):null;
                $transaction->commit();
                return true;
            }
            
        }catch (\Exception $e) {
           $transaction->rollBack();
           throw $e;           
        }
        return false;
    }
    
    /**
     * return the link to a protocol file
     * @author kmagua
     * @return string
     */
    public function fileLink($icon = false)
    {
        if($this->upload != ''){
            $text = ($icon== true)?"<span class='glyphicon glyphicon-download-alt' title='Download - {$this->upload}'></span>" :
                \yii\helpers\Html::encode($this->upload);
            $path = Yii::getAlias('@web') ."/";
            return \yii\helpers\Html::a($text,$path . $this->upload,['data-pjax'=>"0", 'target'=>'_blank']);
        }else{
            return '';
        }
    }
}
