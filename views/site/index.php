<?php

/* @var $this yii\web\View */

$this->title = 'SocialRef';

$this->registerCss('
.logo   {
    padding-top: 10px;
    padding-left: 60px;
}
.site-index {
    background-image: url("'. \yii\helpers\Url::to(["/images/index-background.png"]).'");
    background-repeat: no-repeat;
    background-position: right bottom;
    min-height: 650px;
}

');

?>
<div class="site-index">
    <img class="logo" src="<?= \yii\helpers\Url::to(['/images/logo.png']) ?>"/>
    <br><br><br>
    <div class="row">
        <div class="col-md-6 col-md-offset-1" style="text-align: center;">
            <div style="font-size: 26px; font-weight: bold; color: #4b83f2;">
                SocialRef lets you complete a 20 minute<br>
                social media screen in less than 2 minutes!
            </div>
            <br>
            <br>
            <div style="font-size: 21px; color: rgb(128, 128, 128);">
                We provide social-analytics for Candidates by scanning popular social media accounts to create a consistent and shareable social snapshot with real-time updates
            </div>
            <br>
            <br>
            <p><a class="btn btn-lg btn-success" href="<?= \yii\helpers\Url::to(['/site/login']); ?>">Login / SignUp</a></p>
            <br>
            <br>
            <br>
            <br>
            <p>
                <img src="<?= \yii\helpers\Url::to(['/images/android-app.png']) ?>">
                <img src="<?= \yii\helpers\Url::to(['/images/iphone-app.png']) ?>">
            </p>
        </div>
    </div>
</div>
<p style="text-align: center; font-size: 27px; color: rgb(128, 128, 128); font-weight: bold; padding: 20px;">
    SocialRef generates a single Social Profile with real-time analytics for each candidate allowing for consistent, unbiased and not discriminatory social screening.
</p>
