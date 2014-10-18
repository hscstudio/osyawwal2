<?php

namespace backend\modules\pusdiklat\evaluation\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\TrainingClassStudent;
use backend\models\TrainingClassStudentSearch;
use backend\models\TrainingClassStudentCertificate; 
use backend\models\TrainingClassStudentCertificateSearch; 
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingClassStudentController implements the CRUD actions for TrainingClassStudent model.
 */
class TrainingClassStudent2Controller extends Controller
{
	public $layout = '@hscstudio/heart/views/layouts/column2';	 
 	
	public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TrainingClassStudent models.
     * @return mixed
     */
    public function actionIndex($tb_training_id,$tb_training_class_id=0)
    {
		$trainingClass = \backend\models\TrainingClass::find()
			->where(['tb_training_id'=>$tb_training_id])
			->orderBy('id ASC')
			->all();
		$listTrainingClass = [];	
		foreach($trainingClass as $ts){
			if($tb_training_class_id==0){
				$tb_training_class_id=$ts->id;
			}
			$listTrainingClass[$ts->id]=$ts->class;
		}
		
		if($tb_training_class_id==0){
			Yii::$app->session->setFlash('warning', 'Anda harus membuat kelas terlebih dulu!');
			return $this->redirect([
				'./training-class/index', 'tb_training_id' => $tb_training_id
			]);
		}
		
        $searchModel = new TrainingClassStudentSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingClassStudentSearch']=[
					'tb_training_class_id' => $tb_training_class_id,
				];
        $queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		//$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'finish'=>SORT_ASC];
		
		$training = \backend\models\Training::findOne($tb_training_id);
		
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'training'=>$training,
			'listTrainingClass'=>$listTrainingClass,
			'tb_training_id'=>$tb_training_id,
			'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }

	/**
     * Lists all TrainingClassStudent models.
     * @return mixed
     */
    public function actionCertificate($tb_training_id,$tb_training_class_id=0)
    {
		$trainingClass = \backend\models\TrainingClass::find()
			->where(['tb_training_id'=>$tb_training_id])
			->orderBy('id ASC')
			->all();
		$listTrainingClass = [];	
		foreach($trainingClass as $ts){
			if($tb_training_class_id==0){
				$tb_training_class_id=$ts->id;
			}
			$listTrainingClass[$ts->id]=$ts->class;
		}
		
		if($tb_training_class_id==0){
			Yii::$app->session->setFlash('warning', 'Anda harus membuat kelas terlebih dulu!');
			return $this->redirect([
				'./training-class/index', 'tb_training_id' => $tb_training_id
			]);
		}
		
        $searchModel = new TrainingClassStudentSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingClassStudentSearch']=[
					'tb_training_class_id' => $tb_training_class_id,
				];
        $queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		//$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'finish'=>SORT_ASC];
		
		$training = \backend\models\Training::findOne($tb_training_id);
		
		
        return $this->render('certificate', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'training'=>$training,
			'listTrainingClass'=>$listTrainingClass,
			'tb_training_id'=>$tb_training_id,
			'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }
	

	
	 /** 
     * Displays a single TrainingClassStudentCertificate model. 
     * @param integer $id
     * @return mixed 
     */ 
   public function actionView($id,$tb_training_id,$tb_training_class_id) 
    { 
        $trainingClassStudent = $this->findModel($id);  
		$model = \backend\models\Student::findOne($trainingClassStudent->trainingStudent->tb_student_id);
		return $this->render('view', [ 
            'model' => $model, 
			'tb_training_id'=>$tb_training_id,
			'tb_training_class_id'=>$tb_training_class_id,
        ]); 
    } 

     /** 
     * Updates an existing TrainingClassStudentCertificate model. 
     * If update is successful, the browser will be redirected to the 'view' page. 
     * @param integer $id
     * @return mixed 
     */ 
    public function actionUpdate($id,$tb_training_id,$tb_training_class_id) 
    { 
        $trainingClassStudent = $this->findModel($id);  
		$model = \backend\models\Student::findOne($trainingClassStudent->trainingStudent->tb_student_id);
		
		$currentFiles=[];
        $currentFiles[0]=$model->photo;
		
        if ($model->load(Yii::$app->request->post())) { 
			$files[0] = \yii\web\UploadedFile::getInstance($model, 'photo');
			
			$model->photo=isset($currentFiles[0])?$currentFiles[0]:'';
			if(!empty($files[0])){
				$ext = end((explode(".", $files[0]->name)));
				$filenames[0] = uniqid() . '.' . $ext;				
				$model->photo = $filenames[0];
				$paths[0] = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$paths[0] = Yii::$app->params['uploadPath'].'/student/'.$model->id.'/';
				}
				else{
					$paths[0] = Yii::getAlias('@common').'/../files/student/'.$model->id.'/';
				}
				@mkdir($paths[0], 0755, true);
				@chmod($paths[0], 0755);
				if(isset($currentFiles[0])){
					@unlink($paths[0] . $currentFiles[0]);
					@unlink($paths[0] . 'thumb_'. $currentFiles[0]);
				}
			}
				
            if($model->save()){ 
				if(isset($filenames[0])){
					$files[0]->saveAs($paths[0].$filenames[0]);
					\hscstudio\heart\helpers\Heart::imageResize($paths[0].$filenames[0], $paths[0]. 'thumb_'. $filenames[0],148,198,0);
				}
                Yii::$app->session->setFlash('success', 'Data saved');                 
            } else { 
                // error in saving model 
                Yii::$app->session->setFlash('error', 'There are some errors'); 
            } 
			
			return $this->redirect([
				'view', 
				'id' => $trainingClassStudent->id,
				'tb_training_id'=>$tb_training_id,
				'tb_training_class_id'=>$tb_training_class_id,
			]); 
        } 
        else{ 
            //return $this->render(['update', 'id' => $model->tb_training_class_student_id]); 
            return $this->render('update', [ 
                'model' => $model, 
				'trainingClassStudent' => $trainingClassStudent,
				'tb_training_id'=>$tb_training_id,
				'tb_training_class_id'=>$tb_training_class_id,
            ]); 
        } 
    } 

    /** 
     * Finds the TrainingClassStudentCertificate model based on its primary key value. 
     * If the model is not found, a 404 HTTP exception will be thrown. 
     * @param integer $id
     * @return TrainingClassStudentCertificate the loaded model 
     * @throws NotFoundHttpException if the model cannot be found 
     */ 
    protected function findModel($id) 
    { 
        if (($model = TrainingClassStudent::findOne($id)) !== null) { 
            return $model; 
        } else { 
            throw new NotFoundHttpException('The requested page does not exist.'); 
        } 
    } 
     
    public function actionEditable() { 
        $model = new TrainingClassStudentCertificate; // your model can be loaded here 
        // Check if there is an Editable ajax request 
        if (isset($_POST['hasEditable'])) { 
            // read your posted model attributes 
            if ($model->load($_POST)) { 
                // read or convert your posted information 
                $model2 = $this->findModel($_POST['editableKey']); 
                $name=key($_POST['TrainingClassStudentCertificate'][$_POST['editableIndex']]); 
                $value=$_POST['TrainingClassStudentCertificate'][$_POST['editableIndex']][$name]; 
                $model2->$name = $value ; 
                $model2->save(); 
                // return JSON encoded output in the below format 
                echo \yii\helpers\Json::encode(['output'=>$value, 'message'=>'']); 
                // alternatively you can return a validation error 
                // echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'Validation error']); 
            } 
            // else if nothing to do always return an empty JSON encoded output 
            else { 
                echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'']); 
            } 
        return; 
        } 
        // Else return to rendering a normal view 
        return $this->render('view', ['model'=>$model]); 
    } 


}
