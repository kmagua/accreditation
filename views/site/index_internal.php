<?php
use app\models\Application;
$this->title = "Home -  Supplier Accreditation";
use scotthuangzl\googlechart\GoogleChart;

$status_summary = array(
    array('Status', 'Number of Applications'),        
);
$data = array(
    array('Category', 'Number of Applications'),        
);
$data_approved_apps = array(
    array('Category', 'Number of Applications'),        
);
$levels_summary = array(
    array('Status', 'Number of Applications'),        
);

$all_apps_per_cat = Application::getAllAppsPerCategory();
$approved_apps_per_cat = Application::getAllAppsPerCategory(true);
$application_status_summary = Application::getStatusSummary();
$accredit_levels = Application::getAccreditationLevelData();

foreach($all_apps_per_cat as $category){
    $data[] = array($category['name'], (float)$category['id']);    
}

foreach($approved_apps_per_cat as $approv_category){
    $data_approved_apps[] = array($approv_category['name'], (float)$approv_category['id']);    
}

foreach($application_status_summary as $app_status_summary){
    $status_summary[] = array($app_status_summary['status'], (float)$app_status_summary['id']);    
}

foreach($accredit_levels as $accredit_level){
    $levels_summary[] = array($accredit_level['classification'], (float)$accredit_level['id']);    
}

echo GoogleChart::widget(array('visualization' => 'ColumnChart',
    'data' => $data,
    'options' => array('title' => 'Number of Applications Per Accreditation Category', 'colors'=> ['red'], 'legend.position' =>'center')));

echo GoogleChart::widget(array('visualization' => 'ColumnChart',
    'data' => $data,
    'options' => array('title' => 'Number of Applications Per Accreditation Category', 'legend.position' =>'center', 'colors'=> ['#008000'])));

echo GoogleChart::widget(array('visualization' => 'ColumnChart',
    'data' => $levels_summary,
    'options' => array('title' => 'Applications Per Accreditation Level', 'colors'=> ['red'], 'legend'=>'left')));

echo GoogleChart::widget(array('visualization' => 'ScatterChart',
    'data' => $status_summary,
    'options' => array('title' => 'Appplications Status Summary', 'legend'=>'left', 'is3D' => true, 'colors'=> ['#008000']
        )));//'hAxis'=> array('slantedText'=>true, 'slantedTextAngle' => 90 ,'height'=>900)

?>