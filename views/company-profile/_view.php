<?php
//use yii\widgets\DetailView;
use kartik\tabs\TabsX;
use app\models\CompanyDocumentSearch;

$companyDocumnets = new CompanyDocumentSearch();
$companyDocumnets->company_id = $model->id;
$dataProvider = $companyDocumnets->search(Yii::$app->request->queryParams);
?>


<?= TabsX::widget([
    'position' => TabsX::ALIGN_LEFT,
    'align' => TabsX::ALIGN_LEFT,
    'items' => [
        [
            'label' => 'Company Profile',
            'content' =>$this->render('_companydetail', [
                'model' => $model,
                //'dataProvider' => $dataProvider,
            ]),
            'active' => true
        ],
        [
              'label' => 'Company Documents',
            'content' =>$this->render('../company-document/_documentdetails', [
                'model' => $companyDocumnets,
                 'dataProvider' => $dataProvider,
                 ]),
      
            
        ],
        [
            'label' => 'Dropdown',
            'items' => [
                 [
                     'label' => 'DropdownA',
                     'content' => 'DropdownA, Anim pariatur cliche...',
                 ],
                 [
                     'label' => 'DropdownB',
                     'content' => 'DropdownB, Anim pariatur cliche...',
                 ],
            ],
        ],
    ],
]);
?>



