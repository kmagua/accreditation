<?php
$asSearchModel = \app\models\ApplicationStaff::find()->select("staff_id")->
    join('JOIN', 'company_staff cs', 'staff_id = cs.id')->
    where(['application_id' => $app_id, 'staff_type'=>($s == 1)?['Technical Director', 'Director']:"Staff"])->asArray()->all();
foreach($asSearchModel as $staff_id){
    $staff = app\models\CompanyStaff::findOne($staff_id['staff_id']);
    echo $this->render('../company-staff/view' ,[
        'model' => $staff
    ]);
    echo '<hr>';
}