<?php
use yii\widgets\DetailView;
use yii\helpers\Html;
?>
<p>
    <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Are you sure you want to delete this item?',
            'method' => 'post',
        ],
    ]) ?>
</p>
<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idno',
            'first_name',
            'last_name',
            'other_names',
            'date_of_birth',
            'gender',
            'phone',
            'nationality',
            'county',
            'postal_address',
            'date_created',
            'date_modified',
        ],
    ]) ?>