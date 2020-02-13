<?php
use yii\widgets\DetailView

?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'business_reg_no',
        'company_name',
        'registration_date',
        'county',
        'town',
        'building',
        'floor',
        'telephone_number',
        'company_email:email',
        'type_of_business',
        'postal_address',
        'company_categorization',
        'user_id',
        'date_created',
        'last_updated',
    ],
]) ?>