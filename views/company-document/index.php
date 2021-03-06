<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CompanyDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$model = new \app\models\CompanyDocument();
$model->company_id = $searchModel->company_id;

?>
<div class="company-document-index">
    <?= ""/*$this->render('_form', [
        'model' => $model,
    ]);*/
    ?>
     <?= Html::a("Add Document", ['company-document/data', 'cid'=> $searchModel->company_id], [
        'class' => 'btn btn-success', 'onclick'=>'getDataForm(this.href, "<h3> Documents for ' . $searchModel->company->company_name. '</h3>"); return false;'
        ]); 
    ?>
    <i style="color:red">Business permit, Certificate of Incorporation, KRA tax compliance are MANDATORY.</i>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'company_id',
            'companyTypeDoc.documentType.name',
            [
                'attribute' => 'upload_file',
                'content' => function($data){
                    return $data->fileLink(true);
                }
            ],
            //'date_created',
            //'last_updated',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['style' => 'width: 7%'],
                //'visible'=> Yii::$app->user->isGuest ? false : true,
                'template' => '{update}{delete}',
                'buttons'=>[                    
                    'update' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-document/update-ajax', 'id'=>$model->id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-pencil btn btn-default btn-xs custom_button',
                            'title' =>"Edit Company Document",
                            'onclick'=>"getDataForm('$url', '<h3>Document Edit</h3>'); return false;"]);
                    },
                    'delete' => function ($url, $model) {
                        $url = yii\helpers\Url::to(['company-document/delete-ajax', 'id'=>$model->id]);
                        $return_link = yii\helpers\Url::to(['company-document/data', 'cid'=>$model->company_id]);
                        return Html::a('', $url, ['class' => 'glyphicon glyphicon-trash', 'title' =>"Delete",
                            'onclick'=>"ajaxDeleteRecord('$url', '$return_link'); return false;"]);
                    },
                ],                
            ],
        ],
    ]); ?>

</div>
