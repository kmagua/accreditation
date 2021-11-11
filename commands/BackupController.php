<?php

namespace app\commands;
use yii\console\Controller;

class BackupController extends Controller
{
    public function actionBackups()
    {
        $backup = \Yii::$app->backup;
        $databases = ['db'];
        foreach ($databases as $k => $db) {
            $index = (string)$k;
            $backup->fileName = 'myapp-part';
            $backup->fileName .= str_pad($index, 3, '0', STR_PAD_LEFT);
            $backup->directories = [];
            $backup->databases = [$db];
            $file = $backup->create();
            $this->stdout('Backup file created: ' . $file . PHP_EOL, \yii\helpers\Console::FG_GREEN);
        }
    }
    
    public function actionTesting()
    {
        /** @var \demi\backup\Component $backup */
        $backup = \Yii::$app->backups;
        
        $file = $backup->create();

        $this->stdout('Backup file created: ' . $file . PHP_EOL, \yii\helpers\Console::FG_GREEN);
    }
} 