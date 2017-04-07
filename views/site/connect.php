<?php
/**
 * Created by Netbeans.
 * User: Arun
 * Date: 4/3/17
 * Time: 10:43 AM
 */

$social = Yii::$app->getModule('social');
$callback = \yii\helpers\Url::toRoute([
    '/site/connect',
    'invite_token' => $user->invite_token,
    'validate_fb' => 'true'
], true); // or any absolute url you want to redirect


$authClientCollection = \Yii::$app->authClientCollection;
//var_dump($authClientCollection);

$twitter = $authClientCollection->getClient('twitter');

$twitter_callback = \yii\helpers\Url::toRoute([
    '/site/connect',
    'invite_token' => $user->invite_token,
    'validate_twitter' => 'true'
], true);

$request_token = $twitter->fetchRequestToken(['oauth_callback' => $twitter_callback]);

$auth_url = $twitter->buildAuthUrl($request_token);


$linkedin = $authClientCollection->getClient('linkedin');

$linkin_callback = \yii\helpers\Url::toRoute([
    '/site/connect',
    'invite_token' => $user->invite_token,
    'validate_linkedin' => 'true'
], true);

$linkedin_auth_url = $linkedin->buildAuthUrl(['redirect_uri' => $linkin_callback]);
?>


<div class="jumbotron">

<?php
if(empty($fb_count))    {
    echo $social->getFbLoginLink($callback, ['class'=>'btn btn-primary col-md-offset-4 col-md-4', 'label' => 'Connect to Facebook'], Yii::$app->params['fb_permissions']);
}   else    {
    echo \yii\bootstrap\Html::button('Connected to Facebook', ['class'=>'btn btn-primary col-md-offset-4 col-md-4', 'disabled' => 'disabled']);
}
?>
    <span class="clearfix"></span>
<br><br>
<?php
if(empty($twitter_count))    {
    echo \yii\bootstrap\Html::a('Connect to Twitter', $auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']);
}   else    {
    echo \yii\bootstrap\Html::button('Connected to Twitter', ['class'=>'btn btn-primary col-md-offset-4 col-md-4', 'disabled' => 'disabled']);
}
?>
    <span class="clearfix"></span>
    <br><br>

<?php
if(empty($linkedin_count))    {
    echo \yii\bootstrap\Html::a('Connect to LinkedIn', $linkedin_auth_url, ['class'=>'btn btn-primary col-md-offset-4 col-md-4']);
}   else    {
    echo \yii\bootstrap\Html::button('Connected to LinkedIn', ['class'=>'btn btn-primary col-md-offset-4 col-md-4', 'disabled' => 'disabled']);
}
?>
    <span class="clearfix"></span>
</div>
