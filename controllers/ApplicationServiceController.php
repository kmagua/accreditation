<?php
namespace app\controllers;

use yii\rest\ActiveController;
use app\models\Application;

class ApplicationServiceController extends ActiveController
{
    public $modelClass = 'app\models\Application';
    
    public function actions() {
        parent::actions();        
        $actions = parent::actions();
        // disable the "delete" and "create" actions
        unset($actions['delete'], $actions['create'], $actions['index'], $actions['update'], $actions['view']);
        //$action
        return $actions;
    }
    
    public function actionUpdatePaymentStatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $this->view->title = 'Updating Payment Status';
        
        $request = \Yii::$app->request->headers->get('host');
        if(isset(\Yii::$app->request->post()['token'])){
            $post = \Yii::$app->request->post(); 
            \Yii::$app->db->createCommand('INSERT INTO service_log (status, str_log) VALUES (0, :str_log)', [':str_log' => serialize($post)])
             ->execute();                       
            //if(/*in_array($request, \Yii::$app->params['allowed_hosts']) && */ $this->validateToken($post)){
                $application  = Application::findOne($post['applic_Id']);
                if($application && $application->validatePayment($application->status)){
                    $application->progressWorkFlowStatus("chair-approval");
                    return true;
                }                
            //}
        }        
        return false;
    }
    
    public function validateToken($post)
    {
        $id = $post['applic_Id'];
        $status = $post['status'];
        $post_token = $post['token'];
        $token = md5(date('Y-d-m')) . strtoupper(dechex($id));
        
        if($post_token == $token && $status == 'Confirmed'){
            return true;
        }
        return false;
    }
    
    public function actionReceiveResp()
    {
        \Yii::$app->controller->enableCsrfValidation = false;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $content = file_get_contents('php://input'); //Recieves the response from MPESA as a string

        $res = json_decode($content, false); //Converts the response string to an object
        $myfile = fopen("newfile_test.txt", "w") or die("Unable to open file!");        
        //$txt = $pay_response;
        fwrite($myfile, $res);
        fclose($myfile);
        /*$dataToLog = array(
            date("Y-m-d H:i:s"), //Date and time
            $res
        ); //Sets up the log format: Date, time and the response
        $data = implode(" - ", $dataToLog);

        $data .= PHP_EOL; //Add an end of line to the transaction log

        file_put_contents('transaction_log', $data, FILE_APPEND);// Appends the response to the log file transaction_log*/
    }
}