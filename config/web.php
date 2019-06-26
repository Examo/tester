<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'language' => 'ru',
    'name' => 'EXAMO',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'xMwznFuILAqKTLKy7hrnayjfCt1oxW5Z',
            'baseUrl'=> '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => require(__DIR__ . '/mailer.php'),
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],

        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],

        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'adminPermission' => 'admin',
            'controllerMap' => [
                'settings' => [
                    'class' => 'dektrium\user\controllers\SettingsController',
                    'layout' => '@app/views/layouts/metronic_sidebar',
                ],
                'security' => [
                    'class' => 'dektrium\user\controllers\SecurityController',
                    'layout' => '@app/views/layouts/metronic',
                ],
                'registration' => [
            	    'layout' => '@app/views/layouts/metronic',
                    'class' => \dektrium\user\controllers\RegistrationController::className(),
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        Yii::$app->response->redirect(array('/user/security/login'))->send();
                        Yii::$app->end();
                    },
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_CONFIRM => function ($e) {
                        Yii::$app->response->redirect(array('/site/index'))->send();
                        Yii::$app->end();
                    }
                ],
            ]
        ],
        'rbac' => [
            'class' => 'dektrium\rbac\Module',
            'adminPermission' => 'admin'
        ],
        'markdown' => [
            'class' => 'kartik\markdown\Module',
        ]
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
