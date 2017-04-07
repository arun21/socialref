<?php

namespace app\controllers;

use app\models\Employee;
use app\models\FbDetail;
use app\models\FbPost;
use app\models\LinkedinDetail;
use app\models\Tweet;
use app\models\TwitterDetail;
use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Yii;
use yii\authclient\clients\Twitter;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'connnect'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {
        $twitter_user = TwitterDetail::findOne(1);
        $token = json_decode($twitter_user->access_token);

    }

    public function getFbDetails($user_id)
    {


        $fb_profile = FbDetail::findOne(['employee_id' => $user_id]);

        if(empty($fb_profile))  {
            return false;
        }

        $social = Yii::$app->getModule('social');
        $fb = $social->getFb(); // gets facebook object based on module settings

        $fb->setDefaultAccessToken($fb_profile->access_token);

        try {
            $response = $fb->get('/me/friends')->getDecodedBody();

            $fb_profile->friend_count = $response['summary']['total_count'];
            $fb_profile->update(false, ['friend_count']);

        } catch(Exception $e) {
            // When Graph returns an error
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
            return false;
        }

        try {
            $response = $fb->get('/me/feed?fields=comments.limit(1).summary(true),likes.limit(1).summary(true),description,full_picture,id,message,story,type,picture,name,link,permalink_url,created_time');

            $data = $response->getDecodedBody();

            if(!empty($data['data']))   {
                foreach ($data['data'] as $post)    {
                    $fb_post_id = $post['id'];
                    $post_model = FbPost::findOne([
                        'fb_object_id' => $fb_post_id
                    ]);

                    if(empty($post_model))  {
                        $post_model = new FbPost();
                        $post_model->fb_user_id = $fb_profile->id;
                        $post_model->fb_object_id = $fb_post_id;
                    }

                    $post_model->post_type = isset($post['type'])?$post['type']:null;
                    $post_model->story =  isset($post['story'])?$post['story']:null;
                    $post_model->description =  isset($post['description'])?$post['description']:null;
                    $post_model->picture =  isset($post['picture'])?$post['picture']:null;
                    $post_model->full_picture =  isset($post['full_picture'])?$post['full_picture']:null;
                    $post_model->link =  isset($post['link'])?$post['link']:null;
                    $post_model->fb_link =  isset($post['permalink_url'])?$post['permalink_url']:null;
                    $post_model->like_count =  isset($post['likes']['summary']['total_count'])?$post['likes']['summary']['total_count']:0;
                    $post_model->comment_count =  isset($post['comments']['summary']['total_count'])?$post['comments']['summary']['total_count']:0;
                    $post_model->post_time =  isset($post['created_time'])?strtotime($post['created_time']):0;

                    $post_model->save(false);
                }
            }

        } catch(Exception $e) {
            // When Graph returns an error
            //echo 'Graph returned an error: ' . $e->getMessage();
            //exit;
            return false;
        }
        return true;
    }

    protected function getTweets($twitter, $twitter_model)  {
        $tweets = $twitter->api('statuses/user_timeline.json', 'GET', ['user_id' => $twitter_model->twitter_id]);

        if(!empty($tweets)) {
            foreach ($tweets as $tweet) {
                $tweet_id = $tweet['id'];
                $tweet_text = isset($tweet['text'])?$tweet['text']:'';
                $media_url = isset($tweet['entities']['media'][0]['media_url'])?$tweet['entities']['media'][0]['media_url']:'';
                $media_url_https = isset($tweet['entities']['media'][0]['media_url_https'])?$tweet['entities']['media'][0]['media_url_https']:'';
                $type = isset($tweet['entities']['media'][0]['type'])?$tweet['entities']['media'][0]['type']:'';
                $retweet_count = isset($tweet['retweet_count'])?$tweet['retweet_count']:null;
                $favorite_count = isset($tweet['favorite_count'])?$tweet['favorite_count']:null;
                $tweet_dt = isset($tweet['created_at'])?strtotime($tweet['created_at']):0;

                $tweet_model = Tweet::findOne([
                    'twitter_tweet_id' => $tweet_id
                ]);

                $tweet_text = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $tweet_text);
                $tweet_text = preg_replace('/[\x00-\x1F\x7F]/u', '', $tweet_text);
                $tweet_text = preg_replace('/[\x00-\x1F\x7F]/', '', $tweet_text);

                if(empty($tweet_model))  {
                    $tweet_model = new Tweet();
                    $tweet_model->twitter_user_id = $twitter_model->id;
                    $tweet_model->twitter_tweet_id = $tweet_id;
                }
                $tweet_model->text = $tweet_text;
                $tweet_model->media_url = $media_url;
                $tweet_model->media_url_https = $media_url_https;
                $tweet_model->type = $type;
                $tweet_model->retweet_count = $retweet_count;
                $tweet_model->favorite_count = $favorite_count;
                $tweet_model->tweet_dt = $tweet_dt;
                $tweet_model->save(false);

            }
        }

    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(Yii::$app->user->isGuest)    {
            return $this->render('index');
        }

        $fb_profile = FbDetail::findOne(['employee_id' => Yii::$app->user->identity->getId()]);
        $twitter_profile = TwitterDetail::findOne(['employee_id' => Yii::$app->user->identity->getId()]);

        return $this->render('dashboard', [
            'fb_profile' => $fb_profile,
            'twitter_profile' => $twitter_profile,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        if(Yii::$app->request->get('validate_fb') == 'true')    {
            $social = Yii::$app->getModule('social');
            $fb = $social->getFb(); // gets facebook object based on module settings

            try {
                $helper = $fb->getRedirectLoginHelper();
                $accessToken = $helper->getAccessToken();

                $oAuth2Client = $fb->getOAuth2Client();
                $accessToken = $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

            } catch(FacebookSDKException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            if (isset($accessToken)) { // you got a valid facebook authorization token
                $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
            } elseif ($helper->getError()) {
                throw new BadRequestHttpException($helper->getErrorReason());
            }

            $fb_user = $response->getGraphUser();

            echo '<pre>';
            $email = $fb_user->getEmail();

            $fb_model = FbDetail::findOne([
                'fb_id' => $fb_user->getId()
            ]);

            if(empty($fb_model))    {
                if(!empty($email))  {
                    $emp_model = Employee::findOne(['email' => $email]);
                    if(!empty($emp_model))   {
                        $fb_model = new FbDetail();
                        $fb_model->employee_id = $emp_model->id;

                        $fb_model->fb_id = $fb_user->getId();
                        $fb_model->name = $fb_user->getName();
                        $fb_model->profile_pic = $fb_user->getPicture()->getUrl();
                        $fb_model->access_token = $accessToken->getValue();
                        $fb_model->save(false);

                        $emp_model->invite_token = null;
                        if($emp_model->register_dt) {
                            $emp_model->register_dt = time();
                        }
                        $emp_model->updated_dt = time();
                        $emp_model->profile_pic = $fb_model->profile_pic;

                        $emp_model->save(false);
                    }   else    {
                        \Yii::$app->getSession()->setFlash('error', 'Your account is not registered.');
                        return $this->redirect(['login']);
                    }
                }   else    {
                    \Yii::$app->getSession()->setFlash('error', 'Your account is not registered.');
                    return $this->redirect(['login']);
                }
            }

            $fb_model->fb_id = $fb_user->getId();
            $fb_model->name = $fb_user->getName();
            $fb_model->profile_pic = $fb_user->getPicture()->getUrl();
            $fb_model->access_token = $accessToken->getValue();
            $fb_model->save(false);

            $user = $fb_model->employee;

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                $this->getFbDetails($user->id);
                return $this->goHome();
            }
        }   elseif (Yii::$app->request->get('validate_twitter') == 'true')  {
            $authClientCollection = \Yii::$app->authClientCollection;
            $twitter = $authClientCollection->getClient('twitter');
            $access_token = $twitter->fetchAccessToken();
            $twitter_id = $access_token->params['user_id'];
            $twitter_user = $twitter->api('users/show.json', 'GET', ['user_id' => $twitter_id]);

            $twitter_model = TwitterDetail::findOne([
                'twitter_id' => $twitter_id
            ]);


            if(empty($twitter_model))    {
                \Yii::$app->getSession()->setFlash('error', 'Your account is not registered.');
                return $this->redirect(['login']);
            }

            $user = $twitter_model->employee;

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                $this->getTweets($twitter, $twitter_model);
                return $this->goHome();
            }
        }   elseif(Yii::$app->request->get('validate_linkedin') == 'true')    {
            $authClientCollection = \Yii::$app->authClientCollection;
            $linkedin = $authClientCollection->getClient('linkedin');
            $authCode = Yii::$app->request->get('code');
            $access_token = $linkedin->fetchAccessToken($authCode);
            $linkedin->setAccessToken($access_token);
            $linkedin_user = $linkedin->getUserAttributes();

            $linkedin_id = $linkedin_user['id'];
            $email = $linkedin_user['email-address'];

            $linkedin_model = LinkedinDetail::findOne([
                'linkedin_id' => $linkedin_id
            ]);

            if(empty($linkedin_model))    {
                if(!empty($email))  {
                    $emp_model = Employee::findOne(['email' => $email]);

                    if(!empty($emp_model))  {
                        $linkedin_model = new LinkedinDetail();
                        $linkedin_model->employee_id = $emp_model->id;

                        $linkedin_model->linkedin_id = $linkedin_id;
                        $linkedin_model->name = $linkedin_user['first-name'] . ' '. $linkedin_user['last-name'];
                        $linkedin_model->profile_pic = $linkedin_user['picture-url'];

                        $linkedin_model->access_token = $access_token->params['access_token'];
                        $linkedin_model->save(false);

                        $emp_model->invite_token = null;
                        if($emp_model->register_dt) {
                            $emp_model->register_dt = time();
                        }
                        $emp_model->profile_pic = $linkedin_model->profile_pic;
                        $emp_model->updated_dt = time();
                        $emp_model->save(false);
                    }   else    {
                        \Yii::$app->getSession()->setFlash('error', 'Your account is not registered.');
                        return $this->redirect(['login']);
                    }
                }   else    {
                    \Yii::$app->getSession()->setFlash('error', 'Your account is not registered.');
                    return $this->redirect(['login']);
                }
            }
            $user = $linkedin_model->employee;

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                return $this->goHome();
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @param $invite_token
     * @return string|\yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionRegister($invite_token)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        try {
            $user = Employee::getEmployeeByInviteToken($invite_token);
        }   catch (InvalidParamException $e)    {
            throw new BadRequestHttpException($e->getMessage());
        }

        if(Yii::$app->request->get('validate_fb') == 'true')    {
            $social = Yii::$app->getModule('social');
            $fb = $social->getFb(); // gets facebook object based on module settings

            try {
                $helper = $fb->getRedirectLoginHelper();
                $accessToken = $helper->getAccessToken();

                $oAuth2Client = $fb->getOAuth2Client();
                $accessToken = $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch(FacebookSDKException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            if (isset($accessToken)) { // you got a valid facebook authorization token
                $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
            } elseif ($helper->getError()) {
                throw new BadRequestHttpException($helper->getErrorReason());
            }

            $fb_user = $response->getGraphUser();

            $fb_model = FbDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($fb_model) && $fb_model->fb_id != $fb_user->getId()) {
                $fb_model->delete();
                $fb_model = null;
            }

            if (empty($fb_model)) {
                $fb_model = new FbDetail();
                $fb_model->employee_id = $user->id;
            }

            $fb_model->fb_id = $fb_user->getId();
            $fb_model->name = $fb_user->getName();
            $fb_model->profile_pic = $fb_user->getPicture()->getUrl();
            $fb_model->access_token = $accessToken->getValue();
            $fb_model->save(false);

            $name = $fb_user['name'];
            $tmp_name = explode(' ', $name);
            $first_name = array_shift($tmp_name);
            $last_name = implode(' ', $tmp_name);

            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->profile_pic = $fb_model->profile_pic;
            $user->is_registered = 1;
            $user->invite_token = null;
            $user->register_dt = time();
            $user->updated_dt = time();
            $user->save(false);

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                $this->getFbDetails($user->id);
                return $this->goHome();
            }

            Yii::$app->end();
        }   elseif (Yii::$app->request->get('validate_twitter') == 'true')  {
            $authClientCollection = \Yii::$app->authClientCollection;
            $twitter = $authClientCollection->getClient('twitter');
            $access_token = $twitter->fetchAccessToken();
            $twitter_id = $access_token->params['user_id'];
            $twitter_user = $twitter->api('users/show.json', 'GET', ['user_id' => $twitter_id]);

            $twitter_model = TwitterDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($twitter_model) && $twitter_model->twitter_id != $twitter_id) {
                $twitter_model->delete();
                $twitter_model = null;
            }

            if (empty($twitter_model)) {
                $twitter_model = new TwitterDetail();
                $twitter_model->employee_id = $user->id;
            }


            $twitter_model->twitter_id = $twitter_id;
            $twitter_model->name = $twitter_user['name'];
            $twitter_model->profile_pic = $twitter_user['profile_image_url'];
            $twitter_model->profile_pic = str_replace('_normal', '', $twitter_model->profile_pic);
            $twitter_model->access_token = Json::encode($access_token->params);
            $twitter_model->save(false);

            $name = $twitter_user['name'];

            $tmp_name = explode(' ', $name);
            $first_name = array_shift($tmp_name);
            $last_name = implode(' ', $tmp_name);

            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->profile_pic = $twitter_model->profile_pic;
            $user->is_registered = 1;
            $user->invite_token = null;
            $user->register_dt = time();
            $user->updated_dt = time();
            $user->save(false);

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                $this->getTweets($twitter, $twitter_model);
                return $this->goHome();
            }

            Yii::$app->end();
        }   elseif(Yii::$app->request->get('validate_linkedin') == 'true')    {
            $authClientCollection = \Yii::$app->authClientCollection;
            $linkedin = $authClientCollection->getClient('linkedin');
            $authCode = Yii::$app->request->get('code');
            $access_token = $linkedin->fetchAccessToken($authCode);
            $linkedin->setAccessToken($access_token);
            $linkedin_user = $linkedin->getUserAttributes();


            $linkedin_id = $linkedin_user['id'];


            $linkedin_model = LinkedinDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($linkedin_model) && $linkedin_model->linkedin_id != $linkedin_id) {
                $linkedin_model->delete();
                $linkedin_model = null;
            }

            if (empty($linkedin_model)) {
                $linkedin_model = new LinkedinDetail();
                $linkedin_model->employee_id = $user->id;
            }


            $linkedin_model->linkedin_id = $linkedin_id;
            $linkedin_model->name = $linkedin_user['first-name'] . ' '. $linkedin_user['last-name'];
            $linkedin_model->profile_pic = $linkedin_user['picture-url'];
            $linkedin_model->access_token = $access_token->params['access_token'];
            $linkedin_model->save(false);

            $user->first_name = $linkedin_user['first-name'];
            $user->last_name = $linkedin_user['last-name'];
            $user->profile_pic = $linkedin_model->profile_pic;

            $user->is_registered = 1;
            $user->invite_token = null;
            $user->register_dt = time();
            $user->updated_dt = time();
            $user->save(false);

            $model = new LoginForm();
            $model->username = $user->email;
            if ($model->login()) {
                return $this->goHome();
            }

            Yii::$app->end();
        }


        return $this->render('register', [
            'user' => $user
        ]);
    }

    public function actionConnect()
    {

        $user = Yii::$app->user->identity;

        if(Yii::$app->request->get('validate_fb') == 'true')    {
            $social = Yii::$app->getModule('social');
            $fb = $social->getFb(); // gets facebook object based on module settings

            try {
                $helper = $fb->getRedirectLoginHelper();
                $accessToken = $helper->getAccessToken();

                $oAuth2Client = $fb->getOAuth2Client();
                $accessToken = $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch(FacebookSDKException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            if (isset($accessToken)) { // you got a valid facebook authorization token
                $response = $fb->get('/me?fields=id,name,email,picture', $accessToken);
            } elseif ($helper->getError()) {
                throw new BadRequestHttpException($helper->getErrorReason());
            }

            $fb_user = $response->getGraphUser();

            $fb_model = FbDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($fb_model) && $fb_model->fb_id != $fb_user->getId()) {
                $fb_model->delete();
                $fb_model = null;
            }

            if (empty($fb_model)) {
                $fb_model = new FbDetail();
                $fb_model->employee_id = $user->id;
            }

            $fb_model->fb_id = $fb_user->getId();
            $fb_model->name = $fb_user->getName();
            $fb_model->profile_pic = $fb_user->getPicture()->getUrl();
            $fb_model->access_token = $accessToken->getValue();
            $fb_model->save(false);

            $this->getFbDetails(Yii::$app->user->identity->getId());

            return $this->redirect(['connect']);

        }   elseif (Yii::$app->request->get('validate_twitter') == 'true')  {
            $authClientCollection = \Yii::$app->authClientCollection;
            $twitter = $authClientCollection->getClient('twitter');
            $access_token = $twitter->fetchAccessToken();
            $twitter_id = $access_token->params['user_id'];
            $twitter_user = $twitter->api('users/show.json', 'GET', ['user_id' => $twitter_id]);

            $twitter_model = TwitterDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($twitter_model) && $twitter_model->twitter_id != $twitter_id) {
                $twitter_model->delete();
                $twitter_model = null;
            }

            if (empty($twitter_model)) {
                $twitter_model = new TwitterDetail();
                $twitter_model->employee_id = $user->id;
            }

            $twitter_model->twitter_id = $twitter_id;
            $twitter_model->name = $twitter_user['name'];
            $twitter_model->profile_pic = $twitter_user['profile_image_url'];
            $twitter_model->access_token = Json::encode($access_token->params);
            $twitter_model->save(false);

            $this->getTweets($twitter, $twitter_model);

            return $this->redirect(['connect']);
        }   elseif(Yii::$app->request->get('validate_linkedin') == 'true')    {
            $authClientCollection = \Yii::$app->authClientCollection;
            $linkedin = $authClientCollection->getClient('linkedin');
            $authCode = Yii::$app->request->get('code');
            $access_token = $linkedin->fetchAccessToken($authCode);
            $linkedin->setAccessToken($access_token);
            $linkedin_user = $linkedin->getUserAttributes();


            $linkedin_id = $linkedin_user['id'];


            $linkedin_model = LinkedinDetail::findOne([
                'employee_id' => $user->id
            ]);

            if(!empty($linkedin_model) && $linkedin_model->linkedin_id != $linkedin_id) {
                $linkedin_model->delete();
                $linkedin_model = null;
            }

            if (empty($linkedin_model)) {
                $linkedin_model = new LinkedinDetail();
                $linkedin_model->employee_id = $user->id;
            }

            $linkedin_model->linkedin_id = $linkedin_id;
            $linkedin_model->name = $linkedin_user['first-name'] . ' '. $linkedin_user['last-name'];
            $linkedin_model->profile_pic = $linkedin_user['picture-url'];
            $linkedin_model->access_token = $access_token->params['access_token'];
            $linkedin_model->save(false);

            return $this->redirect(['connect']);
        }

        $fb_count = FbDetail::find()->where(['employee_id'=>$user->getId()])->count();
        $twitter_count = TwitterDetail::find()->where(['employee_id'=>$user->getId()])->count();
        $linkedin_count = LinkedinDetail::find()->where(['employee_id'=>$user->getId()])->count();


        return $this->render('connect', [
            'user' => $user,
            'fb_count' => $fb_count,
            'twitter_count' => $twitter_count,
            'linkedin_count' => $linkedin_count,
        ]);
    }

    protected function getFbExtendedToken($accessToken)  {
        $social = Yii::$app->getModule('social');
        $fb = $social->getFb();
        $extend_url = "https://graph.facebook.com/oauth/access_token?client_id=". $fb->getApp()->getId() ."&client_secret=".$fb->getApp()->getSecret()."&grant_type=fb_exchange_token&fb_exchange_token=".$accessToken->getValue();
        $resp = file_get_contents($extend_url);
        if(empty($resp))    {
            return false;
        }

        parse_str($resp,$output);
        $extended_token = isset($output['access_token'])?$output['access_token']:false;
        return $extended_token;
    }


    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
