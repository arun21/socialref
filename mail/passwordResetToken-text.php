<?php
/* @var $this yii\web\View */
/* @var $user \app\modules\company\models\Company */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['/company/default/reset-password', 'token' => $user->password_reset_token]);
?>
    Hello <?= $user->email ?>,

    Follow the link below to reset your password:

<?= $resetLink ?>