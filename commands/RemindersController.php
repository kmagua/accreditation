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
class RemindersController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex()
    {
        $sql = "SELECT usr.email, CASE 
	WHEN icm.`committee_id` = 1 THEN 'Secretariat'
	ELSE 'Committee'
	END STATUS, app.id, icm.`committee_id`
FROM `supplier_accreditation`.`application` app 
JOIN `supplier_accreditation`.`application_committe_member`  acm ON app.id = acm.`application_id`
JOIN `icta_committee_member` icm ON icm.id = acm.`committee_member_id`
JOIN `supplier_accreditation`.`user` usr ON usr.id = icm.`user_id`
WHERE app.status IN('ApplicationWorkflow/at-secretariat', 'ApplicationWorkflow/at-committee')";
        
        $recs = \app\models\Application::findBySql($sql)->asArray()->all();
        foreach($recs as $rec){
            $link = "http://accreditation.icta.go.ke/application/approval?id={$rec['id']}&level={$rec['committee_id']}";
            $doc_link = "<a href='".$link ."' target='_blank'>Quick Access</a> $link";
            $msg = "Hi,        
                <p>You are reminded to review an application that was assigned to you.</p>
                <p>$doc_link</p>
                
                Regards,<br>
                ICTA Accreditation Team.";
            
            \app\models\Utility::sendMail($rec['email'], 'Reminder to Review an Accredtation Application', $msg);
            //echo $rec['id'], ' => ', $rec['committee_id'] , PHP_EOL;
        }
        return ExitCode::OK;
    }
    
    public function actionDisableExpiredAccounts()
    {
        $sql = "UPDATE `user` SET `status` = '0' WHERE DATEDIFF(NOW(), last_password_change_date) = 60";
        \Yii::$app->db->createCommand($sql)->execute();
    }
}
