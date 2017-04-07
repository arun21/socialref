<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 21/2/17
 * Time: 8:10 AM
 */

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user \app\modules\company\models\Company */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/company/default/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->email) ?>,</p>

    <p>Follow the link below to reset your password:</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>