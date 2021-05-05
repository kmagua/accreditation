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
        
        $request = \Yii::$app->request->headers->get('host');
        if(isset(\Yii::$app->request->post()['token'])){
            $post = \Yii::$app->request->post();            
            if(/*in_array($request, \Yii::$app->params['allowed_hosts']) && */ $this->validateToken($post)){
                $application  = Application::findOne($post['applic_Id']);
                if($application && $application->validatePayment($application->status)){
                    $application->progressWorkFlowStatus("chair-approval");
                    return true;
                }                
            }
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
}