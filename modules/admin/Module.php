<?php

namespace app\modules\admin;
use yii\helpers\Url;
use yii\web\View;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\admin\models\User',
            'enableAutoLogin' => false,
            'loginUrl' => ['admin/default/login'],
            'identityCookie' => ['name' => 'admin', 'httpOnly' => true],
            'idParam' => 'admin_id', //this is important !
        ]);
        \Yii::$app->set('view', [
            'class' => View::className(),
            'theme' => [
                'basePath' => '@app/themes/admin',
                'baseUrl' => '@web/themes/admin',
                'pathMap' => [
                    '@app/views' => '@app/themes/admin',
                ],
            ],
        ]);

        \Yii::$app->setHomeUrl(Url::to(['/admin']));
    }
}
