<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\Url;
use yii\helpers\Html;

use backend\models\LoginForm;
use backend\models\User;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;
use backend\models\Activity;
use backend\models\Training;
use backend\models\Person;
use backend\models\Online;

/**
 * Site controller
 */
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
				'only' => ['logout', 'login', 'signup', 'error', 'index'],
                'rules' => [
					[
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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

    public function actionIndex()
    {
        // Ngambil data
        $data = Activity::find()
            ->where(
                'start > \''.date('Y').'-01-01 00:00:00\''
            )
            ->andWhere(
                'start < \''.date('Y').'-12-31 00:00:00\''
            )
            ->joinWith('training')
            ->all();

        $dataAnggaran = [0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00];
        $dataRealisasi = [0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00,0.00];
        
        foreach ($data as $baris) {
            $angkaBulan = date('m', strtotime($baris->start)) - 1;
            if (!empty($baris->training->cost_plan)) {
                // Artinya aktivitas yang diambil adalah diklat. Rapat uda jelas ga ada duitnya
                $dataAnggaran[$angkaBulan] = $dataAnggaran[$angkaBulan] + $baris->training->cost_plan;
                $dataRealisasi[$angkaBulan] = $dataRealisasi[$angkaBulan] + $baris->training->cost_real;
            }
        }
        
        $dataSeries = [
            [
                'name' => 'Anggaran',
                'type' => 'area',
                'data' => $dataAnggaran
            ],
            [
                'name' => 'Realisasi',
                'type' => 'area',
                'data' => $dataRealisasi
            ]
        ];
        // dah

        // Ngambil jumlah total anggaran
        $totalAnggaran = Activity::find()
            ->where(
                'start > \''.date('Y').'-01-01 00:00:00\''
            )
            ->andWhere(
                'start < \''.date('Y').'-12-31 00:00:00\''
            )
            ->joinWith('training')
            ->sum('training.cost_plan');
        // dah

        // Ngambil jumlah total realisasi
        $totalRealisasi = Activity::find()
            ->where(
                'start > \''.date('Y').'-01-01 00:00:00\''
            )
            ->andWhere(
                'start < \''.date('Y').'-12-31 00:00:00\''
            )
            ->joinWith('training')
            ->sum('training.cost_real');
        // dah

        // Ngambil user yang online
        $userOnline = Online::find()->orderBy('UNIX_TIMESTAMP(time) DESC')->all();
        // dah

        // Ngambil aktivitas terakhir yang terjadi
        $aktivitasTerbaru = Activity::find()
            ->orderBy('UNIX_TIMESTAMP(modified) DESC')
            ->limit(10)
            ->all();
        // dah

        return $this->render('index', [
            'totalAnggaran' => $totalAnggaran,
            'totalRealisasi' => $totalRealisasi,
            'dataSeries' => $dataSeries,
            'userOnline' => $userOnline,
            'aktivitasTerbaru' => $aktivitasTerbaru
        ]);
    }

    public function actionLogin($previous="")
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            // Abis login, kita log ke tabel online,
            $online = Online::findOne(Person::findOne(Yii::$app->user->identity->id)->id);
            if (!empty($online)) {
                // bisa dikembangin lagi, ke arah yg lebih strict, 
                // jadi klo ada 2 user yg sama login di tempat yang berbeda, maka di blok, 
                // karena artinya akun user itu dipake orang lain
                $online->ip = Yii::$app->request->getUserIP();
                $online->time = date('Y-m-d H:i:s');
            }
            else {
                $online = new Online();
                $online->person_id = Person::findOne(Yii::$app->user->identity->id)->id;
                $online->ip = Yii::$app->request->getUserIP();
                $online->time = date('Y-m-d H:i:s');
            }
            if (!$online->save()) {
                die('error, log user online status'.var_dump($online->errors));
            }
            // dah

			if(!empty($previous)){
				return $this->redirect($previous);
			}
			else{
				return $this->goBack();
			}
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        // Hapus log online nya
        $online = Online::findOne(Person::findOne(Yii::$app->user->identity->id)->id);
        if (!empty($online)) {
            // Klo kosong ya uda biarin, klo ada aja baru hapus
            $online->delete();
        }
        // dah

        Yii::$app->user->logout();

        return $this->goHome();
    }
	
	public function actionLockScreen($previous)
    {
		if(isset(Yii::$app->user->identity->username)){
			// save current username	
			$username = Yii::$app->user->identity->username;
			// force logout		
			Yii::$app->user->logout();
			// render form lockscreen
			$model = new LoginForm(); 
			$model->username = $username;	//set default value	
			return $this->render('lockScreen', [
				'model' => $model,
				'previous' => $previous,
			]);  
        }
		else{
			return $this->redirect(['login']);
		}
    }
	
	public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
		$model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {			
            if ($model->sendEmail()) {
				$linkReset = "";
				if (isset(Yii::$app->params['showLinkReset'])){
					$user = User::findOne([
						'status' => User::STATUS_ACTIVE,
						'email' => $model->email,
					]);
					if($user){
						$linkReset = Html::a('reset',Url::to(['reset-password','token'=>$user->password_reset_token]));
					}
				}
				Yii::$app->getSession()->setFlash('success', 'Check your email for further instructions. '.$linkReset);
                //return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
