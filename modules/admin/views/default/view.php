<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Company */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

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

    <?php

    $status_arr = [
            'E' => 'Enabled',
            'D' => 'Disabled',
            'S' => 'Suspended',
    ];

    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'company_name',
            'profile_code',
            'email:email',
//            'password',
            'first_name',
            'last_name',
            'phone_number',
            'website',
            'country',
            'city',
            'postal_code',
            [
                'attribute' => 'company_status',
                'value' => isset($status_arr[$model->company_status])?$status_arr[$model->company_status]:null,
            ],
            [
                'attribute' => 'logo_path',
                'value' => Html::img(['/uploads/logo/'.$model->logo_path]),
                'format' => 'raw',
            ],
//            'created_dt',
//            'updated_dt',
        ],
    ]) ?>

</div>
