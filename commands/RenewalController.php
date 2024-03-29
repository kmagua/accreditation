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
        $this->companyAccreditationRenewals();
        //$this->professionalAccreditationRenewals();
        return ExitCode::OK;
    }
    
    public function companyAccreditationRenewals()
    {
        $sql = "SELECT *, DATEDIFF(DATE_ADD(`initial_approval_date`, INTERVAL 1 YEAR) ,NOW()) date_diff  FROM `supplier_accreditation`.`application`
            WHERE STATUS = 'ApplicationWorkflow/completed'
            HAVING date_diff < 20";
        $applications = \app\models\Application::findBySql($sql)->all();
        foreach($applications as $application){
            $app_id = $application->id;
            //Removed since version 2 to allow a renewal to atache to a previous renewal instead of always the parent
            /*if($application->parent_id != ''){
                $app_id = $application->parent_id;
            }*/
            $sql2 = "SELECT id, parent_id  FROM `supplier_accreditation`.`application` WHERE parent_id = {$app_id}";
            //echo "Hapa\n"; 
            $latest_app = \app\models\Application::findBySql($sql2)->one();
            if(!$latest_app){
                //echo "Hapa ndani\n";
                //$original_app = \app\models\Application::findOne($app_id);
                if($application->status == 'ApplicationWorkflow/completed'){
                    //echo "Hapa ndani tena\n";
                    $application->status = 'ApplicationWorkflow/renewal';
                    $application->save(false);
                    $this->sendEmail($application);
                }
            }
            //echo "Hapa nje";
        }
    }
    
    /**
     * 
     */
    public function professionalAccreditationRenewals()
    {
        $sql = "SELECT id, parent_id, DATEDIFF(DATE_ADD(`initial_approval_date`, INTERVAL 3 YEAR) ,NOW()) date_diff 
            FROM `accreditprof`.`application`
            WHERE STATUS = 4
            HAVING date_diff IN(40, 39, 38, 37, 36, 35)";
        $applications = \app\modules\professional\models\Application::findBySql($sql)->all();
        foreach($applications as $application){
            $app_id = $application->id;
            if($application->parent_id != ''){
                $app_id = $application->parent_id;
            }
            $sql2 = "SELECT id, parent_id,date_created,  DATEDIFF(NOW(), `date_created` ) date_diff
                FROM `accreditprof`.`application`
                WHERE parent_id = {$app_id}
                HAVING date_diff <20;";
            //echo "Hapa\n"; 
            $latest_app = \app\modules\professional\models\Application::findBySql($sql2)->one();
            if(!$latest_app){
                //echo "Hapa ndani\n"; 
                $original_app = \app\modules\professional\models\Application::findOne($app_id);
                if( $original_app->status == 4 ){
                    $original_app->status = 6;
                    $original_app->save(false);
                    $this->sendProfessionalEmail($original_app);
                }
            }
        }
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
                Dear Applicant,
                <p>Kindly note that your Accreditation by ICT Authority for $type is approaching the annual renewal period.
                    You will need to start the renewal process using the link below to ensure validity of your certificate.</p>                        
                    Make sure to review and update the following:-
                        <ul>
                            <li>Audited accounts (the last full financial year)</li>
                            <li>Staff information</li>
                            <li>Company statutory documents</li>
                            <li>Company experience (for the last 5 years)</li>
                        </ul>
                    <p>The renew link is on the 'status' column in the Applications tab on the link below.</p>
                        
                <p>$link</p>
                <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        \app\models\Utility::sendMail($application->company->company_email, $header, $message, $application->user->email);
    }
    
    /**
     * @var $application app\modules\professional\models\Application
     * @param type $application app\modules\professional\models\Application
     */
    public function sendProfessionalEmail($application)
    {        
        $type = $application->category->name;
        $header = 'ICT Authority: ' . $type . " - RENEWAL REQUEST NOTIFICATION";
        
        $link = \yii\helpers\Url::to(['/professional/personal-information/view', 'id' => $application->user_id], true);
        $name = $application->user->getNames();
        $message = <<<MSG
            Dear {$name},
            <p>Kindly note that your Accreditation by ICT Authority for $type is approaching the triennial renewal period.
                You will need to start the renewal process using the link below to ensure validity of your certificate.
                The renew link is on the 'status' column in the Application's tab after opening the link below.</p>

            <p>$link</p>
            <p>Thank you,<br>ICT Authority Accreditation.</p>
                
MSG;
        \app\models\Utility::sendMail($application->user->usr->email, $header, $message);
    }
}
