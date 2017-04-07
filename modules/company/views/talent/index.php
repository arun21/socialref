<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\company\models\EmployeeSearch */
/* @var $model app\modules\company\models\Employee */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Talents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--        <?//= Html::a('Invite Talent', ['create'], ['class' => 'btn btn-success']) ?>
-->

    <div class="row">
        <div class="col-xs-6">
            <?= Html::a('Job', ['/company'], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Talent', ['/company/talent'], ['class' => 'btn btn-success active']) ?>

        </div>
        <div class="col-xs-6" style="text-align: right;">
            <?= Html::a('Invite Talent', '#myModal', [
                'class' => 'btn btn-success',
                'data-toggle' => 'modal',
                'data-target' => '#myModal',
            ]) ?>
        </div>
    </div>

<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'job.job_title',
            'email:email',
            'first_name',
            'last_name',
            // 'is_registered',
            // 'created_dt',
            // 'register_dt',
            // 'updated_dt',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>




<div class="modal fade" id="myModal"  tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Invite Talent</h4>
            </div>
            <div class="modal-body">
                <?php
                echo $this->render('_form', ['model' => $model])
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->