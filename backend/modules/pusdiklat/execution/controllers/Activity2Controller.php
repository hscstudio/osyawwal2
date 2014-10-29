<?php
namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use backend\models\Activity;
use backend\modules\pusdiklat\execution\models\TrainingActivitySearch;

use backend\models\Person;
use backend\models\ObjectPerson;
use backend\models\ObjectFile;
use backend\models\Program;
use backend\models\ProgramSubject;
use backend\models\Reference;
use backend\models\ObjectReference;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Employee;

use backend\models\TrainingClass;
use backend\modules\pusdiklat\execution\models\TrainingClassSearch;

use backend\models\TrainingClassSubject;
use backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch;

use backend\models\TrainingClassStudent;
use backend\modules\pusdiklat\execution\models\TrainingClassStudentSearch;

use backend\models\TrainingStudent;
use backend\modules\pusdiklat\execution\models\TrainingStudentSearch;

use backend\models\Student;
use backend\modules\pusdiklat\execution\models\StudentSearch;

use backend\models\TrainingSchedule;
use backend\modules\pusdiklat\execution\models\TrainingScheduleSearch;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

use hscstudio\heart\helpers\Heart;

use yii\data\ArrayDataProvider;

use PHPExcel_IOFactory;
use PHPExcel_Cell_DataType;
use PHPExcel_Calculation;

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

    private $daftarMPperTanggal = []; 	/*
    									dipake untuk nyatet kordinat kolom MP yang udah dicatet
										yang dikategorikan berdasarkan tanggal
										contoh struktur: $daftarMPperTanggal => [
															'1-1-2014' => [
																'1' => [
																	'kodeMP' => 'MP1',
																	'kordinat' => 'A1'
																],
																'2' => [
																	'kodeMP' => 'MP2',
																	'kordinat' => 'A2'
																]
															],
															'2-1-2014' => [
																'1' => [
																	'kodeMP' => 'MP3',
																	'kordinat' => 'A3'
																],
															]*/

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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->active()
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
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->start = date('Y-m-d',strtotime($model->start));
		$model->end = date('Y-m-d',strtotime($model->end));
		$training = Training::findOne(['activity_id'=>$model->id]);
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		
		if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){
					$model->satker = 'current';
					$model->location = implode('|',$model->location);							
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							$training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id);
							
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Training & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['index']);
							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Data is NOT saved.');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Roolback transaction. Data is not saved');
			}
        } 
		
		return $this->render('update', $renders);
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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
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
			//1213030200 CEK KD_UNIT_ORG 1213030200 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
			'organisation_1213030200'=>'PIC TRAINING ACTIVITY [BIDANG PENYELENGGARAAN I]'
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
     * Creates a new TrainingClass model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateClass($id)
    {		
		$model = $this->findModel($id);
		$classCount1=$model->training->class_count_plan;
		$classCount2=TrainingClass::find()->where(['training_id' =>$model->id])->count();
		$createClass = $classCount1 - $classCount2;
		// x = 1 - 0 = 1
		// start = 0
		// finish = 0+x-1
		if($createClass>0){
			$start = $classCount2;
			$finish = $classCount2+$createClass-1;
			$classes = \hscstudio\heart\helpers\Heart::abjad($start,$finish);
			$created=0;
			$failed=0;
			foreach($classes as $class){
				echo "<br>".$class;
				$model = new TrainingClass();
				$model->training_id = $id;
				$model->class = $class;
				$model->status = 1;
				if($model->save()){
					$created++;
				}
				else{
					$failed++;
				}				
			}
			
			if($failed>0){
				Yii::$app->session->setFlash('warning', $created.' class created but '.$failed.' class failed');
			}
			else{
				Yii::$app->session->setFlash('success', $created.' class created');
			}
		}
		else{
			Yii::$app->session->setFlash('warning', 'No class created');
		}
		
		return $this->redirect(['class', 'id' => $id]);
    }
	
	/**
     * Deletes an existing TrainingClass model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteClass($id, $class_id)
    {
        $model = $this->findModelClass($class_id);
		$model->delete();
		Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
        return $this->redirect(['class', 'id' => $id]);
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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
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
							if(!empty($person)){
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
									'object_id' => $person_id,
									'type' => 'unit',
								])
								->one();
							if(null!=$object_reference){
								$object_reference_id = 1;
							}
						
							$password = Yii::$app->security->generatePasswordHash($nip,0);
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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		
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
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', 'Your request more than stock!');
			}
			else if( count($baseon)==0){
				Yii::$app->session->setFlash('error', 'Select base on random!');
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





    public function actionAttendance($training_class_id)
    {

		$trainingClass = TrainingClass::findOne($training_class_id);
		
		if(empty($start)) {
			$start = $trainingClass->training->activity->start;
		}
		
		if(empty($end) or $end < $start){
			$end = $start;
		}

		$searchModel = new TrainingScheduleSearch;
		$queryParams['TrainingScheduleSearch'] = [
			'training_class_id' => $training_class_id,
		];

		$queryParams = ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

        $dataProvider->query->groupBy = 'date(start)';

		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];

		if (Yii::$app->request->isAjax){
			return $this->renderAjax('attendance', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'trainingClass' => $trainingClass,
				'start' => $start,
				'end' => $end,
			]);
		}
		else{
			return $this->render('attendance', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'trainingClass'=> $trainingClass,
				'start' => $start,
				'end' => $end,
			]);
		}

    }




    public function actionSchedule($training_class_id,$start="",$finish="")
    {
		$trainingClass = TrainingClass::findOne($training_class_id);

    	$activity = $this->findModel($trainingClass->training->activity->id); // Activity
		
		if(empty($start)){
			$start = $trainingClass->training->activity->start;
		}
		
		if(empty($finish) or $finish<$start){
			$finish = $start;
		}
		$searchModel = new TrainingScheduleSearch();
		$queryParams['TrainingScheduleSearch']=[				
			'training_class_id'=>$training_class_id,
			'startDate'=>$start,
			'endDate'=>$finish,
		];
		$queryParams = ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];

		if (Yii::$app->request->isAjax){
			return $this->renderAjax('schedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'trainingClass'=>$trainingClass,
				'start'=>$start,
				'finish'=>$finish,
				'activity' => $activity
			]);
		}
		else{
			return $this->render('schedule', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'trainingClass'=>$trainingClass,
				'start'=>$start,
				'finish'=>$finish,
				'activity' => $activity
			]);
		}
    }



    public function actionGetMaxTime($training_class_id,$start=""){
		$start = date('Y-m-d',strtotime($start)); 
		$finish =  date('Y-m-d',(strtotime($start)+ 60*60*24));
		$trainingSchedule = TrainingSchedule::find()
				->where('
					(
						start >= :start AND 
						end <= :finish
					)
					AND 
					training_class_id = :training_class_id
					AND
					status = :status
				',
				[
					':start' => $start,
					':finish' => $finish,
					':training_class_id' => $training_class_id,
					':status' => 1,
				])
				->orderBy('end DESC')
				->one();
		if($trainingSchedule!=null)
			return date('H:i',strtotime($trainingSchedule->end));		
		else
			return '08:00';		
	}




	public function actionAddActivity($training_class_id) 
    { 		
		if (Yii::$app->request->isAjax){

			// PREPARING DATA
			$post = Yii::$app->request->post('TrainingScheduleExtSearch');
			$start = date('Y-m-d H:i',strtotime($post['startDate'].' '.$post['startTime'])); 
			$training_class_subject_id = $post['training_class_subject_id'];
			$activity_room_id =  $post['activity_room_id'];
			$activity = "";
			$pic = "";
			$hours = 0;
			$minutes = 0;
			
			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			$trainingClass = TrainingClass::findOne($training_class_id);
			if ($satker_id!=$trainingClass->training->activity->satker_id){
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
					$activity = $post['activity'];
					$pic = $post['pic'];
				}
				else{
					die('|0|Minutes have more than 0');
				}
			}
			$training_class_subject_id=(int)$training_class_subject_id;
			$finish = date('Y-m-d H:i',strtotime($start)+($minutes*60));
			$activity_room_id=(int)$activity_room_id;
			// CHECKING CONSTRAIN TIME
			$startSearch = $start + 60; // [08:00 - 09:00, 09:00 - 10:00] not excact between :)
			$finishSearch = $finish - 60;
			$trainingSchedule = TrainingSchedule::find()
				->where('
					((start between :start AND :finish)
						OR (end between :start AND :finish))
					AND 
					training_class_id = :training_class_id
					AND
					status = :status
				',
				[
					':start' => $startSearch,
					':finish' => $finishSearch,
					':training_class_id' => $training_class_id,
					':status' => 1,
				]);
				
			// IS NOT CONSTRAIN			
			if($trainingSchedule->count()==0){ 
				// PREPARING SAVE
				$model = new TrainingSchedule(); 
				$model->training_class_id=$training_class_id;
				$model->training_class_subject_id = $training_class_subject_id;
				$model->activity_room_id = $activity_room_id;		
				$model->activity = $activity;
				$model->pic = $pic;
				$model->hours = $hours;
				$model->start = $start;
				$model->end = $finish;
				$model->status = 1;
			
				if($model->save()) {
					Yii::$app->session->setFlash('success', '<i class="fa fa-fw fa-plus-circle"></i>Activity have Added');
					die('|1|Activity have Added|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($finish)));
					
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
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Only for ajax request');
			return $this->redirect(['schedule', 'training_class_id' => $training_class_id]);
		}
    } 

	public function actionDeleteActivity($id,$training_class_id)
    {
		if (Yii::$app->request->isAjax){

			//CHECKING SATKER
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			$trainingClass = TrainingClass::findOne($training_class_id);
			if ($satker_id != $trainingClass->training->activity->satker_id){
				die('|0|You have not privileges');
			}
			
			$trainingSchedule = TrainingSchedule::find()->where([
				'id'=>$id,
				'training_class_id'=>$training_class_id,
			])->one();

			$start = $trainingSchedule->start;

			if($trainingSchedule->delete()) {
				Yii::$app->session->setFlash('success', '<i class="fa fa-fw fa-plus-circle"></i>Delete activity success');
				die('|1|Activity have deleted|'.date('Y-m-d',strtotime($start)).'|'.date('H:i',strtotime($start)));
			}
			else{
				die('|0|There are some error');
			}
		}
		else{
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Only for ajax request');
			return $this->redirect(['schedule', 'training_class_id' => $training_class_id]);
		}		
		
    }






    public function actionDeleteClassStudent($id, $class_id, $training_class_student_id)
    {
        $model = $this->findModelClassStudent($training_class_student_id);
		$model->delete();
		
		Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-plus-circle"></i>Data have deleted.');
        return $this->redirect(['class-student', 'id' => $id, 'class_id' => $class_id]);
    }





    protected function findModelClassStudent($id)
    {
        if (($model = TrainingClassStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
					$trainingSubjectTrainerRecommendation = \backend\models\TrainingSubjectTrainerRecommendation::find()
					->where([
						'training_id'=>$activity->id,
						'program_subject_id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
						'trainer_id'=>$trainer_id,
						'status'=>1,
					])
					->one();
					$model2->type=$trainingSubjectTrainerRecommendation->type;
					$model2->trainer_id = $trainer_id;
					$model2->hours = $trainingSchedule->hours;
					$model2->status = 1;
					if($model2->save()) {
						$insert++;
					}
					else{
						die('|0|There are some error'.print_r($model2->errors));
					}
				}
				
				if($insert>0) {
					Yii::$app->session->setFlash('success', '<i class="fa fa-fw fa-plus-circle"></i>Trainer have added');
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
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Only for ajax request');
			return $this->redirect(['class-schedule', 'id' => $id, 'class_id' => $class_id]);
		} 
    }






    public function actionRecap($id) {
    	// Ngambil training 
    	$modelTraining = Training::findOne($id);
    	// dah

    	// Ambil template
    	// $template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/STANDAR_REKAPITULASI_KEHADIRAN_PESERTA_DIKLAT.xlsx';
    	$template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/STANDAR_REKAPITULASI_KEHADIRAN_PESERTA_DIKLAT.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($template);
		// dah

		// Ngisi konten
		$objPHPExcel->getActiveSheet()->setCellValue('A2', strtoupper($modelTraining->activity->name));
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'TAHUN ANGGARAN '.date('Y', strtotime($modelTraining->activity->start)));

		$pointerKolomMP = ord('E');

		$pointerKolomHadir = ord('F');

		$pointerKolomTTD = ord('F');

		$pointerBaris = 9;

		$jumlahBaris = 0;

		$namaHari = [
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kami',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu',
			'Sun' => 'Minggu'
		];

		$namaBulan = [
			'January' => 'Januari',
			'February' => 'Febuari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember'
		];

		foreach ($modelTraining->trainingClasses as $trainingClass) {

			$kolomAwal = $pointerKolomMP;
			$kolomAkhir = $pointerKolomMP;
			$pointerTanggal = '';
			$nomorMPTerbesar = 1;

			// Bikin kolom MP
			foreach ($trainingClass->trainingSchedules as $trainingSchedule) {
				
				// Klo ketemu Ishoma, coffe break dll lewati
				if ($trainingSchedule->training_class_subject_id <= 0) {
					continue;
				}
				// dah

				// Ngecek apakah MP pada hari schedule sekarang udah ada
				$pointerTanggal = date('d-m-Y', strtotime($trainingSchedule->start));

				// MP ketemu, artinya MP yg sama di tanggal yg sama uda ada, so lewati
				if ( isset($this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]) ) {
					continue;
		    	}
	    		// Pointer tanggal ketemu, tapi MP belum ada, berarti bikin baru
		    	elseif ( isset($this->daftarMPperTanggal[$pointerTanggal]) ) {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'namaMP' => ProgramSubject::findOne($trainingSchedule->trainingClassSubject->program_subject_id)->name
		    		];

		    		$kolomTertinggi = 0;

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
		    			if ($k != date('d-m-Y', strtotime($trainingSchedule->start))) {

		    				foreach ($v as $baris) {
		    					if (ord($baris['kolom'] > ord($kolomTertinggi))) {
		    						$kolomTertinggi = $baris['kolom'];
		    					}
		    				}

		    			}
		    			else {
		    				break;
		    			}
		    		}

		    		$kolomTertinggi +=1;

		    		/* if ($kolomTertinggi == 0) {
			    		die('error..a2c actrecap');
		    		}
		    		else { */
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
		    		/* } */
					die(chr($kolomTertinggi));
		    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);

					$objPHPExcel->getActiveSheet()->setCellValue(chr($kolomTertinggi).'8', 
						$daftarMPperTanggal [$pointerTanggal] [$trainingSchedule->trainingClassSubject->program_subject_id] ['kodeMP']
					);

					$nomorMPTerbesar += 1;

		    	}
	    		// Artinya pointer tanggal ga ada sama sekali
		    	else {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [];

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => ProgramSubject::findOne($trainingSchedule->trainingClassSubject->program_subject_id)->name
		    		];

		    		$kolomTertinggi = 0;

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
		    			
		    			if ($k != $pointerTanggal) {

		    				foreach ($v as $baris) {
		    					if (ord($baris['kolom']) > $kolomTertinggi) {
		    						$kolomTertinggi = ord($baris['kolom']);
		    					}
		    				}

		    			}
		    			else {
		    				break;
		    			}
		    		}

		    		$kolomTertinggi += 1;

		    		if ($kolomTertinggi == 0) {
			    		die('error..a2c actrecap');
		    		}
		    		elseif ($kolomTertinggi == 1) {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = 'E';
		    		}
		    		else {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
			    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);
		    		}

		    		$kordinatMP = $kolomTertinggi;

					$objPHPExcel->getActiveSheet()->setCellValue(
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].'8', 
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP']
					);

					$nomorMPTerbesar += 1;

					unset($kolomTertinggi);

		    	}
				// dah

			}
			// dah

			// Ngeset ulang pointerKolomHadir
			foreach ($this->daftarMPperTanggal as $MPperTanggal) {
				foreach ($MPperTanggal as $baris) {
					if (ord($baris['kolom']) > $pointerKolomHadir) {
						$pointerKolomHadir = ord($baris['kolom']);
					}
				}
			}
			$pointerKolomHadir += 1;
			// dah
			
			// Ngisi data semua kolom, ngeloopnya per student
			foreach ($trainingClass->trainingClassStudents as $trainingClassStudent) {
				
				// Insert row
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris + 1, 1);
				// dah

				// Ngisi nomer urut
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-8);
				// dah

				// Ngisi nama
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
					$trainingClassStudent->trainingStudent->student->person->name
				);
				// dah

				// Ngisi nip
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$pointerBaris, 
					$trainingClassStudent->trainingStudent->student->person->nip, 
					PHPExcel_Cell_DataType::TYPE_STRING
				);
				// dah

				// Ngisi unit
				$unit = "-";
				$object_reference = ObjectReference::find()
					->where([
						'object' => 'person',
						'object_id' => $trainingClassStudent->trainingStudent->student->person->id,
						'type' => 'unit',
					])
					->one();
				if(null!=$object_reference){
					$unit = $object_reference->reference->name;
				}
				if($trainingClassStudent->trainingStudent->student->satker==2){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon2)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon2;
					}
				}
				else if($trainingClassStudent->trainingStudent->student->satker==3){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon3)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon3;
					}
				}
				else if($trainingClassStudent->trainingStudent->student->satker==4){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon4)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon4;
					}
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
					$unit
				);
				// dah

				// Ngisi kehadiran
				$pointerMP = 0;
				$pointerTanggal = '';
				$jumlahJPKehadiranMax = 0;
				foreach ($trainingClassStudent->trainingClassStudentAttendances as $trainingClassStudentAttendance) {
					
					foreach ($trainingClass->trainingSchedules as $trainingSchedule) {

						$pointerTanggal = date('d-m-Y', strtotime($trainingSchedule->start));
					
						if ($trainingClassStudentAttendance->training_schedule_id == $trainingSchedule->id) {

							// Ngambil JP ideal dari schedule lalu di tambahkan ke $jumlahJPKehadiranMax, nanti dipake buat ngitung total hadir, bolos, dan %
							$jumlahJPKehadiranMax += $trainingSchedule->hours;
							// dah

							// Ngisi keterangan
							$keteranganSebelumnya = $objPHPExcel->getActiveSheet()->getCell(chr($pointerKolomHadir + 3).$pointerBaris)->getValue();
							if ($trainingClassStudentAttendance->reason != null or $trainingClassStudentAttendance->reason != '') {
								if ($keteranganSebelumnya != '') {
									$keteranganSebelumnya .= ', '.$trainingClassStudentAttendance->reason;
								}
								else {
									$keteranganSebelumnya = $trainingClassStudentAttendance->reason;
								}
								$objPHPExcel->getActiveSheet()->setCellValue(
									chr($pointerKolomHadir + 3).$pointerBaris, 
									$keteranganSebelumnya
								);
							}
							// dah
					
							if ($pointerMP == $trainingSchedule->trainingClassSubject->program_subject_id) {
								
								$nilai = $objPHPExcel->getActiveSheet()->getCell(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris
								)->getValue();

								$nilai += $trainingClassStudentAttendance->hours;
								
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$nilai
								);
							}
							else {
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$trainingClassStudentAttendance->hours
								);
								$pointerMP = $trainingSchedule->trainingClassSubject->program_subject_id;
							}
						}
					}
				}
				// dah

				// Ngisi rumus total hadir, bolos, %, dan keterangan
				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir).$pointerBaris, 
					'=SUM(E'.$pointerBaris.':'.chr($pointerKolomHadir - 1).$pointerBaris.')'
				);

				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir + 1).$pointerBaris, 
					'='.$jumlahJPKehadiranMax.' - '.chr($pointerKolomHadir).$pointerBaris
				);

				$kolomHadir = chr($pointerKolomHadir).$pointerBaris;
				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir + 2).$pointerBaris, 
					'='.$kolomHadir.'/'.$jumlahJPKehadiranMax
				);
				// dah

				$pointerBaris += 1;

				$jumlahBaris += 1;

			}
			// dah

			if ($pointerKolomTTD < $pointerKolomHadir) {
				$pointerKolomTTD = $pointerKolomHadir;
			}

			// Balik ke posisi kolom awal
			$pointerKolomMP = ord('E');
			$pointerKolomHadir = ord('F');
			// dah

		}
		// dah

		// Bikin legend
		$pointerBarisLegend = $pointerBaris + 3;
		foreach ($this->daftarMPperTanggal as $tanggal) {
			foreach ($tanggal as $baris) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBarisLegend, $baris['kodeMP']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBarisLegend, $baris['namaMP']);
				$pointerBarisLegend += 1;
			}
		}
		$pointerBarisLegend = $pointerBaris + 2;
		// dah

		// Bikin kolom tanda tangan
		$objPHPExcel->getActiveSheet()->setCellValue(
			chr($pointerKolomTTD).$pointerBarisLegend,
			'Jakarta,   '.date('F Y', strtotime($modelTraining->activity->end))
		);

		$modelEmployeeSigner = Employee::find()
			->where([
				'satker_id' => Yii::$app->user->identity->employee->satker_id,
				'organisation_id' => 396,
				'chairman' => 1
			])
			->one();

		if (!empty($modelEmployeeSigner)) {
			$objPHPExcel->getActiveSheet()->setCellValue(
				chr($pointerKolomTTD).($pointerBarisLegend + 5),
				$modelEmployeeSigner->person->name
			);
			$objPHPExcel->getActiveSheet()->setCellValue(
				chr($pointerKolomTTD).($pointerBarisLegend + 6),
				'NIP '.$modelEmployeeSigner->person->nip
			);
		}
		// dah

		// Ngeset lebar kolom
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		for ($i=ord('E'); $i < $pointerKolomTTD; $i++) { 
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(7);
		}
		// dah

		// Finishing
		$kolomAwal = 'E';
		$kolomAkhir = 'E';
		foreach ($this->daftarMPperTanggal as $MPperTanggal => $data) {
			foreach ($data as $baris) {
				if (ord($baris['kolom']) > ord($kolomAkhir)) {
					$kolomAkhir = $baris['kolom'];
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'6', $namaHari[date('D', strtotime($MPperTanggal))]);
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'7', date('d M', strtotime($MPperTanggal)));
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'6:'.$kolomAkhir.'6');
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'7:'.$kolomAkhir.'7');
			$kolomAwal = chr(ord($kolomAkhir) + 1);
		}

		$kolomAwal = '';
		$kolomAkhir = 'E';
		$bulanAcuan = 0;
		$jumlahBulanDistinct = 0;
		foreach ($this->daftarMPperTanggal as $MPperTanggal => $data) {
			foreach ($data as $baris) {
				if ($kolomAwal == '') {
					$bulanAcuan = date('m', strtotime($MPperTanggal));
					$kolomAwal = $baris['kolom'];
				}
				else {
					if (date('m', strtotime($MPperTanggal)) > $bulanAcuan) {
						$bulanAcuan = date('m', strtotime($MPperTanggal));
						$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'5', $namaBulan[date('F', strtotime($MPperTanggal))]);
						$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'5:'.$kolomAkhir.'5');
						$kolomAwal = chr(ord($kolomAkhir) + 1);
						$jumlahBulanDistinct += 1;
					}
					else {
						$kolomAkhir = $baris['kolom'];
					}
				}
			}
		}
		if ($jumlahBulanDistinct == 0) {
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'5', date('F', strtotime($MPperTanggal)));
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'5:'.$kolomAkhir.'5');
		}
		// dah
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="recapitulation_aggregate_attendance_'.$modelTraining->activity->name.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

    }




    
    public function actionRecapClass($id, $training_class_id) {
    	// Ngambil training 
    	$modelTraining = Training::findOne($id);
    	// dah

    	// Ambil template
    	// $template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/STANDAR_REKAPITULASI_KEHADIRAN_PESERTA_DIKLAT.xlsx';
    	$template = Yii::getAlias('@backend').'/../file/template/pusdiklat/execution/STANDAR_REKAPITULASI_KEHADIRAN_PESERTA_DIKLAT.xls';
		$objPHPExcel = PHPExcel_IOFactory::load($template);
		// dah

		// Ngisi konten
		$namaKelas = TrainingClass::findOne($training_class_id)->class;
		$objPHPExcel->getActiveSheet()->setCellValue(
			'A2', 
			strtoupper($modelTraining->activity->name.' Kelas '.$namaKelas)
		);
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'TAHUN ANGGARAN '.date('Y', strtotime($modelTraining->activity->start)));

		$pointerKolomMP = ord('E');

		$pointerKolomHadir = ord('F');

		$pointerKolomTTD = ord('F');

		$pointerBaris = 9;

		$jumlahBaris = 0;

		$namaHari = [
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kami',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu',
			'Sun' => 'Minggu'
		];

		$namaBulan = [
			'January' => 'Januari',
			'February' => 'Febuari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember'
		];

		foreach ($modelTraining->trainingClasses as $trainingClass) {

			// Nah, kita filter disini, jadi cuma bikin report buat class yg di variable $training_class_id
			if ($trainingClass->id != $training_class_id) {
				continue;
			}
			// dah

			$kolomAwal = $pointerKolomMP;
			$kolomAkhir = $pointerKolomMP;
			$pointerTanggal = '';
			$nomorMPTerbesar = 1;

			// Bikin kolom MP
			foreach ($trainingClass->trainingSchedules as $trainingSchedule) {
				
				// Klo ketemu Ishoma, coffe break dll lewati
				if ($trainingSchedule->training_class_subject_id <= 0) {
					continue;
				}
				// dah

				// Ngecek apakah MP pada hari schedule sekarang udah ada
				$pointerTanggal = date('d-m-Y', strtotime($trainingSchedule->start));

				// MP ketemu, artinya MP yg sama di tanggal yg sama uda ada, so lewati
				if ( isset($this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]) ) {
					continue;
		    	}
	    		// Pointer tanggal ketemu, tapi MP belum ada, berarti bikin baru
		    	elseif ( isset($this->daftarMPperTanggal[$pointerTanggal]) ) {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'namaMP' => ProgramSubject::findOne($trainingSchedule->trainingClassSubject->program_subject_id)->name
		    		];

		    		$kolomTertinggi = '';

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
		    			if ($k != date('d-m-Y', strtotime($trainingSchedule->start))) {

		    				foreach ($v as $baris) {
		    					if (ord($baris['kolom'] > ord($kolomTertinggi))) {
		    						$kolomTertinggi = $baris['kolom'];
		    					}
		    				}

		    			}
		    			else {
		    				break;
		    			}
		    		}

		    		$kolomTertinggi += 1;

		    		if ($kolomTertinggi == '') {
			    		die('error..a2c actrecap');
		    		}
		    		else {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
		    		}

		    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);

					$objPHPExcel->getActiveSheet()->setCellValue(chr($kolomTertinggi).'8', 
						$daftarMPperTanggal [$pointerTanggal] [$trainingSchedule->trainingClassSubject->program_subject_id] ['kodeMP']
					);

					$nomorMPTerbesar += 1;

		    	}
	    		// Artinya pointer tanggal ga ada sama sekali
		    	else {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [];

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => ProgramSubject::findOne($trainingSchedule->trainingClassSubject->program_subject_id)->name
		    		];

		    		$kolomTertinggi = 0;

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
		    			
		    			if ($k != $pointerTanggal) {

		    				foreach ($v as $baris) {
		    					if (ord($baris['kolom']) > $kolomTertinggi) {
		    						$kolomTertinggi = ord($baris['kolom']);
		    					}
		    				}

		    			}
		    			else {
		    				break;
		    			}
		    		}

		    		$kolomTertinggi += 1;

		    		if ($kolomTertinggi == 0) {
			    		die('error..a2c actrecap');
		    		}
		    		elseif ($kolomTertinggi == 1) {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = 'E';
		    		}
		    		else {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
			    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);
		    		}

		    		$kordinatMP = $kolomTertinggi;

					$objPHPExcel->getActiveSheet()->setCellValue(
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].'8', 
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP']
					);

					$nomorMPTerbesar += 1;

					unset($kolomTertinggi);

		    	}
				// dah

			}
			// dah

			// Ngeset ulang pointerKolomHadir
			foreach ($this->daftarMPperTanggal as $MPperTanggal) {
				foreach ($MPperTanggal as $baris) {
					if (ord($baris['kolom']) > $pointerKolomHadir) {
						$pointerKolomHadir = ord($baris['kolom']);
					}
				}
			}
			$pointerKolomHadir += 1;
			// dah
			
			// Ngisi data semua kolom, ngeloopnya per student
			foreach ($trainingClass->trainingClassStudents as $trainingClassStudent) {
				
				// Insert row
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris + 1, 1);
				// dah

				// Ngisi nomer urut
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-8);
				// dah

				// Ngisi nama
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
					$trainingClassStudent->trainingStudent->student->person->name
				);
				// dah

				// Ngisi nip
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$pointerBaris, 
					$trainingClassStudent->trainingStudent->student->person->nip, 
					PHPExcel_Cell_DataType::TYPE_STRING
				);
				// dah

				// Ngisi unit
				$unit = "-";
				$object_reference = ObjectReference::find()
					->where([
						'object' => 'person',
						'object_id' => $trainingClassStudent->trainingStudent->student->person->id,
						'type' => 'unit',
					])
					->one();
				if(null!=$object_reference){
					$unit = $object_reference->reference->name;
				}
				if($trainingClassStudent->trainingStudent->student->satker==2){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon2)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon2;
					}
				}
				else if($trainingClassStudent->trainingStudent->student->satker==3){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon3)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon3;
					}
				}
				else if($trainingClassStudent->trainingStudent->student->satker==4){
					if(!empty($trainingClassStudent->trainingStudent->student->eselon4)){
						$unit = $trainingClassStudent->trainingStudent->student->eselon4;
					}
				}
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
					$unit
				);
				// dah

				// Ngisi kehadiran
				$pointerMP = 0;
				$pointerTanggal = '';
				$jumlahJPKehadiranMax = 0;
				foreach ($trainingClassStudent->trainingClassStudentAttendances as $trainingClassStudentAttendance) {
					
					foreach ($trainingClass->trainingSchedules as $trainingSchedule) {

						$pointerTanggal = date('d-m-Y', strtotime($trainingSchedule->start));
					
						if ($trainingClassStudentAttendance->training_schedule_id == $trainingSchedule->id) {

							// Ngambil JP ideal dari schedule lalu di tambahkan ke $jumlahJPKehadiranMax, nanti dipake buat ngitung total hadir, bolos, dan %
							$jumlahJPKehadiranMax += $trainingSchedule->hours;
							// dah

							// Ngisi keterangan
							$keteranganSebelumnya = $objPHPExcel->getActiveSheet()->getCell(chr($pointerKolomHadir + 3).$pointerBaris)->getValue();
							if ($trainingClassStudentAttendance->reason != null or $trainingClassStudentAttendance->reason != '') {
								if ($keteranganSebelumnya != '') {
									$keteranganSebelumnya .= ', '.$trainingClassStudentAttendance->reason;
								}
								else {
									$keteranganSebelumnya = $trainingClassStudentAttendance->reason;
								}
								$objPHPExcel->getActiveSheet()->setCellValue(
									chr($pointerKolomHadir + 3).$pointerBaris, 
									$keteranganSebelumnya
								);
							}
							// dah
					
							if ($pointerMP == $trainingSchedule->trainingClassSubject->program_subject_id) {
								
								$nilai = $objPHPExcel->getActiveSheet()->getCell(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris
								)->getValue();

								$nilai += $trainingClassStudentAttendance->hours;
								
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$nilai
								);
							}
							else {
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$trainingClassStudentAttendance->hours
								);
								$pointerMP = $trainingSchedule->trainingClassSubject->program_subject_id;
							}
						}
					}
				}
				// dah

				// Ngisi rumus total hadir, bolos, %, dan keterangan
				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir).$pointerBaris, 
					'=SUM(E'.$pointerBaris.':'.chr($pointerKolomHadir - 1).$pointerBaris.')'
				);

				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir + 1).$pointerBaris, 
					'='.$jumlahJPKehadiranMax.' - '.chr($pointerKolomHadir).$pointerBaris
				);

				$kolomHadir = chr($pointerKolomHadir).$pointerBaris;
				$objPHPExcel->getActiveSheet()->setCellValue(
					chr($pointerKolomHadir + 2).$pointerBaris, 
					'='.$kolomHadir.'/'.$jumlahJPKehadiranMax
				);
				// dah

				$pointerBaris += 1;

				$jumlahBaris += 1;

			}
			// dah

			if ($pointerKolomTTD < $pointerKolomHadir) {
				$pointerKolomTTD = $pointerKolomHadir;
			}

			// Balik ke posisi kolom awal
			$pointerKolomMP = ord('E');
			$pointerKolomHadir = ord('F');
			// dah

		}
		// dah

		// Bikin legend
		$pointerBarisLegend = $pointerBaris + 3;
		foreach ($this->daftarMPperTanggal as $tanggal) {
			foreach ($tanggal as $baris) {
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBarisLegend, $baris['kodeMP']);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBarisLegend, $baris['namaMP']);
				$pointerBarisLegend += 1;
			}
		}
		$pointerBarisLegend = $pointerBaris + 2;
		// dah

		// Bikin kolom tanda tangan
		$objPHPExcel->getActiveSheet()->setCellValue(
			chr($pointerKolomTTD).$pointerBarisLegend,
			'Jakarta,   '.date('F Y', strtotime($modelTraining->activity->end))
		);

		$modelEmployeeSigner = Employee::find()
			->where([
				'satker_id' => Yii::$app->user->identity->employee->satker_id,
				'organisation_id' => 396,
				'chairman' => 1
			])
			->one();

		if (!empty($modelEmployeeSigner)) {
			$objPHPExcel->getActiveSheet()->setCellValue(
				chr($pointerKolomTTD).($pointerBarisLegend + 5),
				$modelEmployeeSigner->person->name
			);
			$objPHPExcel->getActiveSheet()->setCellValue(
				chr($pointerKolomTTD).($pointerBarisLegend + 6),
				'NIP '.$modelEmployeeSigner->person->nip
			);
		}
		// dah

		// Ngeset lebar kolom
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		for ($i=ord('E'); $i < $pointerKolomTTD; $i++) { 
			$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(7);
		}
		// dah

		// Finishing
		$kolomAwal = 'E';
		$kolomAkhir = 'E';
		foreach ($this->daftarMPperTanggal as $MPperTanggal => $data) {
			foreach ($data as $baris) {
				if (ord($baris['kolom']) > ord($kolomAkhir)) {
					$kolomAkhir = $baris['kolom'];
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'6', $namaHari[date('D', strtotime($MPperTanggal))]);
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'7', date('d M', strtotime($MPperTanggal)));
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'6:'.$kolomAkhir.'6');
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'7:'.$kolomAkhir.'7');
			$kolomAwal = chr(ord($kolomAkhir) + 1);
		}

		$kolomAwal = '';
		$kolomAkhir = 'E';
		$bulanAcuan = 0;
		$jumlahBulanDistinct = 0;
		foreach ($this->daftarMPperTanggal as $MPperTanggal => $data) {
			foreach ($data as $baris) {
				if ($kolomAwal == '') {
					$bulanAcuan = date('m', strtotime($MPperTanggal));
					$kolomAwal = $baris['kolom'];
				}
				else {
					if (date('m', strtotime($MPperTanggal)) > $bulanAcuan) {
						$bulanAcuan = date('m', strtotime($MPperTanggal));
						$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'5', $namaBulan[date('F', strtotime($MPperTanggal))]);
						$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'5:'.$kolomAkhir.'5');
						$kolomAwal = chr(ord($kolomAkhir) + 1);
						$jumlahBulanDistinct += 1;
					}
					else {
						$kolomAkhir = $baris['kolom'];
					}
				}
			}
		}
		if ($jumlahBulanDistinct == 0) {
			$objPHPExcel->getActiveSheet()->setCellValue($kolomAwal.'5', date('F', strtotime($MPperTanggal)));
			$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'5:'.$kolomAkhir.'5');
		}
		// dah
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="recapitulation_attendance_'.$modelTraining->activity->name.'_Kelas_'.$namaKelas.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;

    }
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionExportTraining($year='',$status='nocancel',$filetype='xlsx')
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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];    
		$dataProvider->setPagination(false);
		
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR.'training.list.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		$objPHPExcel->getProperties()->setTitle("Kalender Diklat");
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->setCellValue('A3', strtoupper(\Yii::$app->user->identity->employee->satker->name));
		$idx=7; // line 7
		$status_arr = ['0'=>'Planning','1'=>'Ready','2'=>'Execute','3'=>'Cancel'];
		foreach($dataProvider->getModels() as $data){		
			if($idx==7){
				$activeSheet->setCellValue('A4', date('Y',strtotime($data->start)));
			}
			$activeSheet->insertNewRowBefore($idx+1,1);
			$locations = explode('|',$data->location);
			$location = '';
			if(Yii::$app->user->identity->employee->satker_id==$locations[0]){
				$location = $locations[1];
			}
			$activeSheet->setCellValue('A'.$idx, $idx-6)
					    ->setCellValue('B'.$idx, $data->training->number)
					    ->setCellValue('C'.$idx, $data->name)
					    ->setCellValue('D'.$idx, date('d M y',strtotime($data->start)))
					    ->setCellValue('E'.$idx, date('d M y',strtotime($data->end)))
						->setCellValue('F'.$idx, $data->training->program->days)
						->setCellValue('G'.$idx, $data->training->program->hours)
						->setCellValue('H'.$idx, $data->training->student_count_plan)
						->setCellValue('I'.$idx, $data->training->class_count_plan)
						->setCellValue('J'.$idx, ($data->hostel==1)?'Ya':'Tidak')
						->setCellValue('K'.$idx, $location)
					    ->setCellValue('L'.$idx, $status_arr[$data->status])
					    
						;
			$idx++;
		} 	
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="training.list.'.date('YmdHis').'.'.$filetype.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
		/* return $this->redirect(['student', 'id' => $id, 'status'=>$status]);	 */
    }

}
