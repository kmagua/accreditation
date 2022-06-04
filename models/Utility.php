<?php
namespace app\models;

use Yii;

/**
 * Description of Utility
 *
 * @author kmagua
 */
class Utility {
    public static $message;
    /**
     * Pass required parameters to send Email
     * @author Kenneth Magua <kenmagua@gmail.com>
     * @param string $to recipients email name
     * @param type $subject Email's Subject
     * @param type $msg email Body
     */
    public static function sendMail($to, $subject, $msg, $cc='', $attach = '', $from = 'noreply@govmail.ke')
    {
        $message = Yii::$app->mailer->compose();
        $message->setFrom($from)
        ->setTo($to)
        ->setSubject($subject)
        ->setHtmlBody(
            $msg
        );
        if($cc){
            $message->setCc($cc);
        }
        if($attach){
            $message->attach(\Yii::$app->basePath ."/web/$attach");
        }
        $message->send();
    }
    
    /**
     * Return csv data as array
     * @author Kenneth Magua <kenmagua@gmail.com>
     * @param type $file The csv file
     * @return array
     */
    public static function csv_to_array($file)
    {
        if(!file_exists($file) || !is_readable($file)){
            return false;
        }	    
        return array_map('str_getcsv', file($file));
    }
    
    public static function generateRandomString($length = 100) {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ$@!', ceil($length/strlen($x)) )),1,$length);
    }
    
    public static function get_percentages_from_array(&$data_item, $key, $total_item_count)
    {
        $data_item = round($data_item/$total_item_count * 100, 1);
    }
    
    public static function generateMpesaAccountString($length = 6) {
        return substr(str_shuffle(str_repeat($x='ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
}
