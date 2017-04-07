<?php
/**
 * Created by Netbeans.
 * User: Arun
 * Date: 8/3/17
 * Time: 6:21 AM
 */

$this->title = 'SocialRef - Dashboard';

$this->registerJsFile('https://www.gstatic.com/charts/loader.js');


$this->registerJs(
    "google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Facebook', 'Twitter', 'LinkedIn'],
          ['2013',  1000,      400, 100],
          ['2014',  1170,      460, 500],
          ['2015',  660,       1120, 450],
          ['2016',  1030,      540, 1000],
          ['2017',  1530,      1340, 1400]
        ]);

        var options = {
          title: '',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
",
    \yii\web\View::POS_END,
    'chart-load'
);

$this->registerCss('
.site-index {
    max-width: 960px;
    margin: 0 auto;
}

.header-label   {
    width: 100%;
    background-color: #2C3E50;
    color: #fff;
    padding: 5px;
}

.dashboard-box-order {
    border-right: solid #18bc9c 4px;
}

');

?>

<div class="site-index">
    <div class="row">
        <div class="col-xs-6 col-md-4" style="text-align: center;">
            <img src="<?= Yii::$app->user->identity->profile_pic ?>" style="max-height: 200px;">
        </div>
        <div class="col-xs-12 col-sm-6 col-md-8">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>
    </div>


    <h3 class="header-label">
        Years of Professional Experience.
    </h3>
    <div class="row">
        <div class="col-md-4 dashboard-box-order">
            <b>Section 1.10.32 of "de Finibus Bonorum et Malorum", written by Cicero in 45 BC</b>
            <br>
            <ul>
                <li>Coffee</li>
                <li>Tea</li>
                <li>Milk</li>
            </ul>

        </div>
        <div class="col-md-4 dashboard-box-order">
            <b>1914 translation by H. Rackham</b>
            <br>
            <ul>
                <li>Coffee</li>
                <li>Tea</li>
                <li>Milk</li>
            </ul>
        </div>
        <div class="col-md-4">
            <b>
                The standard Lorem Ipsum passage, used since the 1500s
            </b>
            <br>
            <ul>
                <li>Coffee</li>
                <li>Tea</li>
                <li>Milk</li>
            </ul>
        </div>
    </div>



    <h3 class="header-label">
        Most Endorsed Skills
    </h3>
    <div class="row">
        <div class="col-md-4 dashboard-box-order">
            <ul>
                <li>Nam libero tempore</li>
                <li>sapiente delectus</li>
                <li>Quis autem vel eum</li>
                <li>Sed ut perspiciatis unde omnis iste</li>
            </ul>

        </div>
        <div class="col-md-4 dashboard-box-order">
            <ul>
                <li>sapiente delectus</li>
                <li>Sed ut perspiciatis unde omnis iste</li>
                <li>Nam libero tempore</li>
                <li>Quis autem vel eum</li>
            </ul>
        </div>
        <div class="col-md-4">
            <ul>
                <li>Sed ut perspiciatis unde omnis iste</li>
                <li>Nam libero tempore</li>
                <li>Quis autem vel eum</li>
                <li>sapiente delectus</li>
            </ul>
        </div>
    </div>


    <h3 class="header-label">
        Years of Social-Media Activity
    </h3>

    <div id="chart_div" style="width: 100%; height: 500px;"></div>

    <?php if(!empty($fb_profile))   {
        ?>

    <h3 class="header-label">
        <div class="col-md-4" style="text-align: center"><?= $fb_profile->friend_count ?> <div style="font-size: 12px;">Friends</div></div>
        <div class="col-md-4" style="text-align: center"><?= \app\models\FbPost::find()->where(['fb_user_id' => $fb_profile->id])->limit(3)->orderBy('post_time DESC')->sum('like_count') ?> <div style="font-size: 12px;">Likes</div></div>
        <div class="col-md-4" style="text-align: center"><?= \app\models\FbPost::find()->where(['fb_user_id' => $fb_profile->id])->limit(3)->orderBy('post_time DESC')->sum('comment_count') ?> <div style="font-size: 12px;">Comments</div></div>
        <span class="clearfix"></span>
    </h3>
    <div class="row">

        <?php
        $posts = \app\models\FbPost::find()->where(['fb_user_id' => $fb_profile->id])->limit(3)->orderBy('post_time DESC')->all();

        if(!empty($posts))  {
            $i = 1;
            foreach ($posts as $post)   {
                echo '<div class="col-md-4';

                if($i < 3)  {
                    echo ' dashboard-box-order';
                }
                echo '">';
                echo $this->render('_fb_post', ['post' => $post, 'i' => $i]);
                echo '</div>';
                $i++;
            }
        }

        ?>

    </div>
    <?php   }   ?>



    <?php if(!empty($twitter_profile))   {
        ?>

        <h3 class="header-label">
            <div class="col-md-4" style="text-align: center">22 <div style="font-size: 12px;">Followers</div></div>
            <div class="col-md-4" style="text-align: center">33 <div style="font-size: 12px;">Following</div></div>
            <div class="col-md-4" style="text-align: center"><?= \app\models\Tweet::find()->where(['twitter_user_id' => $twitter_profile->id])->limit(3)->orderBy('tweet_dt DESC')->sum('favorite_count') ?> <div style="font-size: 12px;">Favourite</div></div>
            <span class="clearfix"></span>
        </h3>
        <div class="row">

            <?php
            $tweets = \app\models\Tweet::find()->where(['twitter_user_id' => $twitter_profile->id])->limit(3)->orderBy('tweet_dt DESC')->all();

            if(!empty($tweets))  {
                $i = 1;
                foreach ($tweets as $tweet)   {
                    echo '<div class="col-md-4';

                    if($i < 3)  {
                        echo ' dashboard-box-order';
                    }
                    echo '">';
                    echo $this->render('_tweet', ['tweet' => $tweet, 'i' => $i]);
                    echo '</div>';
                    $i++;
                }
            }

            ?>

        </div>
    <?php   }   ?>

</div>
