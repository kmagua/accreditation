<?php

echo "MPesa Payment requests sent to your phone. Kindly enter your PIN to complete "
                . "payment and then click confirm button below to confirm payment <br><br>";
                echo \yii\helpers\Html::a('Confirm Payment', ['application/validate-mpesa', 'id' =>$model->id, 
                    'check'=>$model->CheckoutRequestID], ['class'=>'btn btn-success',
                        'onclick' => "getDataForm(this.href, '<h3>Payment Confirmation</h3>'); return false;"]);
