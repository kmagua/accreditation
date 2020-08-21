<?php

if(gethostname() == 'DESKTOP-5E94PGU'){
    return [
        'class' => 'yii\swiftmailer\Mailer',
        'useFileTransport' => true
    ];
}else{
    return [
        'class' => 'yii\swiftmailer\Mailer',
        'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'smtp.gmail.com',
            'username' => 'standards@ict.go.ke',
            'password' => 'driuceycrebiwvlj',
            'port' => '587',
            'encryption' => 'tls',
        ]
    ];
}