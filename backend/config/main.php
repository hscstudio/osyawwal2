<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'language' => 'id-ID',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','heart'],
	'homeUrl' => '/github/osyawwal2/administrator',
    'modules' => [
		'heart' => [
            'class' => 'hscstudio\heart\Module',
            'features'=>[
                // datecontrol (kartik), gridview (kartik), gii, 
                'datecontrol'=>true,// use false for not use it
                'gridview'=>true,// use false for not use it
                'gii'=>true, // use false for not use it
                'privilege'=>[
					'allowActions' => [
						// DEFAULT 
						'debug/*',
						'site/*',
						'gridview/*',	// add or remove allowed actions to this list
						'file/*',
						'user/*',		
						
						
						
						/* 'gii/*',						
						'privilege/*',
						// DEFAULT 
										
						// ADMIN 
						'admin/*',
						// ADMIN 
						// START SEKRETARIAT 
						'sekretariat-organisation/*',
						'sekretariat-hrd/*',
						'sekretariat-finance/*',
						'sekretariat-it/*',
						'sekretariat-general/*',
						// FINISH SEKRETARIAT 
						// START PUSDIKLAT 
						'pusdiklat-general/*',
						'pusdiklat-planning/*',
						'pusdiklat-execution/*',
						'pusdiklat-evaluation/*',
						// FINISH PUSDIKLAT 
						// START PUSDIKLAT2 
						'pusdiklat2-general/*',
						'pusdiklat2-training/*',
						'pusdiklat2-test/*',
						'pusdiklat2-scholarship/*',
						// FINISH PUSDIKLAT2 
						// START BDK 
						'bdk-general/*',
						'bdk-execution/*' ,
						'bdk-evaluation/*' ,
						// FINISH BDK   */
					],
					'authManager' => [
					  'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
					],
				],
            ],
        ],
		'admin' => [
            'class' => 'backend\modules\admin\Module',
        ],
		'user' => [
            'class' => 'backend\modules\user\Module',
        ],
		'student' => [
            'class' => 'frontend\modules\student\Module',
        ],
		/* START SEKRETARIAT */
		'sekretariat-hrd' => [
			'class' => 'backend\modules\sekretariat\hrd\Module',
		], 
		'sekretariat-general' => [
            'class' => 'backend\modules\sekretariat\general\Module',
        ],
		'sekretariat-organisation' => [
            'class' => 'backend\modules\sekretariat\organisation\Module',
        ],
		'sekretariat-finance' => [
            'class' => 'backend\modules\sekretariat\finance\Module',
        ],
		'sekretariat-it' => [
            'class' => 'backend\modules\sekretariat\it\Module',
        ],
		/* START SEKRETARIAT */
        /* START PUSDIKLAT */
        'pusdiklat-general' => [
            'class' => 'backend\modules\pusdiklat\general\Module',
        ],
        'pusdiklat-planning' => [
            'class' => 'backend\modules\pusdiklat\planning\Module',
        ],
        'pusdiklat-execution' => [
            'class' => 'backend\modules\pusdiklat\execution\Module',
        ],
        'pusdiklat-evaluation' => [
            'class' => 'backend\modules\pusdiklat\evaluation\Module',
        ],
        /* FINISH PUSDIKLAT */
		/* START PSDM */
		'pusdiklat2-general' => [
            'class' => 'backend\modules\pusdiklat2\general\Module',
        ],
		'pusdiklat2-competency' => [
            'class' => 'backend\modules\pusdiklat2\competency\Module',
        ],
		'pusdiklat2-test' => [
            'class' => 'backend\modules\pusdiklat2\test\Module',
        ],
		'pusdiklat2-scholarship' => [
            'class' => 'backend\modules\pusdiklat2\scholarship\Module',
        ],
		
		'diklat' => [
            'class' => 'frontend\views\site\diklat\module',
        ],
		/* FINISH PSDM */
		/* START BDK */
		'bdk-general' => [
            'class' => 'backend\modules\bdk\general\Module',
        ],
		'bdk-planning' => [
            'class' => 'backend\modules\bdk\planning\Module',
        ],
		'bdk-execution' => [
            'class' => 'backend\modules\bdk\execution\Module',
        ],
		'bdk-evaluation' => [
            'class' => 'backend\modules\bdk\evaluation\Module',
        ],
		/* FINISH BDK */
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
            'identityClass' => 'backend\models\User',
            'enableAutoLogin' => true,
			'identityCookie' => [
			  'name' => '_backendUser', // unique for backend
			  'path'=>'c:\xampp\htdocs\github\osyawwal2\backend\web'  // correct path for the backend app.
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
		// http://mickgeek.com/yii-2-advanced-template-on-the-same-domain
		'request' => [
            'baseUrl' => '/github/osyawwal2/administrator',
			'enableCsrfValidation' => true,
			'enableCookieValidation'=>true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'suffix' => '.aspx',
			/* 'rules' => [
				['class' => 'hscstudio\heart\helpers\UrlRule', 'connectionID' => 'db',],
			], */
        ],		
    ],
    'params' => $params,
];
