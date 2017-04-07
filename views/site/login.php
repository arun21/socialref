<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;


$social = Yii::$app->getModule('social');
$callback = \yii\helpers\Url::toRoute([
    '/site/login',
    'validate_fb' => 'true'
], true); // or any absolute url you want to redirect


$authClientCollection = \Yii::$app->authClientCollection;
//var_dump($authClientCollection);

$twitter = $authClientCollection->getClient('twitter');

$twitter_callback = \yii\helpers\Url::toRoute([
    '/site/login',
    'validate_twitter' => 'true'
], true);

$request_token = $twitter->fetchRequestToken(['oauth_callback' => $twitter_callback]);

$auth_url = $twitter->buildAuthUrl($request_token);


$linkedin = $authClientCollection->getClient('linkedin');

$linkin_callback = \yii\helpers\Url::toRoute([
    '/site/login',
    'validate_linkedin' => 'true'
], true);

$linkedin_auth_url = $linkedin->buildAuthUrl(['redirect_uri' => $linkin_callback]);
?>

<div class="site-login col-lg-12 text-center">
    <h1><?= Html::encode($this->title) ?></h1>
    <hr class="star-primary">

    <?= Yii::$app->session->getFlash('error'); ?>

    <?= $social->getFbLoginLink($callback, ['class'=>'btn btn-primary col-md-offset-4 col-md-4'], Yii::$app->params['fb_permissions']) ?>
<br><br>
    <?= \yii\bootstrap\Html::a('Login with Twitter', $auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']) ?>
    <br><br>
    <?=\yii\bootstrap\Html::a('Login with LinkedIn', $linkedin_auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']) ?>

</div>
