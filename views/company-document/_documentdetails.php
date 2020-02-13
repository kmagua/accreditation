 <?php
use yii\grid\GridView

?>

<?= GridView::widget([
        'model' => $model,
       
        'attributes' => [
            'id',
            'company_id',
            'document_type_id',
             'upload_file',
            'date_created',
            'last_updated',
        ],
    ]) ?>
