<?php
namespace backend\modules\pusdiklat2\competency\controllers\execution;

use Yii;
use backend\models\Activity;
use backend\modules\pusdiklat2\competency\models\execution\TrainingActivitySearch;

use backend\models\Person;
use backend\models\ObjectPerson;
use backend\models\ObjectFile;
use backend\models\Program;
use backend\models\ProgramSubject;
use backend\models\ProgramSubjectHistory;
use backend\models\Reference;
use backend\models\ObjectReference;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Employee;
use backend\models\Trainer;

use backend\models\TrainingClass;
use backend\modules\pusdiklat2\competency\models\execution\TrainingClassSearch;

use backend\models\TrainingClassSubject;
use backend\modules\pusdiklat2\competency\models\execution\TrainingClassSubjectSearch;

use backend\models\TrainingClassStudent;
use backend\modules\pusdiklat2\competency\models\execution\TrainingClassStudentSearch;

use backend\models\TrainingStudent;
use backend\modules\pusdiklat2\competency\models\execution\TrainingStudentSearch;

use backend\models\Student;
use backend\modules\pusdiklat2\competency\models\execution\StudentSearch;

use backend\models\TrainingSchedule;
use backend\modules\pusdiklat2\competency\models\execution\TrainingScheduleSearch;
use backend\modules\pusdiklat2\competency\models\execution\TrainingScheduleExtSearch;

use backend\models\ActivityRoom;

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
			'organisation_1213030200'=>'PIC TRAINING ACTIVITY [BIDANG PENYELENGGARAAN II]'
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
            'query' => \backend\models\ProgramSubjectHistory::find()
						->where([
							'program_id'=>$model->training->program_id,
							'program_revision'=>$model->training->program_revision,
							'status'=>1
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
		$searchModel = new TrainingClassSearch();
        $queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingClassSearch']=[
					'training_id' => $id,
				];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where([
				'status'=>'1',
				'training_id' => $id
			])
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
						->where([
							'training_student.status'=>'1',
							'training_id' => $id
						])
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
					->where([
						'status'=>'1',
						'training_id' => $id
					])
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
		
		$programSubjects= ProgramSubjectHistory::find()
			->where([
				'program_id'=>$activity->training->program_id,
				'program_revision'=>$activity->training->program_revision,
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
    public function actionStudent($id,$status=1)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch();
		$queryParams['TrainingStudentSearch']=[
			'training_id' => $id,
			'status' => $status
		]; 
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		$dataProvider->getSort()->defaultOrder = [
			'status'=>SORT_DESC,		
		];
		
        return $this->render('student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'status' => $status,
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
							if(!empty($student)){
								$student_id = $student->person_id;
							}
							
							$reference_id = 0;
							$reference = Reference::find()
								->where([
									'type'=>'unit',
									'value'=>$unit
								])
								->one();
							if(!empty($reference)){
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
							if(!empty($object_reference)){
								$object_reference_id = 1;
							}
							
						
							$password = Yii::$app->security->generatePasswordHash($nip,4);
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
						if($object_reference_id>=1){
							$object_reference = ObjectReference::find()
								->where([
									'object' => 'person',
									'object_id' => $person_id,
									'type' => 'unit',
								])
								->one();
							$object_reference->reference_id = $unit_id;
						}
						else{
							$object_reference = new ObjectReference([
								'object' => 'person',
								'object_id' => $person_id,
								'type' => 'unit',
								'reference_id' => $unit_id,
							]);
						}
						$object_reference->save(); 						
					}
				} 
			}
			
			unset($session['data']);

		}
		
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
			->where([
				'status'=>'1',
				'training_id' => $id
			])
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
					->where([
						'training_student.status'=>'1',
						'training_id' => $id
					])
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
					->where([
						'status'=>1,
						'training_id' => $id
					])
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
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
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
	
	/**
     * Lists all Room models.
     * @return mixed
     */
    public function actionExportClassSchedule($id, $class_id,$start="",$end="",$filetype="xlsx")
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
			/* 'startDate' => date('Y-m-d',strtotime($start)),
			'endDate'=>date('Y-m-d',strtotime($start)), */
		];		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		$dataProvider->setPagination(false);
		
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.
			DIRECTORY_SEPARATOR.'training.class.schedule.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->setCellValue('A2', $activity->name)
					->setCellValue('A3', 'TAHUN ANGGARAN '. substr($activity->start,0,4))
					->setCellValue('A4', 'KELAS '. $class->class);
		$idx=7; // line 7
		$last_date='';
		foreach($dataProvider->getModels() as $data){
			/* $unit = "-";
			$object_reference = \backend\models\ObjectReference::find()
				->where([
					'object' => 'person',
					'object_id' => $data->student->person->id,
					'type' => 'unit',
				])
				->one();
			if(!empty($object_reference)){
				$unit = $object_reference->reference->name;
			}
			if($data->student->satker==2){
				if(!empty($data->student->eselon2)){
					$unit = $data->student->eselon2;
				}
			}
			else if($data->student->satker==3){
				if(!empty($data->student->eselon3)){
					$unit = $data->student->eselon3;
				}
			}
			else if($data->student->satker==4){
				if(!empty($data->student->eselon4)){
					$unit = $data->student->eselon4;
				}
			}
					
			$trainingClassStudent = \backend\models\TrainingClassStudent::find()
				->where([
					'training_student_id'=>$data->id
				])
				->one();
			$class = '-';*/
			
			if(!empty($trainingClassStudent)) $class = $trainingClassStudent->class;			
			
			$start = date('d-M-Y H:i',strtotime($data->start));
			$finish = date('d-M-Y H:i',strtotime($data->end));
			$startDate = date('d-M-Y',strtotime($data->start));
			$finishDate = date('d-M-Y',strtotime($data->end));
			$startTime = date('H:i',strtotime($data->start));
			$finishTime = date('H:i',strtotime($data->end));
			
			$weeks = ['','Senin','Selasa','Rabu','Kamis',"Jum'at",'Sabtu','Minggu'];
			$week = $weeks[date('N',strtotime($data->start))];
			$months = ['','Januari','Februari','Maret','April','Mei','Juni','July','Agustus','September','Oktober','November','Desember'];
			$month = $months[date('n',strtotime($data->start))];
			$date = $week.', '.date('j',strtotime($data->start)).' '.$month.' '.date('Y',strtotime($data->start));
			if($date!=$last_date){				
				$last_date = $date;
			}
			else{
				$date = '';
			}
			
			$time = '-';
			if($start==$finish){
				$time_start = $startTime;				
				$time_end = $startTime;				
			}
			else{
				$time_start = $startTime;				
				$time_end = $finishTime;	
			}
			
			$activity_name = '-';
			if($data->training_class_subject_id>0){
				$trainingClassSubject = \backend\models\TrainingClassSubject::findOne($data->training_class_subject_id);
				if($trainingClassSubject!==null){					
					$program_subject_id = $trainingClassSubject->program_subject_id;
					$program_id = $activity->training->program_id;
					$program_revision =  $activity->training->program_revision;
					$programSubjectHistory = \backend\models\ProgramSubjectHistory::find()
						->where([
							'id'=>$program_subject_id,
							'program_id'=>$program_id,
							'program_revision'=>$program_revision,
							'status'=>1
						])
						->one();
					if(!empty($programSubjectHistory)){
						$name = $programSubjectHistory->subjectType->name.' '.$programSubjectHistory->name;
						$activity_name =  $name;
					}
					else{
						$activity_name =  "Undefined??? hello??";
					}
					
				}
				else{
					$activity_name =  "Undefined??? hello??";
				}
			}
			else{
				$activity_name =  $data->activity;
			}
			
			if($data->training_class_subject_id>0){
				$hours = $data->hours;
			}
			else{
				$hours = '';
			}
			
			$pic = '';
			if($data->training_class_subject_id>0){				
				$trainingScheduleTrainer = \backend\models\TrainingScheduleTrainer::find()
					->where([
						'training_schedule_id'=>$data->id,
						'status'=>1,
					])
					->orderBy('type ASC')
					->all();
				$type= "-1";	
				$idx2 = 1;
				foreach($trainingScheduleTrainer as $trainer){
					$pic .= $trainer->trainer->person->name.' ('.$trainer->trainer->person->organisation.')
					';
					/* if($type!=$trainer->type){
						$content .="<hr style='margin:2px 0'>";
						$content .="<strong>".$trainer->trainerType->name."</strong>";
						$content .="<hr style='margin:2px 0'>";
						$type=$trainer->type;
						$idx=1;
					}
					
					$content .="<div>";
					$content .="<span  class='label label-default' data-toggle='tooltip' title='".$trainer->trainer->person->organisation." - ".$trainer->trainer->person->phone."'>".$idx++.". ".$trainer->trainer->person->name."</span> ";							 */
				}
			}
			else{
				$pic = $data->pic;
			}
			
			$activeSheet->insertNewRowBefore($idx+1,1);
			$activeSheet->setCellValue('A'.$idx, $idx-6)
					    ->setCellValue('B'.$idx, $date)
					    ->setCellValue('C'.$idx, $time_start)
						->setCellValue('D'.$idx, $time_end)
						->setCellValue('E'.$idx, $activity_name) 
						->setCellValue('F'.$idx, $hours) 
						->setCellValue('G'.$idx, $pic) 
						;
			$idx++;
		}		
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="training.class.schedule.'.date('YmdHis').'.'.$filetype.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
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
			->orderBy('type')
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
	
	public function actionDeleteTrainerClassSchedule($id, $class_id, $schedule_id, $trainer_id) 
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		$trainingSchedule = TrainingSchedule::findOne($schedule_id);
		$trainingScheduleTrainer = TrainingScheduleTrainer::find()
			->where([
				'training_schedule_id'=>$schedule_id,
				'trainer_id'=>$trainer_id
			])
			->one();
		$trainingScheduleTrainer->delete();
		Yii::$app->session->setFlash('success', 'Trainer have deleted');
		die('|1|Trainer have deleted|'.date('Y-m-d',strtotime($trainingSchedule->start)).'|'.date('H:i',strtotime($trainingSchedule->end)));
	}


    /* public function actionTrainerClassSchedule($id, $class_id, $schedule_id) 
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
    } */






    public function actionRecap($id, $training_class_id = null) {
    	// Ngambil training 
    	$modelTraining = Training::findOne($id);
    	// dah

    	// Ambil template
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

			// Nah, kita filter disini, jadi cuma bikin report buat class yg di variable $training_class_id
			if ($trainingClass->id != $training_class_id and $training_class_id != null) {
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

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [];

					
					$programSubject = \backend\models\ProgramSubjectHistory::find()
						->where([
							'id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
							'program_id'=>$modelTraining->program_id,
							'program_revision'=>$modelTraining->program_revision,
							'status'=>1
						])
						->one();
					$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => $programSubject->name,
		    		];

		    		// koreksi kodeMP
		    		ksort($this->daftarMPperTanggal);
		    		foreach ($this->daftarMPperTanggal as $tanggal => $dataTanggal) {
		    			
		    			if ($tanggal != $pointerTanggal) {

		    				foreach ($dataTanggal as $subjectId => $dataSubject) {

			    				if ($subjectId == $trainingSchedule->trainingClassSubject->program_subject_id) {
		    						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP'] = $dataSubject['kodeMP'];
			    				}
		    				}
		    			}
		    			else {
		    				break;
		    			}
		    		}
		    		// dah

		    		// Benerin urutan kolom
		    		$kolomTertinggi = ord('E');

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
	    				foreach ($v as $baris) {
	    					if (ord($baris['kolom']) != '') {
    							$kolomTertinggi = ord($baris['kolom']);
    						}
	    				}
		    		}
		    		$kolomTertinggi += 1;

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
		    		// dah


		    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);

					$objPHPExcel->getActiveSheet()->setCellValue(chr($kolomTertinggi).'8', 
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP']
					);

					$nomorMPTerbesar += 1;

		    	}
	    		// Artinya pointer tanggal ga ada sama sekali
		    	else {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [];

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];
					
					$programSubject = \backend\models\ProgramSubjectHistory::find()
						->where([
							'id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
							'program_id'=>$modelTraining->program_id,
							'program_revision'=>$modelTraining->program_revision,
							'status'=>1
						])
						->one();
		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => $programSubject->name,
		    		];

		    		// Koreksi kodeMP
		    		ksort($this->daftarMPperTanggal);
		    		foreach ($this->daftarMPperTanggal as $tanggal => $dataTanggal) {
		    			
		    			if ($tanggal != $pointerTanggal) {

		    				foreach ($dataTanggal as $subjectId => $dataSubject) {

			    				if ($subjectId == $trainingSchedule->trainingClassSubject->program_subject_id) {
		    						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP'] = $dataSubject['kodeMP'];
		    						$nomorMPTerbesar -= 1; // Perlu dikurang 1 sebab dibawah nanti MP nya pasti naik 1, padahal kode diatas cm ngopi kodeMP karena uda sama
			    				}
		    				}
		    			}
		    			else {
		    				break;
		    			}
		    		}
		    		// dah

		    		// Koreksi kolom
		    		$kolomTertinggi = ord('E');

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
	    				foreach ($v as $baris) {
	    					if (ord($baris['kolom']) != '') {
    							$kolomTertinggi = ord($baris['kolom']);
    						}
	    				}
		    		}
		    		// dah

		    		if ($kolomTertinggi == ord('E')) {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = 'E';
		    		}
		    		else {
		    			$kolomTertinggi += 1;
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
		$pointerBarisLegend = $pointerBaris + 4;
		$catat = [];
		foreach ($this->daftarMPperTanggal as $tanggal) {
			foreach ($tanggal as $baris) {
				$lewati = false;
				foreach ($catat as $kodeMP) {
					if ($baris['kodeMP'] == $kodeMP) {
						$lewati = true;
					}
				}
				if ($lewati == true) {
					continue;
				}
				$barisSebelumnya = $pointerBarisLegend -1 ;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBarisLegend, $baris['kodeMP']);
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$barisSebelumnya), 'A'.$pointerBarisLegend);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBarisLegend, $baris['namaMP']);
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('B'.$barisSebelumnya), 'B'.$pointerBarisLegend);
				$catat[] = $baris['kodeMP'];
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
			if($kolomAkhir != 'E'){
				$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'6:'.$kolomAkhir.'6');
				$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'7:'.$kolomAkhir.'7'); 
			}
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

		header('Content-Disposition: attachment;filename="recapitulation_student_attendance_'.$modelTraining->activity->name.'.xls"');
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





    public function actionRecapTrainer($id, $training_class_id = null,$filetype='xls') {
    	// Ngambil training 
    	$modelTraining = Training::findOne($id);
    	// dah

    	// Ambil template
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR.'STANDAR_REKAPITULASI_KEHADIRAN_PENGAJAR_DIKLAT.'.$filetype;
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$objPHPExcel = $objReader->load($template);
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

			// Nah, kita filter disini, jadi cuma bikin report buat class yg di variable $training_class_id
			if ($trainingClass->id != $training_class_id and $training_class_id != null) {
				continue;
			}
			// dah

			$kolomAwal = $pointerKolomMP;
			$kolomAkhir = $pointerKolomMP;
			$pointerTanggal = '';
			$nomorMPTerbesar = 1;
			$daftarPengajar = [];

			// Bikin kolom MP
			foreach ($trainingClass->trainingSchedules as $trainingSchedule) {
				
				// Klo ketemu Ishoma, coffe break dll lewati
				if ($trainingSchedule->training_class_subject_id <= 0) {
					continue;
				}
				// dah

				// Nyatet pengajar yang distinct
				foreach ($trainingSchedule->trainingScheduleTrainers as $baris) {
					$ketemu = array_search($baris->trainer_id, $daftarPengajar);
					if ($ketemu === false) {
						$daftarPengajar[] = $baris->trainer_id;
					}
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

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [];
					
					$programSubject = \backend\models\ProgramSubjectHistory::find()
						->where([
							'id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
							'program_id'=>$modelTraining->program_id,
							'program_revision'=>$modelTraining->program_revision,
							'status'=>1
						])
						->one();
					$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => $programSubject->name,
		    		];

		    		// koreksi kodeMP
		    		ksort($this->daftarMPperTanggal);
		    		foreach ($this->daftarMPperTanggal as $tanggal => $dataTanggal) {
		    			
		    			if ($tanggal != $pointerTanggal) {

		    				foreach ($dataTanggal as $subjectId => $dataSubject) {

			    				if ($subjectId == $trainingSchedule->trainingClassSubject->program_subject_id) {
		    						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP'] = $dataSubject['kodeMP'];
			    				}
		    				}
		    			}
		    			else {
		    				break;
		    			}
		    		}
		    		// dah

		    		// Benerin urutan kolom
		    		$kolomTertinggi = ord('E');

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
	    				foreach ($v as $baris) {
	    					if (ord($baris['kolom']) != '') {
    							$kolomTertinggi = ord($baris['kolom']);
    						}
	    				}
		    		}
		    		$kolomTertinggi += 1;

		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = chr($kolomTertinggi);
		    		// dah


		    		$objPHPExcel->getActiveSheet()->insertNewColumnBefore(chr($kolomTertinggi), 1);

					$objPHPExcel->getActiveSheet()->setCellValue(chr($kolomTertinggi).'8', 
						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP']
					);

					$nomorMPTerbesar += 1;

		    	}
	    		// Artinya pointer tanggal ga ada sama sekali
		    	else {

		    		$this->daftarMPperTanggal[$pointerTanggal] = [];

		    		$this->daftarMPperTanggal[$pointerTanggal] = [
		    			$trainingSchedule->trainingClassSubject->program_subject_id => []
		    		];
					
					$programSubject = \backend\models\ProgramSubjectHistory::find()
						->where([
							'id'=>$trainingSchedule->trainingClassSubject->program_subject_id,
							'program_id'=>$modelTraining->program_id,
							'program_revision'=>$modelTraining->program_revision,
							'status'=>1
						])
						->one();
		    		$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id] = [
		    			'kodeMP' => 'MP'.$nomorMPTerbesar,
		    			'kolom' => '',
		    			'namaMP' => $programSubject->name,
		    		];

		    		// Koreksi kodeMP
		    		ksort($this->daftarMPperTanggal);
		    		foreach ($this->daftarMPperTanggal as $tanggal => $dataTanggal) {
		    			
		    			if ($tanggal != $pointerTanggal) {

		    				foreach ($dataTanggal as $subjectId => $dataSubject) {

			    				if ($subjectId == $trainingSchedule->trainingClassSubject->program_subject_id) {
		    						$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kodeMP'] = $dataSubject['kodeMP'];
		    						$nomorMPTerbesar -= 1; // Perlu dikurang 1 sebab dibawah nanti MP nya pasti naik 1, padahal kode diatas cm ngopi kodeMP karena uda sama
			    				}
		    				}
		    			}
		    			else {
		    				break;
		    			}
		    		}
		    		// dah

		    		// Koreksi kolom
		    		$kolomTertinggi = ord('E');

		    		ksort($this->daftarMPperTanggal);

		    		foreach ($this->daftarMPperTanggal as $k => $v) {
	    				foreach ($v as $baris) {
	    					if (ord($baris['kolom']) != '') {
    							$kolomTertinggi = ord($baris['kolom']);
    						}
	    				}
		    		}
		    		// dah

		    		if ($kolomTertinggi == ord('E')) {
		    			$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'] = 'E';
		    		}
		    		else {
		    			$kolomTertinggi += 1;
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

			// Ngambil data trainer
			$modelTrainer = Trainer::find()
				->where([
					'person_id' => $daftarPengajar
				])
				->all();
			// dah
			
			// Ngisi data semua kolom, ngeloopnya per trainer
			foreach ($modelTrainer as $trainer) {
				
				// Insert row
				$objPHPExcel->getActiveSheet()->insertNewRowBefore($pointerBaris + 1, 1);
				// dah

				// Ngisi nomer urut
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBaris, $pointerBaris-8);
				// dah

				// Ngisi nama
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBaris, 
					$trainer->person->name
				);
				// dah

				// Ngisi nip
				$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$pointerBaris, 
					$trainer->person->nid, 
					PHPExcel_Cell_DataType::TYPE_STRING
				);
				// dah

				// Ngisi unit				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$pointerBaris, 
					$trainer->person->organisation
				);
				// dah

				// Ngisi kehadiran
				$pointerMP = 0;
				$pointerTanggal = '';
				$jumlahJPKehadiranMax = 0;
				foreach ($trainer->trainingScheduleTrainers as $trainerAttendance) {
					
					foreach ($trainingClass->trainingSchedules as $trainingSchedule) {

						$pointerTanggal = date('d-m-Y', strtotime($trainingSchedule->start));
					
						if ($trainerAttendance->training_schedule_id == $trainingSchedule->id) {

							// Ngambil JP ideal dari schedule lalu di tambahkan ke $jumlahJPKehadiranMax, nanti dipake buat ngitung total hadir, bolos, dan %
							$jumlahJPKehadiranMax += $trainingSchedule->hours;
							// dah

							// Ngisi keterangan
							$keteranganSebelumnya = $objPHPExcel->getActiveSheet()->getCell(chr($pointerKolomHadir + 3).$pointerBaris)->getValue();
							if ($trainerAttendance->reason != null or $trainerAttendance->reason != '') {
								if ($keteranganSebelumnya != '') {
									$keteranganSebelumnya .= ', '.$trainerAttendance->reason;
								}
								else {
									$keteranganSebelumnya = $trainerAttendance->reason;
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

								$nilai += $trainerAttendance->hours;
								
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$nilai
								);
							}
							else {
								$objPHPExcel->getActiveSheet()->setCellValue(
									$this->daftarMPperTanggal[$pointerTanggal][$trainingSchedule->trainingClassSubject->program_subject_id]['kolom'].$pointerBaris, 
									$trainerAttendance->hours
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
		$pointerBarisLegend = $pointerBaris + 4;
		$catat = [];
		foreach ($this->daftarMPperTanggal as $tanggal) {
			foreach ($tanggal as $baris) {
				$lewati = false;
				foreach ($catat as $kodeMP) {
					if ($baris['kodeMP'] == $kodeMP) {
						$lewati = true;
					}
				}
				if ($lewati == true) {
					continue;
				}
				$barisSebelumnya = $pointerBarisLegend -1 ;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$pointerBarisLegend, $baris['kodeMP']);
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('A'.$barisSebelumnya), 'A'.$pointerBarisLegend);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$pointerBarisLegend, $baris['namaMP']);
				$objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('B'.$barisSebelumnya), 'B'.$pointerBarisLegend);
				$catat[] = $baris['kodeMP'];
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
			if($kolomAkhir != 'E'){
				$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'6:'.$kolomAkhir.'6');
				$objPHPExcel->getActiveSheet()->mergeCells($kolomAwal.'7:'.$kolomAkhir.'7');
			}
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
		
		if($filetype=='xls') 
			header('Content-Type: application/vnd.ms-excel');
		else 
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			
		header('Content-Disposition: attachment;filename="recapitulation_pengajar_attendance_'.$modelTraining->activity->name.date('YmdHis').'.'.$filetype.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;

    }
	
	public function actionChangeClassStudent($id, $class_id, $training_class_student_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		$model = $this->findModelClassStudent($training_class_student_id);
		$renders = [];
		$renders['activity'] = $activity;
		$renders['class'] = $class;
		$renders['model'] = $model;
		$trainingClass = TrainingClass::find()
			->where([
				'training_id' => $activity->id
			])
			->all();
		$renders['trainingClass'] = $trainingClass;	
		if (Yii::$app->request->post()) {			
			$model->load(Yii::$app->request->post());
			if($model->save()){
				Yii::$app->getSession()->setFlash('success', 'Student have moved.');
				if (!Yii::$app->request->isAjax){
					return $this->redirect(['class-student', 'id' => $id, 'class_id'=>$class_id]);	
				}
				else{
					echo 'Student have moved.';
				}
			}
			else{
				Yii::$app->getSession()->setFlash('failed', 'Student have not moved.');
				if (!Yii::$app->request->isAjax) {
					return $this->redirect(['class-student', 'id' => $id, 'class_id'=>$class_id]);	
				}
				else{
					echo 'Student have not moved.';
				}
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('changeClassStudent', $renders);
            else
				return $this->render('changeClassStudent', $renders);
        }
    }
	
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionHonorarium($id)
    {
        $model = $this->findModel($id);
		$searchModel = new TrainingClassSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('honorarium', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	public function actionPrepareHonorarium($id, $class_id)
    {				
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		/* $dataProvider = new \yii\data\ActiveDataProvider([
			'query' => \backend\models\TrainingScheduleTrainer::find()
				->joinWith(['trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>\backend\models\TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
					'ref_trainer_type_id'=>[0], //Only PENGAJAR not ASISTEN & PENCERAMAH
				])
				->groupBy('tb_training_class_subject_id,tb_trainer_id'),				
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC]]
		]);
		$trainingClass=\backend\models\TrainingClass::findOne($tb_training_class_id);
		$sbu = \backend\models\Sbu::find()->where(['name'=>'honor_persiapan_mengajar','status'=>1])->one();
        return $this->render('prepare', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbu' => $sbu,
        ]); */
    }
	
	/* public function actionTransport($tb_training_class_id)
    {			
		$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
		$dataProvider = new ActiveDataProvider([
			'query' => TrainingScheduleTrainer::find()
				->select(TrainingScheduleTrainer::tableName().'.*,'.Employee::tableName().'.ref_satker_id')
				->joinWith(['trainer', 'trainer.employee','trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						//->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
				])
				->andWhere(
					'('.Employee::tableName().'.ref_satker_id IS NOT NULL AND '.Employee::tableName().'.ref_satker_id!='.$ref_satker_id.')'.
					' OR '.
					Employee::tableName().'.ref_satker_id IS NULL'
				)
				->groupBy('tb_training_class_subject_id,tb_trainer_id')
				,	
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC]]
		]);
		$trainingClass=TrainingClass::findOne($tb_training_class_id);
		$sbu = Sbu::find()->where(['name'=>'honor_transport_dalam_kota','status'=>1])->one();
        return $this->render('transport', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbu' => $sbu,
        ]);
    }
	
	public function actionTraining($tb_training_class_id)
    {			
		$ref_satker_id = Yii::$app->user->identity->employee->ref_satker_id;
		$dataProvider = new ActiveDataProvider([
			'query' => TrainingScheduleTrainer::find()
				->joinWith(['trainer', 'trainer.employee','trainingSchedule'])
				->where([
					'tb_training_schedule_id'=>TrainingSchedule::find()
						->select('id')
						->where([
							'tb_training_class_id'=>$tb_training_class_id,
							'status'=>1,					
						])
						->andWhere('tb_training_class_subject_id>0')
						//->groupBy('tb_training_class_subject_id')
						->column(),
					TrainingScheduleTrainer::tableName().'.status'=>1,
				])
				->groupBy('tb_training_class_subject_id,tb_trainer_id')
				,		
			'pagination' => [
				'pageSize' => 20,
			],
			'sort'=> ['defaultOrder' => ['tb_training_schedule_id'=>SORT_ASC,'ref_trainer_type_id'=>SORT_ASC]]
		]);

		$trainingClass=TrainingClass::findOne($tb_training_class_id);
		$sbus = ArrayHelper::map(Sbu::find()->where(['status'=>1])->all(),'name','value');
        return $this->render('training', [
			'dataProvider' => $dataProvider,
			'trainingClass' => $trainingClass, 
			'sbus' => $sbus,
        ]);
    } */
	
	public function actionSptjm($id, $class_id, $filetype='docx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			'trainer_id', 'tarif', 'jamlat', 'employee_id',
		]);
		$model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'required');
		$model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'integer');
	 
		if ($model->load(Yii::$app->request->post())) {
			if(!$model->validate()){
				return false;
			}
			/* == */
			try {
				$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'sptjm.'.$filetype;
				$OpenTBS->LoadTemplate($template); 
				//Header
				$satker_id = Yii::$app->user->identity->employee->satker_id;
				$satker = \backend\models\Reference::findOne($satker_id);
				$name_satker = strtoupper($satker->name);
				$address_satker = strtoupper($satker->satker->address.' '.$satker->satker->city);
				$phone_satker = $satker->satker->phone;
				$fax_satker = $satker->satker->fax;
				$web_satker = 'http://www.bppk.kemenkeu.go.id';
				$OpenTBS->VarRef['name_satker']= $name_satker;
				$OpenTBS->VarRef['address_satker']= $address_satker;
				$OpenTBS->VarRef['phone_satker']= $phone_satker;
				$OpenTBS->VarRef['fax_satker']= $fax_satker;
				$OpenTBS->VarRef['web_satker']= $web_satker;
				
				$bulan = [
					'','Januari','Februari','Maret','April',
					'Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember'
				];
				$OpenTBS->VarRef['year']= date('Y');
				$OpenTBS->VarRef['month']= $bulan[date('n')];
				// Get Employee TTD
				$employee = \backend\models\Employee::findOne($model->employee_id);
				if($employee!==null){
					$name_signer = $employee->person->name;
					$nip_signer = $employee->person->nip;
					$satker_id = $employee->satker_id;
					$satker = \backend\models\Reference::findOne($satker_id);
					$satker_signer = $satker->name;
				}
				else{
					$name_signer = "...";
					$nip_signer = "...";	
					$satker_signer = "...";					
				}
				$OpenTBS->VarRef['name_signer']= $name_signer;
				$OpenTBS->VarRef['nip_signer']= $nip_signer;
				$OpenTBS->VarRef['satker_signer']= $satker_signer;
				
				// Get Trainer
				$trainer = \backend\models\Trainer::findOne($model->trainer_id);
				if($trainer!==null){
					$name_trainer = $trainer->person->name;
				}
				else{
					$name_trainer = "...";				
				}
				$OpenTBS->VarRef['name_trainer']= $name_trainer;
				
				$OpenTBS->VarRef['name_training']= $activity->name;
				$OpenTBS->VarRef['year_training']= substr($activity->start,0,4);
				$OpenTBS->VarRef['cost_jp']= number_format($model->tarif,0,'.',',');
				$OpenTBS->VarRef['jp']= $model->jamlat; 
				$total_cost_jp = $model->tarif*$model->jamlat;
				$OpenTBS->VarRef['total_cost_jp']= number_format($total_cost_jp,0,'.',',');
				$OpenTBS->VarRef['text_total_cost_jp']=$this->numToText($total_cost_jp);
				$data = [];
				$OpenTBS->MergeBlock('data', $data);
				// Output the result as a file on the server. You can change output file
				$OpenTBS->Show(OPENTBS_DOWNLOAD, 'sptjm_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error');
			} 
		}
		
        return $this->renderAjax('sptjm', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	
	public function actionSkph($id, $class_id,  $filetype='docx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			'trainer_id', 'trainer_job', 'trainer_address',
			'employee_id', 'employee_job', 'employee_address',
		]);
		$model->addRule(['trainer_id', 'employee_id'], 'required');
		$model->addRule(['trainer_id', 'employee_id'], 'integer');
		$model->addRule(['trainer_job', 'trainer_address','employee_job', 'employee_address'], 'string');
	 
		if ($model->load(Yii::$app->request->post())) {
			if(!$model->validate()){
				return false;
			}
			/* == */
			try {
				$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'skph.'.$filetype;
				$OpenTBS->LoadTemplate($template);
				//Header
				$satker_id = Yii::$app->user->identity->employee->satker_id;
				$satker = \backend\models\Reference::findOne($satker_id);
				$name_satker = strtoupper($satker->name);
				$address_satker = strtoupper($satker->satker->address.' '.$satker->satker->city);
				$phone_satker = $satker->satker->phone;
				$fax_satker = $satker->satker->fax;
				$web_satker = 'http://www.bppk.kemenkeu.go.id';
				$OpenTBS->VarRef['name_satker']= $name_satker;
				$OpenTBS->VarRef['address_satker']= $address_satker;
				$OpenTBS->VarRef['phone_satker']= $phone_satker;
				$OpenTBS->VarRef['fax_satker']= $fax_satker;
				$OpenTBS->VarRef['web_satker']= $web_satker;
				
				$bulan = [
					'','Januari','Februari','Maret','April',
					'Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember'
				];
				$OpenTBS->VarRef['year']= date('Y');
				$OpenTBS->VarRef['month']= $bulan[date('n')];
				// Get Employee TTD
				$employee = \backend\models\Employee::findOne($model->employee_id);
				if($employee!==null){
					$name_employee = $employee->person->name;
					$nip_employee = $employee->person->nip;
					$satker_id = $employee->satker_id;
					$satker = \backend\models\Reference::findOne($satker_id);
					$satker_employee = $satker->name;
				}
				else{
					$name_employee = "...";
					$nip_employee = "...";	
					$satker_employee = "...";					
				}
				$OpenTBS->VarRef['name_employee']= $name_employee;
				$OpenTBS->VarRef['nip_employee']= $nip_employee;
				$OpenTBS->VarRef['satker_employee']= $satker_employee;
				$OpenTBS->VarRef['job_employee']= $model->employee_job;
				$OpenTBS->VarRef['address_employee']= $model->employee_address;
				
				// Get Trainer
				$trainer = \backend\models\Trainer::findOne($model->trainer_id);
				if($trainer!==null){
					$name_trainer = $trainer->person->name;
				}
				else{
					$name_trainer = "...";				
				}
				$OpenTBS->VarRef['name_trainer']= $name_trainer;
				$OpenTBS->VarRef['job_trainer']= $model->trainer_job;
				$OpenTBS->VarRef['address_trainer']= $model->trainer_address;
				
				$OpenTBS->VarRef['name_training']= $activity->name;
				$OpenTBS->VarRef['year_training']= substr($activity->start,0,4);
				/* $OpenTBS->VarRef['cost_jp']= number_format($model->tarif,0,'.',',');
				$OpenTBS->VarRef['jp']= $model->jamlat; 
				$total_cost_jp = $model->tarif*$model->jamlat;
				$OpenTBS->VarRef['total_cost_jp']= number_format($total_cost_jp,0,'.',',');
				$OpenTBS->VarRef['text_total_cost_jp']=$this->numToText($total_cost_jp); */
				$data = [];
				$OpenTBS->MergeBlock('data', $data);
				// Output the result as a file on the server. You can change output file
				$OpenTBS->Show(OPENTBS_DOWNLOAD, 'skph_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error');
			} 
		}
		
        return $this->renderAjax('skph', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	
	public function numToText($num){
		$num = abs($num);
		$angka = array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
		$temp = "";
		if($num < 12){
			$temp = " ".$angka[$num];
		}else if($num < 20){
			$temp = $this->numToText($num - 10)." belas";
		}else if($num < 100){
			$temp = $this->numToText($num/10)." puluh".$this->numToText($num%10);
		}else if ($num < 200) {
			$temp = " seratus".$this->numToText($num - 100);
		}else if ($num < 1000) {
			$temp = $this->numToText($num/100). " ratus". $this->numToText($num % 100);
		}else if ($num < 2000) {
			$temp = " seribu". $this->numToText($num - 1000);
		}else if ($num < 1000000) {
			$temp = $this->numToText($num/1000)." ribu". $this->numToText($num % 1000);
		}else if ($num < 1000000000) {
			$temp = $this->numToText($num/1000000)." juta". $this->numToText($num % 1000000);
		}

		return $temp;
	}

	public function actionRegistration($id, $class_id, $filetype='docx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			/* 'trainer_id', 'tarif', 'jamlat', 'employee_id', */
		]);
		/* $model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'required');
		$model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'integer'); */
	 
		if (Yii::$app->request->post()) {
			/* if(!$model->validate()){
				return false;
			} */
			/* == */
			try {
				$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'form_registrasi_peserta_diklat.'.$filetype;
				$OpenTBS->LoadTemplate($template); 
				//Header
				$satker_id = Yii::$app->user->identity->employee->satker_id;
				$satker = \backend\models\Reference::findOne($satker_id);
				$name_satker = strtoupper($satker->name);
				$address_satker = strtoupper($satker->satker->address.' '.$satker->satker->city);
				$phone_satker = $satker->satker->phone;
				$fax_satker = $satker->satker->fax;
				$web_satker = 'http://www.bppk.kemenkeu.go.id';
				$OpenTBS->VarRef['name_satker']= $name_satker;
				$OpenTBS->VarRef['address_satker']= $address_satker;
				$OpenTBS->VarRef['phone_satker']= $phone_satker;
				$OpenTBS->VarRef['fax_satker']= $fax_satker;
				$OpenTBS->VarRef['web_satker']= $web_satker;
				
				$bulan = [
					'','Januari','Februari','Maret','April',
					'Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember'
				];
				$OpenTBS->VarRef['year']= date('Y');
				$OpenTBS->VarRef['month']= $bulan[date('n')];
							
				// GET STATIC VARIABEL
				$name_training = $activity->name;
				$year_training = substr($activity->start,0,4);
				$executor = $name_satker;
				$date_training = \hscstudio\heart\helpers\Heart::twodate($activity->start,$activity->end);
				
				// GET STUDENT IN THIS CLASS
				$data = [];
				$searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				//$dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC];
				$dataProvider->setPagination(false);
				$i = 0;
				foreach($dataProvider->getModels() as $trainingClassStudent){
					$data[$i]['break'] = " ";
					$data[$i]['name_training'] = $name_training;
					$data[$i]['year_training'] = $year_training;
					$data[$i]['executor'] = $name_satker;
					$data[$i]['date_training'] = $date_training;
					$student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;
					$front_title = empty($person->front_title)?'':$person->front_title.' ';
					$back_title = empty($person->back_title)?'':', '.$person->back_title;					
					$data[$i]['name_student'] = $front_title.$person->name.$back_title;
					$data[$i]['nip_student'] = $person->nip;
					$data[$i]['born_student'] = $person->born;					
					$data[$i]['birthday_student'] = \hscstudio\heart\helpers\Heart::twodate($person->birthday);
					//GOL skip
					$rank_class="-";
					$or=\backend\models\ObjectReference::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'rank_class',							
						])
						->one();
					if($or!==null){
						$reference = $or->reference;
						if($reference!==null){
							$rank_class= $reference->name;							
						}
					}	
					$data[$i]['rank_class_student'] = $rank_class;
					
					$data[$i]['position_desc_student'] = $person->position_desc;
					//AGAMA skip
					$religion="-";
					$or=\backend\models\ObjectReference::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'religion',							
						])
						->one();
					if($or!==null){
						$reference = $or->reference;
						if($reference!==null){
							$religion= $reference->name;							
						}
					}	
					$data[$i]['religion_student'] = $religion;
					
					$eselon1="-";
					$or=\backend\models\ObjectReference::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'unit',							
						])
						->one();
					if($or!==null){
						$reference = $or->reference;
						if($reference!==null){
							$eselon1= $reference->name;							
						}
					}

					$photo="";
					$of=\backend\models\ObjectFile::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'photo',							
						])
						->one();
					if($of!==null){
						$file = $of->file;
						if($file!==null){
							$photo= $file->file_name;	
							$path = '';
							$object = 'person';
							$object_id = $student->person_id;
							if(isset(Yii::$app->params['uploadPath'])){
								$path = Yii::$app->params['uploadPath'].'/'.$object.'/'.$object_id.'/';
							}
							else{
								$path = Yii::getAlias('@file').'/'.$object.'/'.$object_id.'/';
							}
							$data[$i]['photo_student'] = $path.$photo;		
						}
					}	
					
					$data[$i]['eselon1_student'] = $eselon1;
					$data[$i]['eselon2_student'] = $student->eselon2;
					$data[$i]['eselon3_student'] = $student->eselon3;
					$data[$i]['eselon4_student'] = $student->eselon4;
					$data[$i]['tmt_student'] = $student->no_sk.' / '.$student->tmt_sk;
					
					$data[$i]['office_address_student'] = $person->office_address;
					$data[$i]['office_phone_student'] = $person->office_phone;
					$data[$i]['address_student'] = $person->address;
					$data[$i]['phone_student'] = $person->phone;
					$data[$i]['email_student'] = $person->email;
					$data[$i]['graduate_desc_student'] = $person->graduate_desc;
					$i++;
				}
				$OpenTBS->MergeBlock('data', $data);
				// Output the result as a file on the server. You can change output file
				$OpenTBS->Show(OPENTBS_DOWNLOAD, 'form_registrasi_peserta_diklat_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error');
			} 
		}
		
        return $this->renderAjax('registration', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	
	public function actionDeskplate($id, $class_id, $filetype='docx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			/* 'trainer_id', 'tarif', 'jamlat', 'employee_id', */
		]);
		/* $model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'required');
		$model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'integer'); */
	 
		if (Yii::$app->request->post()) {
			/* if(!$model->validate()){
				return false;
			} */
			/* == */
			try {
				$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'deskplate.'.$filetype;
				$OpenTBS->LoadTemplate($template); 
											
				// GET STATIC VARIABEL
				$name_training = $activity->name;
								
				// GET STUDENT IN THIS CLASS
				$data = [];
				$searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				//$dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC];
				$dataProvider->setPagination(false);
				$i = 0;
				foreach($dataProvider->getModels() as $trainingClassStudent){
					$data[$i]['number'] = $i;
					$data[$i]['name_training'] = $name_training;
					$student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;					
					$data[$i]['name_student'] = $person->name;
					$i++;
				}
				$OpenTBS->MergeBlock('data', $data);
				// Output the result as a file on the server. You can change output file
				$OpenTBS->Show(OPENTBS_DOWNLOAD, 'deskplate'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error');
			} 
		}
		
        return $this->renderAjax('deskplate', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	
	public function actionReceipt($id, $class_id, $filetype='xlsx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			/* 'trainer_id', 'tarif', 'jamlat', 'employee_id', */
		]);
		/* $model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'required');
		$model->addRule(['trainer_id', 'tarif', 'jamlat', 'employee_id'], 'integer'); */
	 
		if (Yii::$app->request->post()) {
			/* if(!$model->validate()){
				return false;
			} */
			/* == */
			try {				
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'receipt.'.$filetype;				
				$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
				$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
				$objPHPExcel = $objReader->load($template);
				$objPHPExcel->getProperties()->setTitle("Tanda Terima Bahan Ajar + ATK");
				$objPHPExcel->setActiveSheetIndex(0);
				$activeSheet = $objPHPExcel->getActiveSheet();
				
				$name_training = $activity->name;
				$year_training = substr($activity->start,0,4);
				$activeSheet->setCellValue('A2', strtoupper($name_training));
				$activeSheet->setCellValue('A3', 'KELAS '.$class->class.' TAHUN ANGGARAN '.$year_training);
				$searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				$dataProvider->setPagination(false);
				$idx=0;
				$baseRow = 12;
				foreach($dataProvider->getModels() as $trainingClassStudent){
					$row = $baseRow + $idx;
					if($idx!=0) $activeSheet->insertNewRowBefore($row,1);
					$student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;					
					$activeSheet->setCellValue('A'.$row, $idx+1)
								->setCellValue('B'.$row, $person->name)
								->setCellValue('E'.$row, ' '.$person->nip)
								;
					$activeSheet->mergeCells('B'.$row.':D'.$row);
					if(($idx+1)%2==1){
						$activeSheet->setCellValue('G'.$row,'=A'.$row);
					}
					else{
						$activeSheet->mergeCells('G'.($row-1).':G'.($row));
						$activeSheet->mergeCells('H'.($row-1).':H'.($row));
						$activeSheet->setCellValue('H'.($row-1),'=A'.$row);
					}
					$idx++;
				}			
				
				if(($idx+1)%2<>1){
					$row = $baseRow + $idx;
					$activeSheet->insertNewRowBefore($row,1);
					$activeSheet->mergeCells('B'.$row.':D'.$row);
					$activeSheet->mergeCells('G'.($row-1).':G'.($row));
					$activeSheet->mergeCells('H'.($row-1).':H'.($row));
				}
				// Redirect output to a client’s web browser
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="receipt.'.date('YmdHis').'.'.$filetype.'"');
				header('Cache-Control: max-age=0');
				$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
				$objWriter->save('php://output');
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error'.print_r($e));
			} 
		}
		
        return $this->renderAjax('receipt', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	
	public function actionFollowTraining($id, $class_id,  $filetype='docx')
    {
		$activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class	
		
		$model = new \yii\base\DynamicModel([
			'place_training', 'day_training', 'day_hours_training',
			'employee_id', 
		]);
		$model->addRule(['place_training', 'day_training', 'day_hours_training', 'employee_id'], 'required');
		$model->addRule(['day_training', 'employee_id', 'day_hours_training'], 'integer');
		$model->addRule(['place_training'], 'string');
	 
		if ($model->load(Yii::$app->request->post())) {
			if(!$model->validate()){
				return false;
			}
			/* == */
			try {
				$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
				}
				else{
					$path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
				}
				$template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'execution'.DIRECTORY_SEPARATOR;
				$template = $template_path . 'surat.keterangan.diklat.'.$filetype;
				$OpenTBS->LoadTemplate($template);
				//Header
				$satker_id = Yii::$app->user->identity->employee->satker_id;
				$satker = \backend\models\Reference::findOne($satker_id);
				$name_satker = $satker->name;
				$address_satker = $satker->satker->address.' '.$satker->satker->city;
				$phone_satker = $satker->satker->phone;
				$fax_satker = $satker->satker->fax;
				$web_satker = 'http://www.bppk.kemenkeu.go.id';
				$OpenTBS->VarRef['name_satker']= strtoupper($name_satker);
				$OpenTBS->VarRef['name_satker2']= $name_satker;
				$OpenTBS->VarRef['address_satker']= $address_satker;
				$OpenTBS->VarRef['phone_satker']= $phone_satker;
				$OpenTBS->VarRef['fax_satker']= $fax_satker;
				$OpenTBS->VarRef['web_satker']= $web_satker;
				
				$bulan = [
					'','Januari','Februari','Maret','April',
					'Mei','Juni','Juli','Agustus','September',
					'Oktober','November','Desember'
				];
				$OpenTBS->VarRef['year']= date('Y');
				$OpenTBS->VarRef['month']= $bulan[date('n')];
				// Get Employee TTD
				$employee = \backend\models\Employee::findOne($model->employee_id);
				if($employee!==null){
					$name_employee = $employee->person->name;
					$nip_employee = $employee->person->nip;
					$position_employee = 'Kepala ' . @$employee->organisation->NM_UNIT_ORG;
					$satker_id = $employee->satker_id;
					$satker = \backend\models\Reference::findOne($satker_id);
					$satker_employee = $satker->name;
				}
				else{
					$name_employee = "...";
					$nip_employee = "...";	
					$position_employee = "...";	
					$satker_employee = "...";					
				}
				$OpenTBS->VarRef['name_employee']= $name_employee;
				$OpenTBS->VarRef['nip_employee']= $nip_employee;
				$OpenTBS->VarRef['position_employee']= $position_employee;
				$OpenTBS->VarRef['satker_employee']= $satker_employee;
				
				$OpenTBS->VarRef['place_training']= $model->place_training;
				$OpenTBS->VarRef['day_training']= $model->day_training;
				$OpenTBS->VarRef['day_hours_training']= $model->day_hours_training;
				
				$OpenTBS->VarRef['name_training']= $activity->name;
				$OpenTBS->VarRef['year_training']= substr($activity->start,0,4);
				$date_training = \hscstudio\heart\helpers\Heart::twodate($activity->start,$activity->end);
				$OpenTBS->VarRef['date_training']= $date_training;
				
				//'place_training', 'day_training', 'day_hours_training',
				$OpenTBS->VarRef['total_hours_training']= $model->day_training * $model->day_hours_training;
				// GET STUDENT IN THIS CLASS
				$data = [];
				$searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				//$dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC];
				$dataProvider->setPagination(false);
				$i = 0;
				foreach($dataProvider->getModels() as $trainingClassStudent){
					$data[$i]['number'] = '';
					$student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;
					$front_title = empty($person->front_title)?'':$person->front_title.' ';
					$back_title = empty($person->back_title)?'':', '.$person->back_title;					
					$data[$i]['name_student'] = $front_title.$person->name.$back_title;
					$data[$i]['nip_student'] = $person->nip;
					//GOL skip
					$rank_class="-";
					$or=\backend\models\ObjectReference::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'rank_class',							
						])
						->one();
					if($or!==null){
						$reference = $or->reference;
						if($reference!==null){
							$rank_class= $reference->name;							
						}
					}	
					$data[$i]['rank_class_student'] = $rank_class;					
					$data[$i]['position_desc_student'] = $person->position_desc;
					
						
					$eselon1="-";
					$or=\backend\models\ObjectReference::find()
						->where([
							'object'=>'person',
							'object_id'=>$student->person_id,
							'type'=>'unit',							
						])
						->one();
					if($or!==null){
						$reference = $or->reference;
						if($reference!==null){
							$eselon1= $reference->name;							
						}
					}
					$data[$i]['eselon1_student'] = $eselon1;
					$data[$i]['eselon2_student'] = $student->eselon2;
					$data[$i]['eselon3_student'] = $student->eselon3;
					$data[$i]['eselon4_student'] = $student->eselon4;
					
					$data[$i]['office_address_student'] = $person->office_address;
					$data[$i]['office_phone_student'] = $person->office_phone;
					$data[$i]['address_student'] = $person->address;
					$data[$i]['phone_student'] = $person->phone;
					$data[$i]['email_student'] = $person->email;
					$data[$i]['graduate_desc_student'] = $person->graduate_desc; 
					$i++;
				}
				$OpenTBS->MergeBlock('data', $data);
				// Output the result as a file on the server. You can change output file
				$OpenTBS->Show(OPENTBS_DOWNLOAD, 'follow_training_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
				exit;
			} catch (\yii\base\ErrorException $e) {
				Yii::$app->session->setFlash('error', 'Unable export there are some error'.print_r($e));
			} 
		}
		
        return $this->renderAjax('followTraining', [
            'model' => $model,
            'activity' => $activity,
            'class' => $class,
        ]);
    }
	

}
