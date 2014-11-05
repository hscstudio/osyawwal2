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

use backend\models\Issue;
use backend\models\IssueSearch; 

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
                        'actions' => ['logout', 'index', 'issue'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
					'delete-issue' => ['post'],
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
            $dataAnggaran[$angkaBulan] = $dataAnggaran[$angkaBulan] + $baris->training->cost_plan;
            $dataRealisasi[$angkaBulan] = $dataRealisasi[$angkaBulan] + $baris->training->cost_real;
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
        $userOnline = Online::find()->orderBy(['time' => 'ASC'])->all();

        return $this->render('index', [
            'totalAnggaran' => $totalAnggaran,
            'totalRealisasi' => $totalRealisasi,
            'dataSeries' => $dataSeries,
            'userOnline' => $userOnline
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
	
	 /**
     * Lists all Issue models.
     * @return mixed
     */
    public function actionIssue($status=1)
    {
		$searchModel = new IssueSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='all'){
			$queryParams['IssueSearch']=[
			];
		}
		else{
			$queryParams['IssueSearch']=[
				'status' => $status,
			];			
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['modified'=>SORT_DESC,'created'=>SORT_DESC];

        return $this->render('issue', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'status'=> $status,
        ]);
    } 
	
	/**
     * Displays a single Issue model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewIssue($id)
    {
        return $this->render('viewIssue', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Issue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateIssue()
    {
        $model = new Issue();

        if ($model->load(Yii::$app->request->post())){ 
			$attachment = UploadedFile::getInstances($model, 'attachment');
			
			$filenames = uniqid() . '.' . $ext;				
		$file->file_name = $filenames;
		$path = '';
		if(isset(Yii::$app->params['uploadPath'])){
			$path = Yii::$app->params['uploadPath'].'/'.$object.'/'.$object_id.'/';
		}
		else{
			$path = Yii::getAlias('@file').'/'.$object.'/'.$object_id.'/';
		}
		@mkdir($path, 0755, true);
		@chmod($path, 0755);
		if(isset($current_file)){
			@unlink($path . $current_file);
			@unlink($path . 'thumb_'. $current_file);
		}
		
		if(isset($filenames)){
			$instance_file->saveAs($path.$filenames);
			if ($resize) 
				\hscstudio\heart\helpers\Heart::imageResize($path.$filenames, $path. 'thumb_'. $filenames,148,198,0);
			if(!isset($file->name)) $file->name = $filenames;
			if(!isset($file->status)) $file->status=1;
			$file->save();

			$object_file->object = $object; 
			$object_file->object_id = $object_id; 
			$object_file->type = $type; 
			$object_file->file_id = $file->id; 
			$object_file->save();
			return true;
		}
		
			if ($model->validate()) {                
                $model->file->saveAs('uploads/' . $attachment->baseName . '.' . $attachment->extension);
            }
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				
            }
            else{
                Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
            }
            return $this->redirect(['view-issue', 'id' => $model->id]);
        } else {
            return $this->render('createIssue', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Issue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateIssue($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Data have updated.');
            }
            else{
                Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
            }
            return $this->redirect(['view-issue', 'id' => $model->id]);
        } else {
            return $this->render('updateIssue', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Issue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteIssue($id)
    {
        if($this->findModel($id)->delete()) {
            Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        }
        else{
            Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
        }
        return $this->redirect(['issue']);
    }

    /**
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelIssue($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    } 
}
