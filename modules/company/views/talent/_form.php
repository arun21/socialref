<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\company\models\Employee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employee-form">

    <?php

    if(Yii::$app->controller->action->id == 'index')    {
        $form = ActiveForm::begin([
                'action' => \yii\helpers\Url::to(['create'])
        ]);
    }   else    {
        $form = ActiveForm::begin();
    }


    ?>

    <?= $form->field($model, 'email', ['options' => ['class' => 'col-md-6']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'job_id', ['options' => ['class' => 'col-md-6']])->dropDownList(\yii\helpers\ArrayHelper::map(
            \app\modules\company\models\Job::find()->where(['company_id' => Yii::$app->user->identity->getId()])->all(),
            'id',
            'job_title'
    ), ['prompt' => '']) ?>

    <span class="clearfix"></span>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Invite' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
