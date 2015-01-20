<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
	'language' => 'id-ID',
    'bootstrap' => ['log','heart'],
	'homeUrl' => '/github/osyawwal2',
    'controllerNamespace' => 'frontend\controllers',
	'modules'=>[
		'heart' => [
            'class' => 'hscstudio\heart\Module',
            'features'=>[
                // datecontrol (kartik), gridview (kartik), gii, 
                'datecontrol'=>true,// use false for not use it
                'gridview'=>true,// use false for not use it
				'privilege'=>false,
            ],
        ],
		'student' => [
            'class' => 'frontend\modules\student\Module',
        ],
		'trainingclass' => [
            'class' => 'frontend\modules\trainingclass\Module',
        ],
	],
    'components' => [
		'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'frontend\models\Student',
            'enableAutoLogin' => true,
			'identityCookie' => [
			  'name' => '_frontendUser', // unique for backend
			  'path'=>'c:\xampp\htdocs\github\osyawwal2\frontend\web'  // correct path for the backend app.
			]
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'request' => [
            'baseUrl' => '/github/osyawwal2',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'suffix' => '.aspx',
			'rules' => [
				['class' => 'hscstudio\heart\helpers\UrlRule', 'connectionID' => 'db', /* ... */],
			],
        ],
    ],
    'params' => $params,
];
