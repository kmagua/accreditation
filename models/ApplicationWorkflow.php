<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\models;

/**
 * Description of ApplicationWorkflow
 *
 * @author Administrator
 */
class ApplicationWorkflow implements \raoul2000\workflow\source\file\IWorkflowDefinitionProvider{
    public function getDefinition() 
    {
        return [
            'initialStatusId' => 'draft',
            'status' => [
                'draft' => [
                    'transition' => ['at-secretariat']
                ],
                /*'application-paid' => [
                    'transition' => ['application-payment-confirmed', 'application-payment-rejected']
                ],
                'application-payment-confirmed' => [
                    'transition' => ['at-secretariat']
                ],
                'application-payment-rejected' => [
                    'transition' => ['draft']
                ],*/
                'at-secretariat' => [
                    'transition' => ['assign-approval-committee', 'sec-rejected']
                ],
                'assign-approval-committee' => [
                    'transition' => ['at-committee']
                ],
                'at-committee' => [
                    'transition' => ['approved', 'com-rejected']
                ],
                'sec-rejected' => [
                    'transition' => ['at-secretariat']
                ],
                'com-rejected' => [
                    'transition' => ['at-committee']
                ],
                'approved' => [
                    'transition' => ['certificate-paid']
                ],
                'certificate-paid' => [
                    'transition' => ['completed', 'approval-payment-rejected']
                ],
                 'approval-payment-rejected' => [
                    'transition' => ['approved']
                ],
                'completed' => [
                    'transition' => ['renewal']
                ],
                'renewal' => [
                    'transition' => ['completed']
                ],
            ]
        ];
    }
}
