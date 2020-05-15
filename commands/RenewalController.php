<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class RenewalController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        //allow this to be run several days
        $sql = "SELECT id, parent_id, DATEDIFF(DATE_ADD(`initial_approval_date`, INTERVAL 1 YEAR) ,NOW()) date_diff  FROM `accreditcomp`.`application`
            WHERE STATUS = 'ApplicationWorkflow/completed'
            HAVING date_diff IN(40, 39, 38, 37, 36, 35)";
        $applications = \app\models\Application::findBySql($sql)->all();
        foreach($applications as $application){
            $app_id = $application->id;
            if($application->parent_id != ''){
                $app_id = $application->parent_id;
            }
            $sql2 = "SELECT id, parent_id,date_created,  DATEDIFF(NOW(), `date_created` ) date_diff  FROM `accreditcomp`.`application`
                WHERE parent_id = {$app_id}
                HAVING date_diff <20;";
            //echo "Hapa\n"; 
            $latest_app = \app\models\Application::findBySql($sql2)->one();
            if(!$latest_app){
                //echo "Hapa ndani\n"; 
                $original_app = \app\models\Application::findOne($app_id);
                if($original_app->status == 'ApplicationWorkflow/completed'){
                    $original_app->status = 'ApplicationWorkflow/renewal';
                    $original_app->save(false);
                    $this->sendEmail($original_app);
                }
            }
        }
        return ExitCode::OK;
    }
    
    /**
     * 
     * @param type $application app\models\Application
     */
    public function sendEmail($application)
    {        
        $header = $application->accreditationType->name . " - RENEWAL REQUEST NOTIFICATION";
        $type = $application->accreditationType->name;
        $link = \yii\helpers\Url::to(['/company-profile/view', 'id' => $application->company_id], true);
        
        $message = <<<MSG
                Dear {$application->user->full_name},
                <p>Kindly note that your Accreditation by ICT Authority for $type is approaching the annual renewal period.
                    You will need to start the renewal process using the link below to ensure validity of your certificate.
                    The link to renew is on the 'status' column in the Applications tab after opening the link below.</p>
                        
                <p>$link</p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        \app\models\Utility::sendMail($application->company->company_email, $header, $message, $application->user->email);
    }
}
