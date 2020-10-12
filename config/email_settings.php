<?php

if($_SERVER['SERVER_NAME'] == 'localhost'){
    return [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => true
    ];
}else{
    return [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => false,
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'standards@ict.go.ke',
            'password' => 'driuceycrebiwvlj',
            'port' => '587',
            'encryption' => 'tls',
            //'streamOptions' => [ 'ssl' => [ 'allow_self_signed' => true, 'verify_peer' => false, 'verify_peer_name' => false]]
        ]
    ];
}