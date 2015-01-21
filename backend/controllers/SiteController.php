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
use yii\web\UploadedFile;
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
            $online = Online::findOne(Person::findOne(Yii::$app->user->identity->employee->person_id)->id);
            if (!empty($online)) {
                // bisa dikembangin lagi, ke arah yg lebih strict, 
                // jadi klo ada 2 user yg sama login di tempat yang berbeda, maka di blok, 
                // karena artinya akun user itu dipake orang lain
                $online->ip = Yii::$app->request->getUserIP();
                $online->time = date('Y-m-d H:i:s');
            }
            else {
                $online = new Online();
                $online->person_id = Yii::$app->user->identity->employee->person_id;
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
        $online = Online::findOne(Person::findOne(Yii::$app->user->identity->employee->person_id)->id);
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
			$name = Yii::$app->user->identity->employee->person->name;
			// force logout		
			Yii::$app->user->logout();
			// render form lockscreen
			$model = new LoginForm(); 
			$model->username = $username;	//set default value	
			return $this->render('lockScreen', [
				'model' => $model,
				'previous' => $previous,
				'name' => $name,
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
    public function actionIssue($status='all')
    {
		$searchModel = new IssueSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='all'){
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
		$model = $this->findModelIssue($id);
		$last_status = $model->getLastStatus($model->id);
		$modelNew = new Issue();
		if ($modelNew->load(Yii::$app->request->post())){ 
			$attachment = UploadedFile::getInstances($modelNew, 'attachment');	
			if(!empty($attachment[0])){			
				$ext = end((explode(".", $attachment[0]->name)));
				$filename = uniqid() . '.' . $ext;			
				$modelNew->attachment = $filename;
			}
			$modelNew->parent_id = $id;
			$modelNew->subject = 'comment';
			if(!empty($modelNew->label)){
				$modelNew->subject = 'label';
				/* $modelNew->content = $modelNew->label; */
			}
			
			if(!empty($modelNew->status)){
				$modelNew->status = ($modelNew->status==2)?0:1;
				$modelNew->subject = 'status';
				/* $modelNew->content = $modelNew->status; */
				$model->status = $modelNew->status;
				$model->save();
			}
			else{
				$modelNew->status = 1;
			}
			
			$modelNew->subject=strip_tags($modelNew->subject);
			$modelNew->content=strip_tags($modelNew->content);
			if($modelNew->validate() and $modelNew->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				if(!empty($attachment[0])){
					$path = '';
					if(isset(Yii::$app->params['uploadPath'])){
						$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR.'issue'.DIRECTORY_SEPARATOR.$modelNew->id.DIRECTORY_SEPARATOR;
					}
					else{
						$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'issue'.DIRECTORY_SEPARATOR.$modelNew->id.DIRECTORY_SEPARATOR;
					}
					@mkdir($path, 0755, true);
					@chmod($path, 0755);
					/* if(isset($current_file)){
						@unlink($path . $current_file);
					}	 */	
					$attachment[0]->saveAs($path.$filename);
				}
			}
            else{
				die(print_r($modelNew->errors));
                Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
            }
        } 
		
		$modelChildrens = \backend\models\Issue::find()
			->where([
				'parent_id' => $id,
			])
			->orderBy('id ASC')
			->all();
		
		$modelNew = new Issue();
		
        return $this->render('viewIssue', [
            'model' => $model,
            'modelNew' => $modelNew,
            'modelChildrens' => $modelChildrens,
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
			if(!empty($attachment[0])){
				$ext = end((explode(".", $attachment[0]->name)));
				$filename = uniqid() . '.' . $ext;				
				$model->attachment = $filename;
			}
			$model->parent_id = NULL;
			$model->label = NULL;
			$model->status = 1;
			$model->subject=strip_tags($model->subject);
			$model->content=nl2br(strip_tags($model->content));
			if($model->validate() and  $model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				if(!empty($attachment[0])){
					
					$path = '';
					if(isset(Yii::$app->params['uploadPath'])){
						$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR.'issue'.DIRECTORY_SEPARATOR.$model->id.DIRECTORY_SEPARATOR;
					}
					else{
						$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'issue'.DIRECTORY_SEPARATOR.$model->id.DIRECTORY_SEPARATOR;
					}
					@mkdir($path, 0755, true);
					@chmod($path, 0755);
					/* if(isset($current_file)){
						@unlink($path . $current_file);
					}	 */	
					$attachment[0]->saveAs($path.$filename);
				}
			}
            else{
                Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
            }
            return $this->redirect(['issue']);
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
    public function actionUpdateIssue($id,$lastId=0)
    {
        $model = $this->findModelIssue($id);
	
        if ($model->load(Yii::$app->request->post())) {
			$model->subject=strip_tags($model->subject);
			$model->content=nl2br(strip_tags($model->content));
            if($model->validate() and  $model->save()) {
                Yii::$app->getSession()->setFlash('success', 'Data have updated.');
            }
            else{
                Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
            }
			
			if($lastId==0){
				return $this->redirect(['issue']);
			}
			else{
				return $this->redirect([
					'view-issue',
					'id' => $lastId,
				]);
			}	
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
        if($this->findModelIssue($id)->delete()) {
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
            throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
        }
    } 
}
