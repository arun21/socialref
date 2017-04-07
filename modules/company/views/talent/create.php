<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\company\models\Employee */

$this->title = 'Invite Talent';
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
