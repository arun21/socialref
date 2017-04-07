<?php
/**
 * Created by Netbeans.
 * User: Arun
 * Date: 21/2/17
 * Time: 12:07 PM
 */

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

$social = Yii::$app->getModule('social');
$callback = \yii\helpers\Url::toRoute([
    '/site/register',
    'invite_token' => $user->invite_token,
    'validate_fb' => 'true'
], true); // or any absolute url you want to redirect


$authClientCollection = \Yii::$app->authClientCollection;
//var_dump($authClientCollection);

$twitter = $authClientCollection->getClient('twitter');

$twitter_callback = \yii\helpers\Url::toRoute([
    '/site/register',
    'invite_token' => $user->invite_token,
    'validate_twitter' => 'true'
], true);

$request_token = $twitter->fetchRequestToken(['oauth_callback' => $twitter_callback]);

$auth_url = $twitter->buildAuthUrl($request_token);


$linkedin = $authClientCollection->getClient('linkedin');

$linkin_callback = \yii\helpers\Url::toRoute([
    '/site/register',
    'invite_token' => $user->invite_token,
    'validate_linkedin' => 'true'
], true);

$linkedin_auth_url = $linkedin->buildAuthUrl(['redirect_uri' => $linkin_callback]);
?>

    <div class="site-login col-lg-12 text-center">
    <h1><?= \yii\bootstrap\Html::encode($this->title) ?></h1>
    <hr class="star-primary">

    <?= $social->getFbLoginLink($callback, ['class'=>'btn btn-primary col-md-offset-4 col-md-4'], Yii::$app->params['fb_permissions']) ?>
    <br><br>
    <?= \yii\bootstrap\Html::a('Login with Twitter', $auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']) ?>
        <br><br>
    <?=\yii\bootstrap\Html::a('Login with LinkedIn', $linkedin_auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']) ?>

    </div>

