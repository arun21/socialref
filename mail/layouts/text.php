<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 21/2/17
 * Time: 8:09 AM
 */

use yii\helpers\Html;
/** @var \yii\web\View $this view component instance */
/** @var \yii\mail\MessageInterface $message the message being composed */
/** @var string $content main view render result */
?>

<?php $this->beginPage() ?>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<?php $this->endPage() ?>