<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\CompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
 //       'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'company_name',
            'profile_code',
            //'logo_path',
            'email:email',
            // 'password',
             'first_name',
             'last_name',
            [
                    'attribute' => 'company_status',
                    'value' => function($data) {
                        $status_arr = [
                            'E' => 'Enabled',
                            'D' => 'Disabled',
                            'S' => 'Suspended',
                        ];
                        return isset($status_arr[$data->company_status])?$status_arr[$data->company_status]:null;
                    },
            ],
            // 'phone_number',
            // 'website',
            // 'country',
            // 'city',
            // 'postal_code',
            // 'company_status',
            // 'created_dt',
            // 'updated_dt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
