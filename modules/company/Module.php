<?php

namespace app\modules\company;
use yii\helpers\Url;
use yii\web\View;

/**
 * company module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\company\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        \Yii::$app->set('user', [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\company\models\Company',
            'enableAutoLogin' => false,
            'loginUrl' => ['company/default/login'],
            'identityCookie' => ['name' => 'company', 'httpOnly' => true],
            'idParam' => 'company_id', //this is important !
        ]);
        \Yii::$app->set('view', [
            'class' => View::className(),
            'theme' => [
                'basePath' => '@app/themes/company',
                'baseUrl' => '@web/themes/company',
                'pathMap' => [
                    '@app/views' => '@app/themes/company',
                ],
            ],
        ]);

        \Yii::$app->setHomeUrl(Url::to(['/company']));
    }
}
