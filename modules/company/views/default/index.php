<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\company\models\JobSearch */
/* @var $$model app\modules\company\models\Job */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jobs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--        <?//= Html::a('Create Job', ['create'], ['class' => 'btn btn-success']) ?>
-->

    <div class="row">
        <div class="col-xs-6">
            <?= Html::a('Job', ['/company'], ['class' => 'btn btn-success active']) ?>
            <?= Html::a('Talent', ['/company/talent'], ['class' => 'btn btn-success']) ?>

        </div>
        <div class="col-xs-6" style="text-align: right;">
            <?= Html::a('Create Job', '#myModal', [
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

            //'id',
            'job_title',

            [
                'attribute' => 'created_dt',
                'value' => function($data) {
                    return date("d-M-Y", $data->created_dt);
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>


<div class="modal fade" id="myModal"  tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Create Job</h4>
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