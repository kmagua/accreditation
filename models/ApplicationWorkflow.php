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
                    'transition' => ['application-paid']
                ],
                'application-paid' => [
                    'transition' => ['application-payment-confirmed', 'application-payment-rejected']
                ],
                'application-payment-confirmed' => [
                    'transition' => ['at-secretariat']
                ],
                'application-payment-rejected' => [
                    'transition' => ['draft']
                ],
                'at-secretariat' => [
                    'transition' => ['at-committee', 'draft']
                ],
                'at-committee' => [
                    'transition' => ['approved', 'draft']
                ],
                'approved' => [
                    'transition' => ['approval-payment']
                ],
                'approval-payment' => [
                    'transition' => ['approval-payment-confirmed', 'approval-payment-rejected']
                ],
                'approval-payment-confirmed' => [
                    'transition' => ['completed']
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
