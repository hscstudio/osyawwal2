<?php
namespace backend\modules\pusdiklat\evaluation\controllers;

use Yii;
use backend\models\Activity;
use backend\modules\pusdiklat\evaluation\models\TrainingActivitySearch;
use backend\models\Person;
use backend\models\ObjectPerson;
use backend\models\File;
use backend\models\ObjectFile;
use backend\models\Program;
use backend\models\ProgramSubject;
use backend\models\Reference;
use backend\models\ObjectReference;
use backend\models\Training;
use backend\models\TrainingStudentPlan;

use backend\models\TrainingClass;
use backend\modules\pusdiklat\evaluation\models\TrainingClassSearch;

use backend\models\TrainingClassSubject;
use backend\modules\pusdiklat\evaluation\models\TrainingClassSubjectSearch;

use backend\models\TrainingClassStudent;
use backend\modules\pusdiklat\evaluation\models\TrainingClassStudentSearch;

use backend\models\TrainingClassStudentCertificate;

use backend\models\TrainingStudent;
use backend\modules\pusdiklat\evaluation\models\TrainingStudentSearch;

use backend\models\TrainingSchedule;
use backend\modules\pusdiklat\evaluation\models\TrainingScheduleSearch;
use backend\modules\pusdiklat\evaluation\models\TrainingScheduleExtSearch;

use backend\models\Student;
use backend\modules\pusdiklat\evaluation\models\StudentSearch;

use backend\models\Room;
use backend\models\ActivityRoom;
use backend\modules\pusdiklat\evaluation\models\ActivityRoomSearch;
use backend\modules\pusdiklat\evaluation\models\ActivityRoomExtensionSearch;
use backend\modules\pusdiklat\evaluation\models\RoomSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use hscstudio\heart\helpers\Heart;
use yii\data\ArrayDataProvider;
/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class Activity2Controller extends Controller
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex($year='',$status='nocancel')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new TrainingActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = \yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	; 
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }



   
    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionProgramName($id){
		$model = Program::find()->where([
			'id'=>$id
		])
		->currentSatker()
		->active()
		->one();
		return $model->name;
	}
	
	/**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndexStudentPlan($year='',$status='nocancel')
    {
        if(empty($year)) $year=date('Y');
		$searchModel = new TrainingActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = \yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->active()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	;

        return $this->render('indexStudentPlan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewStudentPlan($id)
    {
		$model = $this->findModel($id);
		$training = Training::findOne(['activity_id'=>$model->id]);
		$trainingStudentPlan = TrainingStudentPlan::findOne(['training_id'=>$model->id]);
		if(null==$trainingStudentPlan){
			$trainingStudentPlan = new TrainingStudentPlan(
				['training_id'=>$model->id]
			);
		}
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		$renders['trainingStudentPlan'] = $trainingStudentPlan;
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('viewStudentPlan', $renders);
		else
			return $this->render('viewStudentPlan', $renders);
    }
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPic($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			//1213030100 CEK KD_UNIT_ORG 1213030100 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
			'organisation_1213040200'=>'PIC TRAINING ACTIVITY'
		];
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$object_people[$object_person] = ObjectPerson::find()
				->where([
					'object'=>'activity',
					'object_id' => $id,
					'type' => $object_person, 
				])
				->one();
			if($object_people[$object_person]==null){
				$object_people[$object_person]= new ObjectPerson(
					[
						'object'=>'activity',
						'object_id' => $id,
						'type' => $object_person, 
					]
				);
			}
			$renders[$object_person] = $object_people[$object_person];
		}	
		
        if (Yii::$app->request->post()) {
			foreach($object_people_array as $object_person=>$label){
				$person_id = (int)Yii::$app->request->post('ObjectPerson')[$object_person]['person_id'];
				Heart::objectPerson($object_people[$object_person],$person_id,'activity',$id,$object_person);
			}	
			Yii::$app->getSession()->setFlash('success', 'Pic have updated.');
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['view', 'id' => $model->id]);	
			}
			else{
				echo 'Pic have updated.';
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('pic', $renders);
            else
				return $this->render('pic', $renders);
        }
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionDashboard($id)
    {
		$model = $this->findModel($id);
		
        return $this->render('dashboard', [
            'model' => $model,
        ]);
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionProperty($id)
    {
        $model = $this->findModel($id);
		$program = Program::findOne($model->training->program_id);
		$subject = new ActiveDataProvider([
            'query' => ProgramSubject::find()
						->where([
							'program_id' => $model->training->program_id,
							'status'=>1,
						])
						->orderBy(['sort'=>SORT_ASC,]),
        ]);
		$document = new ActiveDataProvider([
            'query' => ObjectFile::find()
						->joinWith('file')
						->where([
							'object'=>'program',
							'object_id' => $model->training->program_id,
							'file.status'=>1,
							'type' => ['kap','gbpp','module'],
						])
						->orderBy(['type'=>SORT_ASC,]),
        ]);
		return $this->render('property', [
            'model' =>$model,
			'program' =>$program,
			'subject' =>$subject,
			'document' =>$document,
        ]);
    }
	
	 /**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionClass($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingClassSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where(['status'=>'1'])
			->andWhere([
				'not in', 'id', $subquery
			])
			->count();
		
		if (Yii::$app->request->post()){ 
			$student = Yii::$app->request->post()['student'];
			$baseon = 0;
			if(isset(Yii::$app->request->post()['baseon'])) $baseon = Yii::$app->request->post()['baseon'];
			$objectTrainingClass = TrainingClass::find()
					->where([
						'training_id' => $id,
					]);
			$trainingClassCount = (int) $objectTrainingClass->count();
			
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', 'Your request more than stock!');
			}
			else if($trainingClassCount==0){
				Yii::$app->session->setFlash('error', 'There is no class!');
			}
			else if($baseon==0 or count($baseon)==0){
				Yii::$app->session->setFlash('error', 'Select base on random!');
			}
			else{				
				$baseon = implode(',',$baseon);
				// select name, gender from person group by name, gender order by rand();
				$part = (int) ($student / $trainingClassCount);
				$residu = $student % $trainingClassCount;
				$trainingClasses = $objectTrainingClass->all();
				//die($part.' => '.$residu);
				$idx = 1;
				foreach($trainingClasses as $trainingClass){
					$limit = $part;
					if($idx == $trainingClassCount){
						$limit += $residu; 
					}
					$trainingStudents = TrainingStudent::find()
						->joinWith('student')
						->joinWith('student.person')
						->joinWith('student.person.unit')
						->where(['training_student.status'=>'1'])
						->andWhere([
							'not in', 'training_student.id', $subquery
						])
						->groupBy($baseon)
						->orderBy('rand()')
						->limit($limit)
						->asArray()
						->all();
					foreach ($trainingStudents as $trainingStudent){
						$trainingClassStudent = new TrainingClassStudent([
							'training_id'=>$id,
							'training_class_id'=>$trainingClass->id,
							'training_student_id'=>$trainingStudent['id'],
							'status'=>1
						]);
						$trainingClassStudent->save();
					}
					$idx++;
				}
				
				Yii::$app->session->setFlash('success', $student.' student added!');
				
				$subquery = TrainingClassStudent::find()
					->select('training_student_id')
					->where(['training_id' => $id]);
				 
				// fetch orders that are placed by customers who are older than 30  
				$trainingStudentCount = TrainingStudent::find()
					->where(['status'=>'1'])
					->andWhere([
						'not in', 'id', $subquery
					])
					->count();
			}
		}
		
        return $this->render('class', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'trainingStudentCount' => $trainingStudentCount
        ]);
    }
	

	
    /**
     * Finds the TrainingClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelClass($id)
    {
        if (($model = TrainingClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionClassSubject($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$searchModel = new TrainingClassSubjectSearch();
		$queryParams['TrainingClassSubjectSearch']=[				
			'training_class_id' =>$class_id,
			'status'=>1,
		];
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		
        return $this->render('classSubject', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
        ]);
    }
	
	 /**
     * Creates a new TrainingClassSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateClassSubject($id,$class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class
		
		$programSubjects= ProgramSubject::find()
			->where([
				'program_id' => $activity->training->program_id,
				'status'=>1
			])
			->all();
		$created=0;
		$failed=0;
		foreach($programSubjects as $programSubject){
			$classSubjectCount = TrainingClassSubject::find()
					->where([
						'training_class_id' => $class_id,
						'program_subject_id' => $programSubject->id,
					])
					->count();
			if ($classSubjectCount==0){
				$classSubject = new TrainingClassSubject([
						'training_class_id' => $class_id,
						'program_subject_id' => $programSubject->id,
						'status'=>1,
					]);
				if($classSubject->save()){
					$created++;
				}
				else{
					$failed++;
				}
			}
		}
		
		if($failed>0){
			Yii::$app->session->setFlash('warning', $created.' class subject created but '.$failed.' class failed');
		}
		else if($created==0){
			Yii::$app->session->setFlash('warning', 'No class subject created');
		}
		else{
			Yii::$app->session->setFlash('success', $created.' class subject created, awesome :)');
		}
		
		return $this->redirect(['class', 'id' => $id]);	
		
    }
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionStudent($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionChooseStudent($id)
    {
        $model = $this->findModel($id);
		$searchModel = new StudentSearch([
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('chooseStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	/**
     * Updates an existing TrainingStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateStudent($id,$student_id)
    {
        $model = $this->findModel($id);
		$training_student = TrainingStudent::find()
			->where([
				'training_id'=>$model->id,
				'student_id'=>$student_id,
			])
			->one();
        if ($training_student->load(Yii::$app->request->post())) {
            if($training_student->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['student', 'id' => $model->id]);
        } 
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('updateStudent', [
				'model' => $model,
				'training_student' => $training_student,
			]);
		else
			return $this->render('updateStudent', [
				'model' => $model,
				'training_student' => $training_student,
			]);
    }
	
	 /**
     * Finds the TrainingClass model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClass the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelStudent($id)
    {
        if (($model = TrainingStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 /**
     * Creates a new TrainingStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetStudent($id,$student_id)
    {
		$model = $this->findModel($id);
		$student = Student::findOne($student_id);
		$training_student = new TrainingStudent([
				'training_id'=>$model->id,
				'student_id'=>$student_id,
			]);

        if (Yii::$app->request->post()){ 
			if($training_student->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['choose-student', 'id' => $model->id]);
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('setStudent', [
					'model' => $model,
					'student' => $student,
					'training_student' => $training_student,
				]);
			else
				return $this->render('setStudent', [
					'model' => $model,
					'student' => $student,
					'training_student' => $training_student,
				]);
        }
    }

	/**
     * Deletes an existing TrainingClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteStudent($id, $student_id, $training_student_id)
    {
        $model = $this->findModelStudent($training_student_id);
		$model->delete();
		Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        return $this->redirect(['student', 'id' => $id]);
    }
	
	public function actionImportStudent($id){	
		$model = $this->findModel($id);
		$session = Yii::$app->session;
		$session->open();
		
		$start = time();
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');			
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; 
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 4;
					$err=[];
					$data = [];	
					$password = Yii::$app->security->generatePasswordHash('aku cinta bppk');
					$row=0;
					while(!empty($sheetData[$baseRow]['B'])){
						// GET DATA FROM EXCEL
						$nip = str_replace(' ','',trim($sheetData[$baseRow]['B']));
						$name = trim($sheetData[$baseRow]['C']);						
						$unit = trim($sheetData[$baseRow]['D']);
						$trainingStudent = TrainingStudent::find()
							->where([ 
								'training_id' => $model->id ,
								'student_id' => Person::find()
									->select('id')
									->where(['nip' => $nip])
									->orWhere(['nid' => $nip])
									->column(),
							])
							->exists();
						if(!$trainingStudent){						
							$row++;
							$person_id = 0;
							$person = Person::find()
										->where(['nip' => $nip])
										->orWhere(['nid' => $nip])
										->one();
							if(null!=$person){
								$person_id = $person->id;
							}
							
							$student_id = 0;
							$student = Student::find()
								->where([ 
									'person_id' => Person::find()
										->select('id')
										->where(['nip' => $nip])
										->orWhere(['nid' => $nip])
										->column(),
								])
								->one();					
							if(null!=$student){
								$student_id = $student->person_id;
							}
							
							$reference_id = 0;
							$reference = Reference::find()
								->where([
									'type'=>'unit',
									'value'=>$unit
								])
								->one();
							if(null!=$reference){
								$reference_id = $reference->id;
							}
							
							$object_reference_id = 0;
							$object_reference = ObjectReference::find()
								->where([
									'object' => 'person',
									'object_id' => $person->id,
									'type' => 'unit',
								])
								->one();
							if(null!=$object_reference){
								$object_reference_id = 1;
							}
						
							
							$data[$row]=[
								'row'	=>	$row,
								'nip'	=>	$nip,
								'name'	=>	$name,
								'unit'	=>	$unit,
								'unit_id' => $reference_id,
								'student_id'=>	$student_id,
								'person_id'=>	$person_id,
								'password' => $password,
								'object_reference_id' => $object_reference_id,
							];							
						}
						$baseRow++;
					}	
					
					// SESSION BUG MAYBE :)
					$data[$row+1]=[];
					$session['data'] = $data;						
					Yii::$app->session->setFlash('success', ($row).' row affected');
					
					
					/* if(!empty($err)){
						Yii::$app->session->setFlash('warning', 'There are error: <br>'.implode('<br>',$err));
					} */	
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
					return $this->redirect([
						'student', 'id' => $id
					]);
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
				return $this->redirect([
					'student', 'id' => $id
				]);
			}
		}
		
		if (Yii::$app->request->post() and isset(Yii::$app->request->post()['selection'])){ 
			$selections = Yii::$app->request->post()['selection'];
			$names = Yii::$app->request->post()['name'];
			$nips = Yii::$app->request->post()['nip'];
			$unit_ids = Yii::$app->request->post()['unit_id'];
			$student_ids = Yii::$app->request->post()['student_id'];
			$person_ids = Yii::$app->request->post()['person_id'];
			$passwords = Yii::$app->request->post()['password'];
			$object_reference_ids =  Yii::$app->request->post()['object_reference_id'];
			
			/* $person_values = [];
			$student_values = [];
			$training_student_values = []; */
			foreach($selections as $selection){	
				/* if($persons[$selection]==0)
					$person_values[] = [$names[$selection],$nips[$selection],$nips[$selection],1];
				if($students[$selection]==0)
					$student_values[] = [$nips[$selection]]; */	
				$person_id = $person_ids[$selection];
				$name = $names[$selection];
				$nip = $nips[$selection];
				$unit_id = $unit_ids[$selection];
				$student_id = $student_ids[$selection];
				$object_reference_id = $object_reference_ids[$selection];
				// PROVIDE DATA FROM NIP
				$birthday = '';
				$gender = '';
				$status = 1;
				
				if(strlen($nip)==18){
					$birthday = substr($nip,0,4) .'-'. substr($nip,4,2) .'-'. substr($nip,6,2);
					$gender = substr($nip,14,1);
				}
				$password = $passwords[$selection];
				if($person_id==0){
					$person = new Person([
						'name' => $name,
						'nid' => $nip,
						'nip' => $nip,
						'birthday' => $birthday,
						'gender' => $gender,
						'status' => 1,
					]);
					if($person->save()){
						$person_id = $person->id;
					}
				}
				
				if($person_id>0){
					if($student_id==0){
						$student = new Student([
							'person_id' => $person_id,
							'username'=>$nip,
							'password_hash'=>$password,
							'status' => 1,
						]);
						if($student->save()){
							$student_id = $student->person_id;
						}
					}
					
					if($student_id>0){
						$training_student = new TrainingStudent([
							'student_id' => $student_id,
							'training_id' => $id,
							'status' => 1,
						]);
						$training_student->save();
					}
					
					if($unit_id>0){
						/* if($object_reference_id==1){
							$object_reference = ObjectReference::find()
								->where([
									'object' => 'person',
									'object_id' => $person_id,
									'type' => 'unit',
									'reference_id' => $unit_id,
								]);
						}
						else{
							$object_reference = new ObjectReference([
								'object' => 'person',
								'object_id' => $person_id,
								'type' => 'unit',
								'reference_id' => $unit_id,
							]);
						}
						$object_reference->save(); */
					}
				} 
			}
			
			unset($session['data']);
			
			/*
			if(count($person_values)>0)
				Yii::$app->getDb()->createCommand()
					->batchInsert('person', ['name', 'nip', 'nid','status'], $person_values)
					->execute();
			
			if(count($student_values)>0)
				Yii::$app->getDb()->createCommand()
					->batchInsert('student', ['person_id','username','password_hash','status'], $student_values)
					->execute();
			 Yii::$app->getDb()->createCommand()
				->batchInsert('training_student', ['nip'], $person_values);
				->execute(); */	
			/* Yii::$app->getDb()->createCommand()
				->batchInsert('tbl_user', ['name', 'status'],
					[],
					[],
					
				])
				->execute(); */

		}
		/* 
		Please read guide of upload https://github.com/yiisoft/yii2/blob/master/docs/guide/input-file-upload.md
		maybe I do mistake :)
		*/	
		

		
		if ($session->has('data')) {
			$data = $session['data'];
			$dataProvider = new ArrayDataProvider([
				'allModels' => $data,
				/* 'sort' => [
					'attributes' => ['row', 'nip', 'name', 'unit', 'exists'],
				], */
				'pagination' => [
					'pageSize' => 'all',
				],
			]);
			
			return $this->render('importStudent', [
				'model' => $model,
				'dataProvider' => $dataProvider,
			]);
		}
		else{
			return $this->redirect([
				'student', 'id' => $id
			]);
		}
	}
	
	/**
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionClassStudent($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$searchModel = new TrainingClassStudentSearch();
		$queryParams['TrainingClassStudentSearch']=[				
			'training_class_id' =>$class_id,
			'training_class_student.status'=>1,
		];
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where(['status'=>1])
			->andWhere([
				'not in', 'id', $subquery
			])
			->count();
		
		if (Yii::$app->request->post()){ 
			$student = Yii::$app->request->post()['student'];
			$baseon = 0;
			if(isset(Yii::$app->request->post()['baseon'])) $baseon = Yii::$app->request->post()['baseon'];
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Your request more than stock!');
			}
			else if($baseon==0 or count($baseon)==0){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Select base on random!');
			}
			else{				
				$baseon = implode(',',$baseon);
				// select name, gender from person group by name, gender order by rand();
				$trainingStudents = TrainingStudent::find()
					->joinWith('student')
					->joinWith('student.person')
					->joinWith('student.person.unit')
					->where(['training_student.status'=>'1'])
					->andWhere([
						'not in', 'training_student.id', $subquery
					])
					->groupBy($baseon)
					->orderBy('rand()')
					->limit($student)
					->asArray()
					->all();
				foreach ($trainingStudents as $trainingStudent){
					$trainingClassStudent = new TrainingClassStudent([
						'training_id'=>$id,
						'training_class_id'=>$class_id,
						'training_student_id'=>$trainingStudent['id'],
						'status'=>1
					]);
					$trainingClassStudent->save();
				}
				Yii::$app->session->setFlash('success', $student.' student added!');
				
				$subquery = TrainingClassStudent::find()
					->select('training_student_id')
					->where(['training_id' => $id]);
				 
				// fetch orders that are placed by customers who are older than 30  
				$trainingStudentCount = TrainingStudent::find()
					->where(['status'=>'1'])
					->andWhere([
						'not in', 'id', $subquery
					])
					->count();
			}
		}
		
        return $this->render('classStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
			'trainingStudentCount' => $trainingStudentCount
        ]);
    }

    /**
     * Creates a new TrainingClassStudentCertificate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCertificateClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        $model = new \backend\models\TrainingClassStudentCertificate([
            'training_class_student_id'=> $training_class_student_id,
            'date'=>date('Y-m-d'),
            'status' => 1,
        ]);

        if ($model->load(Yii::$app->request->post())){
            //
            $trainingClassStudent = \backend\models\TrainingClassStudent::findOne($training_class_student_id);
            $student = $trainingClassStudent->trainingStudent->student;
            $person = $student->person;
            $objectPerson = \backend\models\ObjectPerson::find()
                ->where([
                    'object'=>'person',
                    'object_id'=>$person->id,
                    'type'=>'graduate',
                ])
                ->one();
            if(null!=$objectPerson) $model->graduate=$objectPerson->reference_id;
            $model->graduate_desc=$person->graduate_desc;
            $model->position=$person->position;
            $model->position_desc=$person->position_desc;

            $model->eselon3=$student->eselon3;
            $model->eselon4=$student->eselon4;
            $model->satker=$student->satker;
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Certificate created');
            }
            else{
                //die(print_r($model->errors));
                Yii::$app->session->setFlash('error', 'Unable create there are some error');
            }
            return $this->redirect([
                'class-student',
                'id' => $id,
                'class_id'=>$class_id,
            ]);
        } else {
            return $this->render('createCertificate', [
                'model' => $model,
                'activity'=>$activity,
                'class'=>$class,
            ]);
        }
    }

    /**
     * Updates an existing TrainingClassStudentCertificate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateCertificateClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        $model = $this->findModelClassStudentCerificate($training_class_student_id);

        if ($model->load(Yii::$app->request->post())){
            //
            $trainingClassStudent = \backend\models\TrainingClassStudent::findOne($training_class_student_id);
            $student = $trainingClassStudent->trainingStudent->student;
            $person = $student->person;
            $objectPerson = \backend\models\ObjectPerson::find()
                ->where([
                    'object'=>'person',
                    'object_id'=>$person->id,
                    'type'=>'graduate',
                ])
                ->one();
            if(null!=$objectPerson) $model->graduate=$objectPerson->reference_id;
            $model->graduate_desc=$person->graduate_desc;
            $model->position=$person->position;
            $model->position_desc=$person->position_desc;

            $model->eselon3=$student->eselon3;
            $model->eselon4=$student->eselon4;
            $model->satker=$student->satker;
            if($model->save()) {
                Yii::$app->session->setFlash('success', 'Certificate update');
            }
            else{
                //die(print_r($model->errors));
                Yii::$app->session->setFlash('error', 'Unable update there are some error');
            }
            return $this->redirect([
                'class-student',
                'id' => $id,
                'class_id'=>$class_id,
            ]);
        } else {
            return $this->render('updateCertificate', [
                'model' => $model,
                'activity'=>$activity,
                'class'=>$class,
            ]);
        }
    }


    /**
     * Deletes an existing TrainingClassStudentCertificate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteCertificateClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        $model = $this->findModelClassStudentCerificate($training_class_student_id);

        if($model->delete()) {
            Yii::$app->session->setFlash('success', 'Certificate deleted');
        }
        else{
            //die(print_r($model->errors));
            Yii::$app->session->setFlash('error', 'Unable delete there are some error');
        }
        return $this->redirect([
            'class-student',
            'id' => $activity->id,
            'class_id'=>$class->id,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        $class_student = $this->findModelClassStudent($training_class_student_id);
        $student = $class_student->trainingStudent->student;
        $person = $student->person;
        $renders = [];
        $renders['activity'] = $activity;
        $renders['class'] = $class;
        $renders['class_student'] = $class_student;
        $renders['person'] = $person;
        $renders['student'] = $student;

        $object_references_array = [
            'unit'=>'Unit','religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
        $renders['object_references_array'] = $object_references_array;
        foreach($object_references_array as $object_reference=>$label){
            $object_references[$object_reference] = ObjectReference::find()
                ->where([
                    'object'=>'person',
                    'object_id' => $person->id,
                    'type' => $object_reference,
                ])
                ->one();
            if($object_references[$object_reference]==null){
                $object_references[$object_reference]= new ObjectReference();
            }
            $renders[$object_reference] = $object_references[$object_reference];
        }

        $currentFiles=[];
        $object_file_array = [
            'photo'=>'Photo 4x6','sk_cpns'=>'SK CPNS','sk_pangkat'=>'SK Pangkat'];
        $renders['object_file_array'] = $object_file_array;
        foreach($object_file_array as $object_file=>$label){
            $currentFiles[$object_file] = '';
            $object_files[$object_file] = ObjectFile::find()
                ->where([
                    'object'=>($object_file=='photo')?'person':'person',
                    'object_id' => $person->id,
                    'type' => $object_file,
                ])
                ->one();

            if($object_files[$object_file]!=null){
                $files[$object_file] = File::find()
                    ->where([
                        'id'=>$object_files[$object_file]->file_id,
                    ])
                    ->one();
                $currentFiles[$object_file]=$files[$object_file]->file_name;
            }
            else{
                $object_files[$object_file]= new ObjectFile();
                $files[$object_file] = new File();
            }
            $renders[$object_file] = $object_files[$object_file];
            $renders[$object_file.'_file'] = $files[$object_file];
        }

        if ($person->load(Yii::$app->request->post())) {
            if($person->save()) {
                foreach($object_references_array as $object_reference=>$label){
                    $reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
                    Heart::objectReference($object_references[$object_reference],$reference_id,'person',$person->id,$object_reference);
                }

                $uploaded_files = [];
                foreach($object_file_array as $object_file=>$label){
                    $uploaded_files[$object_file] = \yii\web\UploadedFile::getInstance($files[$object_file], 'file_name['.$object_file.']');
                    if(null!=$uploaded_files[$object_file]){
                        //upload(
                        //$instance_file, $object='person', $object_id, $file, $object_file,
                        //$type='photo', $resize=false,$current_file='',$thumb = false){
                        \hscstudio\heart\helpers\Heart::upload(
                            $uploaded_files[$object_file],
                            'person',
                            $person->id,
                            $files[$object_file],
                            $object_files[$object_file],
                            $object_file,
                            ($object_file=='photo')?true:false,
                            $currentFiles[$object_file],
                            ($object_file=='photo')?true:false
                        );
                    }
                }
                Yii::$app->getSession()->setFlash('success', 'Update Person Success');

                $student->load(Yii::$app->request->post());
                if(strlen($student->new_password)>3){
                    $student->password = $student->new_password;
                }
                if ($student->save()){
                    Yii::$app->getSession()->setFlash('success', 'Update Person & Student Success');
                }
                return $this->redirect([
                    'class-student',
                    'id' => $activity->id,
                    'class_id'=>$class->id,
                ]);
            }
            else{
                Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
                return $this->render('updateClassStudent', $renders);
            }

        } else {
            return $this->render('updateClassStudent', $renders);
        }
    }

    /**
     * Finds the TrainingClassStudentCertificate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClassStudentCertificate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelClassStudentCerificate($id)
    {
        if (($model = TrainingClassStudentCertificate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findModelClassStudent($id)
    {
        if (($model = TrainingClassStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	


	/**
     * Lists all Room models.
     * @return mixed
     */
    public function actionClassSchedule($id, $class_id,$start="",$end="")
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	

		if(empty($start)){
			$start = $class->training->activity->start;
		}
		
		if(empty($end) or $end<$start){
			$end = $start;
		}
		$searchModel = new TrainingScheduleSearch();
		$queryParams['TrainingScheduleSearch']=[				
			'training_class_id'=>$class_id,
			'startDate' => date('Y-m-d',strtotime($start)),
			'endDate'=>date('Y-m-d',strtotime($start)),
		];		
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];

		$trainingScheduleExtSearch = new TrainingScheduleExtSearch([
			'startDate' => date('Y-m-d',strtotime($start)),
			'scheduleDate' => date('Y-m-d',strtotime($start)),
		]);
		
		// GET ALL TRAINING YEAR
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('classSchedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity'=>$activity,
				'class'=>$class,
				'start'=>$start,
				'end'=>$end,
				'trainingScheduleExtSearch' => $trainingScheduleExtSearch
			]);
		}
		else{
			return $this->render('classSchedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity'=>$activity,
				'class'=>$class,
				'start'=>$start,
				'end'=>$end,
				'trainingScheduleExtSearch' => $trainingScheduleExtSearch
			]);
		}
    }
	
	public function actionClassScheduleMaxTime($id, $class_id,$start=""){
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	

		if(empty($start)){
			$start = $class->training->activity->start;
		}
		
		$start = date('Y-m-d',strtotime($start)); 
		$end =  date('Y-m-d',(strtotime($start)+ 60*60*24));
		$trainingSchedule = TrainingSchedule::find()
				->where('
					(
						start >= :start AND 
						end <= :end
					)
					AND 
						training_class_id = :class_id
					AND
					status = :status
				',
				[
					':start' => $start,
					':end' => $end,
					':class_id' => $class_id,
					':status' => 1,
				])
				->orderBy('end DESC')
				->one();
		if($trainingSchedule!=null)
			return date('H:i',strtotime($trainingSchedule->end));		
		else
			return '08:00';		
	}
	
	public function actionAddActivityClassSchedule($id, $class_id) 
    { 	
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			// PREPARING DATA
			$post = Yii::$app->request->post('TrainingScheduleExtSearch');
			$start = date('Y-m-d H:i',strtotime($post['startDate'].' '.$post['startTime'])); 
			$training_class_subject_id = $post['training_class_subject_id'];
			$activity_room_id =  $post['activity_room_id'];
			$activity_name = "";
			$pic = "";
			$hours = 0;
			$minutes = 0;
			
			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			if ($satker_id!=$activity->satker_id){
				die('|0|You have not privileges');
			}
			
			if(empty($training_class_subject_id) or $training_class_subject_id==0){
				die('|0|You must select activity!');
			}
			else if ($training_class_subject_id>0){
				$hours = $post['hours'];
				if($hours>0){
					$minutes = (int)($hours * 45);
				}
				else{
					die('|0|Hours have more than 0');
				}
			}
			else{
				$minutes = (int)$post['minutes'];
				if($minutes>0){
					$activity_name = $post['activity'];
					$pic = $post['pic'];
				}
				else{
					die('|0|Minutes have more than 0');
				}
			}
			$training_class_subject_id=(int)$training_class_subject_id;
			$end = date('Y-m-d H:i',strtotime($start)+($minutes*60));
			$activity_room_id=(int)$activity_room_id;
			// CHECKING CONSTRAIN TIME
			$startSearch = $start + 1; // [08:00 - 09:00, 09:00 - 10:00] not exact between :)
			$endSearch = $end - 1;
			$trainingSchedule = TrainingSchedule::find()
				->where('
					((start between :start AND :end)
						OR (end between :start AND :end))
					AND 
						training_class_id = :training_class_id
					AND
					status = :status
				',
				[
					':start' => $startSearch,
					':end' => $endSearch,
					':training_class_id' => $class_id,
					':status' => 1,
				]);
				
			// IS NOT CONSTRAIN			
			if($trainingSchedule->count()==0){ 
				// PREPARING SAVE
				$model = new TrainingSchedule(); 
				$model->training_class_id=$class_id;
				$model->training_class_subject_id = $training_class_subject_id;
				$model->activity_room_id = $activity_room_id;		
				$model->activity = $activity_name;
				$model->pic = $pic;
				$model->hours = $hours;
				$model->start = $start;
				$model->end = $end;
				$model->status = 1;
			
				if($model->save()) {
					Yii::$app->session->setFlash('success', 'Activity have Added');
					die('|1|Activity have Added|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($end)));
					
				}
				else{
					die('|0|There are some error');
				}
			}
			else{
				die('|0|Constrain time, please change time!');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		}
    } 
	
	public function actionDeleteActivityClassSchedule($id, $class_id, $schedule_id) 
    { 	
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			if ($satker_id!=$activity->satker_id){
				die('|0|You have not privileges');
			}
			
			$trainingSchedule = \backend\models\TrainingSchedule::find()->where([
				'id'=>$schedule_id,
				'training_class_id'=>$class_id,
			])->one();
			$start = $trainingSchedule->start;
			if($trainingSchedule->delete()) {
				Yii::$app->session->setFlash('success', 'Delete activity success');
				die('|1|Activity have deleted|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($start)));
			}
			else{
				die('|0|There are some error');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
	}
	
	public function actionRoomClassSchedule($id, $class_id, $schedule_id) 
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			$model = TrainingSchedule::findOne($schedule_id);
			$dataRoom = [];
			$activityRoom = ActivityRoom::find()
				->where([
					'activity_id'=>$activity->id,
					'status'=>2 // Approved only	
				])
				->all();
			foreach ($activityRoom as $ar){
				$dataRoom[$ar->room_id] = $ar->room->name;
			}
			if (Yii::$app->request->post()) {	
				$model->activity_room_id = Yii::$app->request->post('TrainingSchedule')['activity_room_id'];
				if($model->save()) {
					Yii::$app->session->setFlash('success', 'Room have set');
					die('|1|Room have set|'.date('Y-m-d',strtotime($model->start)).'|'.date('H:i',strtotime($model->end)));
					
				}
				else{
					die('|0|There are some error');
				}
			}
			else{
				return $this->renderAjax('roomSchedule', [
					'model' => $model,
					'activity' => $activity,
					'class' => $class,
					'dataRoom' => $dataRoom
				]);
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
    }
	
	public function actionTrainerClassSchedule($id, $class_id, $schedule_id) 
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		if (Yii::$app->request->isAjax){
			$model = new \backend\models\TrainingScheduleTrainer();
			$trainingSchedule = TrainingSchedule::findOne($schedule_id);
			$trainingSubjectTrainerRecommendation =\backend\models\TrainingSubjectTrainerRecommendation::find()
			->where([
				'training_id'=>$activity->id,
				'program_subject_id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
				'status'=>1,
			])
			->groupBy('type')
			->all();
			if ($model->load(Yii::$app->request->post())) {
				$trainer_id_array = Yii::$app->request->post('trainer_id_array');
				
				$insert=0;
				foreach($trainer_id_array as $trainer_id=>$on){
					$model2 = new \backend\models\TrainingScheduleTrainer();
					$model2->training_schedule_id = $schedule_id;
					$trainingSubjectTrainerRecommendation=\backend\models\TrainingSubjectTrainerRecommendation::find()
					->where([
						'training_id'=>$activity->id,
						'program_subject_id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
						'trainer_id'=>$trainer_id,
						'status'=>1,
					])
					->one();
					$model2->type=$trainingSubjectTrainerRecommendation->type;
					$model2->trainer_id = $trainer_id;
					$model2->status = 1;
					if($model2->save()) {
						$insert++;
					}
					else{
						die('|0|There are some error'.print_r($model2->errors));
					}
				}
				
				if($insert>0) {
					Yii::$app->session->setFlash('success', 'Trainer have added');
					die('|1|Trainer have added|'.date('Y-m-d',strtotime($trainingSchedule->start)).'|'.date('H:i',strtotime($trainingSchedule->end)));
				}
				else{
					die('|0|No trainer added');
				} 
			}
			else{				
				return $this->renderAjax('trainerSchedule', [
					'model' => $model,
					'activity' => $activity,
					'class' => $class,
					'trainingSchedule' => $trainingSchedule,
					'trainingSubjectTrainerRecommendation' => $trainingSubjectTrainerRecommendation,
					
				]);
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
    }
	


    public function actionPrintFrontendCertificate($id,$class_id,$filetype='docx'){

        try {
            $templates=[
                'docx'=>'ms-word.docx',
                'odt'=>'open-document.odt',
                'xlsx'=>'ms-excel.xlsx'
            ];
            // Initalize the TBS instance
            $OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
            // Change with Your template kaka
            //$template = Yii::getAlias('@common').'/extensions/opentbs-template/'.$templates[$filetype];
            $path = '';
            if(isset(Yii::$app->params['uploadPath'])){
                $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
            }
            else{
                $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
            }
            $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
            $template = $template_path . 'skpp01.docx';
            $type_certificate = "SERTIFIKAT";
            $type_certificate_id = Yii::$app->request->post('type_certificate');
            if($type_certificate_id == 2){
                $template = $template_path . 'skpp01B.docx';
            }
            else if($type_certificate_id == 1){
                $type_certificate = "Surat Tanda Tamat Pendidikan dan Pelatihan";
            }
            $OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
            //$OpenTBS->VarRef['modelName']= "TrainingClassStudentCertificate";

            $data = [];
            $idx = 0;
            $number="";
            $trainingClassStudentCertificates = \backend\models\TrainingClassStudentCertificate::find()
                ->where([
                    'training_class_student_id' => TrainingClassStudent::find()
                        ->where([
                            'training_id'=>$id,
                            'training_class_id'=>$class_id,
                            'status'=>1,
                        ])
                        ->column(),
                    'status'=>1,
                ])
                ->all();

            $name_signer = '';
            $nip_signer = '';
            $position_signer = 'Kepala Pusat Pendidikan dan Pelatihan ';
            $city_signer = '';

            foreach($trainingClassStudentCertificates as $trainingClassStudentCertificate){
                if($idx==0){
                    $numbers = explode('-',$trainingClassStudentCertificate->trainingClassStudent->training->number);
                    // 2014-03-00-2.2.1.0.2 to /2.3.1.2.138/07/00/2014
                    $number = '';
                    $seri = '';
                    if(isset($numbers[3]) and strlen($numbers[3])>3){
                        $number .= '/'.$numbers[3];
                        $seri = '/'.$numbers[3];
                    }
                    if(isset($numbers[1]) and strlen($numbers[1])==2){
                        $number .= '/'.$numbers[1];
                    }
                    if(isset($numbers[2]) and strlen($numbers[2])==2){
                        $number .= '/'.$numbers[2];
                    }
                    if(isset($numbers[0]) and strlen($numbers[0])==4){
                        $number .= '/'.$numbers[0];
                    }

                    $program = \backend\models\ProgramHistory::find()
                        ->where([
                            'id'=>$trainingClassStudentCertificate->trainingClassStudent->training->program_id,
                            'revision'=>$trainingClassStudentCertificate->trainingClassStudent->training->program_revision,
                        ])
                        ->one();

                    $type_graduate = "TELAH MENGIKUTI";
                    if($program->test==1){
                        $type_graduate = "L U L U S";
                    }

                    if($program->hours==(int)$program->hours){
                        $hours_program = (int)$program->hours;
                    }
                    else{
                        $hours_program = str_replace('.',',',$program->hours);
                    }

                    $name_executor = $trainingClassStudentCertificate->trainingClassStudent->training->satker->name;
                    $name_training = Yii::$app->request->post('name_training');
                    $location_training = Yii::$app->request->post('location_training');

                    $signer = (int)Yii::$app->request->post('signer');
                    $employee = \backend\models\Employee::findOne($signer);
                    if(!null == $employee){
                        $name_signer = $employee->person->name;
                        $nip_signer = $employee->person->nip;
                        $position_signer = $employee->positionDesc;
                        $city_signer = Yii::$app->request->post('city_signer');
                    }
                    $idx++;
                }

                $student = $trainingClassStudentCertificate->trainingClassStudent->trainingStudent->student;
                $instansi = $student->unit->name;
                if($student->satker>1){
                    $eselon = 'eselon'.$student->satker;
                    $instansi =$student->$eselon;
                }

                if (file_exists($path.'student/'.$student->id.'/'.$student->photo) and strlen($student->photo)>3)
                    $photo = $path.'student/'.$student->id.'/thumb_'.$student->photo;

                $data[] = [
                    // CERTIFICATE DATA
                    'type_certificate'=>$type_certificate,
                    'type_graduate'=>$type_graduate,
                    'seri'=>$trainingClassStudentCertificate->seri.$seri,
                    'number'=>$trainingClassStudentCertificate->number.$number,
                    'year_training'=>date('Y',strtotime($trainingClassStudentCertificate->trainingClassStudent->training->activity->start)),
                    'date_training'=>\hscstudio\heart\helpers\Heart::twodate(
                        date('Y-m-d',strtotime($trainingClassStudentCertificate->trainingClassStudent->training->activity->start)),
                        date('Y-m-d',strtotime($trainingClassStudentCertificate->trainingClassStudent->training->activity->end)),
                        0, // month type
                        0, // year type
                        ' ', // delimiter
                        ' sampai dengan '
                    ),
                    'hours_training'=>$hours_program,
                    'name_executor'=>$name_executor,
                    'name_training'=>$name_training,
                    'location_training'=>$location_training,
                    // STUDENT DATA
                    'name_student'=>$student->name,
                    'nip_student'=>$student->nip,
                    'born_student'=>$student->born,
                    'birthDay_student'=>$student->birthDay,
                    'rankClass_student'=>$student->rankClass->name,
                    'position_student'=>$student->positionDesc,
                    'satker_student'=>$instansi,
                    'photo_student'=>$photo,
                    //SIGNER DATA
                    'city_signer'=>$city_signer,
                    'date_signer'=>\hscstudio\heart\helpers\Heart::twodate($trainingClassStudentCertificate->date),
                    'position_signer'=>$position_signer,
                    'name_signer'=>$name_signer,
                    'nip_signer'=>$nip_signer,
                ];
            }
            $OpenTBS->MergeBlock('data', $data);
            // Output the result as a file on the server. You can change output file
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
            exit;
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'class-student',
            'id'=>$id,
            'class_id'=>$class_id,
        ]);
    }

    public function actionPrintValueCertificate($tb_training_id,$tb_training_class_id,$filetype='docx'){

        try {
            // Initalize the TBS instance
            $OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
            // Change with Your template kaka
            //$template = Yii::getAlias('@common').'/extensions/opentbs-template/'.$templates[$filetype];
            $path = '';
            if(isset(Yii::$app->params['uploadPath'])){
                $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
            }
            else{
                $path = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
            }
            $template_path = $path . 'templates'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
            $template = $template_path . 'skpp03.docx';

            $OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
            //$OpenTBS->VarRef['modelName']= "TrainingClassStudentCertificate";

            $idx = 0;
            $number="";
            $trainingClassStudentCertificates = \backend\models\TrainingClassStudentCertificate::find()
                ->where([
                    'tb_training_class_student_id' => TrainingClassStudent::find()
                        ->where([
                            'tb_training_id'=>$tb_training_id,
                            'tb_training_class_id'=>$tb_training_class_id,
                            'status'=>1,
                        ])
                        ->column(),
                    'status'=>1,
                ])
                ->all();

            $name_signer = '';
            $nip_signer = '';
            $position_signer = 'Kepala Pusat Pendidikan dan Pelatihan ';
            $city_signer = '';

            foreach($trainingClassStudentCertificates as $trainingClassStudentCertificate){
                if($idx==0){
                    $numbers = explode('-',$trainingClassStudentCertificate->trainingClassStudent->training->number);
                    // 2014-03-00-2.2.1.0.2 to /2.3.1.2.138/07/00/2014
                    $number = '';
                    $seri = '';
                    if(isset($numbers[3]) and strlen($numbers[3])>3){
                        $number .= '/'.$numbers[3];
                        $seri = '/'.$numbers[3];
                    }
                    if(isset($numbers[1]) and strlen($numbers[1])==2){
                        $number .= '/'.$numbers[1];
                    }
                    if(isset($numbers[2]) and strlen($numbers[2])==2){
                        $number .= '/'.$numbers[2];
                    }
                    if(isset($numbers[0]) and strlen($numbers[0])==4){
                        $number .= '/'.$numbers[0];
                    }
                }
                $idx++;
                $student = $trainingClassStudentCertificate->trainingClassStudent->trainingStudent->student;
                $instansi = $student->unit->name;
                if($student->satker>1){
                    $eselon = 'eselon'.$student->satker;
                    $instansi =$student->$eselon;
                }
                $data1[] = [
                    'idx' => $idx,
                    'number'=>$trainingClassStudentCertificate->number.$number,
                    'name_student'=>$student->name,
                    'nip_student'=>$student->nip,
                    'satker_student'=>$instansi,
                ];
            }
            $OpenTBS->MergeBlock('data1', $data1);

            $training = \backend\models\Training::findOne($tb_training_id);

            $programSubjectHistories = \backend\models\ProgramSubjectHistory::find()
                ->where([
                    'tb_program_id' => $training->tb_program_id,
                    'revision' => $training->tb_program_revision,
                    'status'=>1,
                ])
                ->all();
            $idx1 = 1;
            $idx2 = 1;
            $idx3 = 1;
            $data2=[];
            $data3=[];
            $data4=[];
            foreach($programSubjectHistories as $programSubjectHistory){
                if(in_array($programSubjectHistory->ref_subject_type_id,[2,3])){
                    $data4[] = [
                        'no' => $idx3++.'.',
                        'name_subject'=>$programSubjectHistory->name,
                    ];
                }
                else if($programSubjectHistory->test==1){
                    $data2[] = [
                        'no' => $idx1++.'.',
                        'name_subject'=>$programSubjectHistory->name,
                    ];
                }
                else{
                    $data3[] = [
                        'no' => $idx2++.'.',
                        'name_subject'=>$programSubjectHistory->name,
                    ];
                }
            }
            $OpenTBS->MergeBlock('data2', $data2);
            $OpenTBS->MergeBlock('data3', $data3);
            $OpenTBS->MergeBlock('data4', $data4);

            $signer = (int)Yii::$app->request->post('signer');
            $employee = \backend\models\Employee::findOne($signer);
            if(!null == $employee){
                $name_signer = $employee->name;
                $nip_signer = $employee->nip;
                $position_signer = $employee->positionDesc;
                $city_signer = Yii::$app->request->post('city_signer');
                $date_signer = Yii::$app->request->post('date_signer');
                $data[] = [
                    'name_training'=>$training->name,
                    'year_training'=>substr($training->start,0,4),
                    'city_signer'=>$city_signer,
                    'date_signer'=>\hscstudio\heart\helpers\Heart::twodate($date_signer),
                    'position_signer'=>$position_signer,
                    'name_signer'=>$name_signer,
                    'nip_signer'=>$nip_signer,
                ];
                $OpenTBS->MergeBlock('data', $data);
            }

            // Output the result as a file on the server. You can change output file
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_value_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
            exit;
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'certificate',
            'tb_training_id'=>$tb_training_id,
            'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }

    public function actionPrintBackendCertificate($tb_training_id,$tb_training_class_id,$filetype='docx'){
        try {
            $templates=[
                'docx'=>'ms-word.docx',
                'odt'=>'open-document.odt',
                'xlsx'=>'ms-excel.xlsx'
            ];
            // Initalize the TBS instance
            $OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
            // Change with Your template kaka
            //$template = Yii::getAlias('@common').'/extensions/opentbs-template/'.$templates[$filetype];
            $path = '';
            if(isset(Yii::$app->params['uploadPath'])){
                $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
            }
            else{
                $path = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
            }
            $template_path = $path . 'templates'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
            $template = $template_path . 'skpp02.docx';

            $OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
            //$OpenTBS->VarRef['modelName']= "TrainingClassStudentCertificate";

            $data = [];
            $training = \backend\models\Training::findOne($tb_training_id);
            $programSubjectHistories = \backend\models\ProgramSubjectHistory::find()
                ->where([
                    'tb_program_id' => $training->tb_program_id,
                    'revision' => $training->tb_program_revision,
                    'status'=>1,
                ])
                ->all();
            $idx = 1;
            foreach($programSubjectHistories as $programSubjectHistory){
                $data[] = [
                    'no' => $idx++.'.',
                    'name_subject'=>$programSubjectHistory->name,
                ];
            }
            $OpenTBS->MergeBlock('data', $data);

            $signer = (int)Yii::$app->request->post('signer');
            $employee = \backend\models\Employee::findOne($signer);
            if(!null == $employee){
                $name_signer = $employee->name;
                $nip_signer = $employee->nip;
                $position_signer = $employee->positionDesc;
                $city_signer = Yii::$app->request->post('city_signer');
                $date_signer = Yii::$app->request->post('date_signer');
                $data2[] = [
                    'city_signer'=>$city_signer,
                    'date_signer'=>\hscstudio\heart\helpers\Heart::twodate($date_signer),
                    'position_signer'=>$position_signer,
                    'name_signer'=>$name_signer,
                    'nip_signer'=>$nip_signer,
                ];
                $OpenTBS->MergeBlock('data2', $data2);
            }

            // Output the result as a file on the server. You can change output file
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_back_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
            exit;
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'certificate',
            'tb_training_id'=>$tb_training_id,
            'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }

    public function actionPrintStudentChecklist($tb_training_id,$tb_training_class_id,$filetype='xlsx'){

        try {
            if(in_array($filetype,['xlsx','xls'])){
                $types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
                $objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
                $path = '';
                if(isset(Yii::$app->params['uploadPath'])){
                    $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                }
                else{
                    $path = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
                }
                $template_path = $path . 'templates'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
                $template = $template_path . 'student.checklist.'.$filetype;
                $objPHPExcel = $objReader->load($template);
                $objPHPExcel->setActiveSheetIndex(0);
                $activeSheet = $objPHPExcel->getActiveSheet();
                $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                $objPHPExcel->getProperties()->setTitle("Student Checklist");
                $training=\backend\models\Training::findOne($tb_training_id);

                $idx=1;
                $baseRow = 7; // start line 27

                $trainingClassStudents=\backend\models\TrainingClassStudent::find()
                    ->where([
                        'tb_training_id' => $tb_training_id,
                        'tb_training_class_id' => $tb_training_class_id,
                        'status'=>1,
                    ])
                    ->all();
                foreach($trainingClassStudents as $trainingClassStudent){
                    if($idx==1){
                        $activeSheet->setCellValue('A2', $training->name)
                            ->setCellValue('A3', 'KELAS '. $trainingClassStudent->trainingClass->class)
                            ->setCellValue('A4', 'TAHUN ANGGARAN '. substr($training->start,0,4));
                    }
                    $student = $trainingClassStudent->trainingStudent->student;
                    $row = ($idx+$baseRow)-1;
                    $eselon = $student->satker;
                    $satker = [
                        '1'=>$student->unit->shortname.' ',
                        '2'=>$student->eselon2.' ',
                        '3'=>$student->eselon3.' ',
                        '4'=>$student->eselon4.' ',
                    ];
                    $studentSatker='-';
                    if (strlen($satker[$eselon])>=3){
                        $studentSatker = $satker[$eselon];
                    }
                    $activeSheet->setCellValue('A'.$row, $idx)
                        ->setCellValue('B'.$row, $student->name)
                        ->setCellValue('C'.$row, $student->nip.' ')
                        ->setCellValue('D'.$row, $student->born.', '.date('d-m-Y',strtotime($student->birthDay)))
                        ->setCellValue('E'.$row, $student->rankClass->name)
                        ->setCellValue('F'.$row, $student->positionDesc)
                        ->setCellValue('G'.$row, $studentSatker)
                        ->setCellValue('H'.$row, $student->email)
                        ->setCellValue('I'.$row, $student->phone)
                    ;
                    $idx++;
                }

                // Redirect output to a client?s web browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="student.checklist.'.$filetype.'"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
                $objWriter->save('php://output');
                exit;
            }
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'index',
            'tb_training_id'=>$tb_training_id,
            'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }

    public function actionPrintCertificateReceipt($tb_training_id,$tb_training_class_id,$filetype='xlsx'){

        try {
            if(in_array($filetype,['xlsx','xls'])){
                $types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
                $objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
                $path = '';
                if(isset(Yii::$app->params['uploadPath'])){
                    $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                }
                else{
                    $path = Yii::getAlias('@common').DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
                }
                $template_path = $path . 'templates'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
                $template = $template_path . 'certificate.receipt.'.$filetype;
                $objPHPExcel = $objReader->load($template);
                $objPHPExcel->setActiveSheetIndex(0);
                $activeSheet = $objPHPExcel->getActiveSheet();
                $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                $objPHPExcel->getProperties()->setTitle("Tanda Terima Sertifikat");
                $training=\backend\models\Training::findOne($tb_training_id);

                $idx=0;
                $baseRow = 12; // start line 12
                $days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
                $months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                $trainingClassStudents=\backend\models\TrainingClassStudent::find()
                    ->where([
                        'tb_training_id' => $tb_training_id,
                        'tb_training_class_id' => $tb_training_class_id,
                        'status'=>1,
                    ])
                    ->all();
                foreach($trainingClassStudents as $trainingClassStudent){
                    $row = $baseRow + $idx;
                    if($idx==1){
                        $month=$months[date("n")-1];
                        $day=$days[date("w")];
                        $activeSheet->setCellValue('C6', $training->name.' T.A '.
                            substr($training->start,0,4).
                            ' Kelas '.$trainingClassStudent->trainingClass->class
                        )
                            ->setCellValue('C8',' '.$day.'. '.date('d').' '.$month.' '.date('Y'));
                        ;
                        $activeSheet->setCellValue('E18',Yii::$app->user->identity->employee->name);
                        $activeSheet->setCellValue('E19','NIP '.Yii::$app->user->identity->employee->nip);
                    }
                    else{
                        $activeSheet->insertNewRowBefore($row+1,1);
                    }
                    $student = $trainingClassStudent->trainingStudent->student;
                    $eselon = $student->satker;
                    $satker = [
                        '1'=>$student->unit->shortname.' ',
                        '2'=>$student->eselon2.' ',
                        '3'=>$student->eselon3.' ',
                        '4'=>$student->eselon4.' ',
                    ];
                    $studentSatker='-';
                    if (strlen($satker[$eselon])>=3){
                        $studentSatker = $satker[$eselon];
                    }
                    $activeSheet->setCellValue('A'.$row, $idx+1)
                        ->setCellValue('B'.$row, $student->name)
                        ->setCellValue('C'.$row, $student->nip.' ')
                        ->setCellValue('D'.$row, $studentSatker)
                    ;
                    if(($idx+1)%2==1){
                        $activeSheet->setCellValue('E'.$row,'=A'.$row);
                    }
                    else{
                        $activeSheet->mergeCells('E'.($row-1).':E'.($row));
                        $activeSheet->mergeCells('F'.($row-1).':F'.($row));
                        $activeSheet->setCellValue('F'.($row-1),'=A'.$row);
                    }
                    $idx++;
                }
                if(($idx+1)%2<>1){
                    $row = $baseRow + $idx;
                    $activeSheet->insertNewRowBefore($row,1);
                    $activeSheet->mergeCells('E'.($row-1).':E'.($row));
                    $activeSheet->mergeCells('F'.($row-1).':F'.($row));
                }
                // Redirect output to a client?s web browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="student.checklist.'.$filetype.'"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
                $objWriter->save('php://output');
                exit;
            }
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'certificate',
            'tb_training_id'=>$tb_training_id,
            'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }
}
