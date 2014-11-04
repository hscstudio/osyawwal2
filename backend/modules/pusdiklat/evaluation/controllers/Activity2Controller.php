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
use backend\modules\pusdiklat\evaluation\models\TrainingClassStudentPureSearch;

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
use yii\helpers\Json;
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
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							$training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id);
							
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Training & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['index']);
							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', 'Data is NOT saved.');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', 'Roolback transaction. Data is not saved');
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
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionClassStudent($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$tcsc=TrainingClassStudent::find()			
			->select('id,training_id,training_class_id,max(training_class_student_certificate.number) as max_number,max(training_class_student_certificate.seri) as max_seri')
			->joinWith('trainingClassStudentCertificate')			
			->where([
				'training_id' => $id,
			])
			->asArray()
			->one();
		$max_number = '0001';
		$max_seri = '0001';
		if(isset($tcsc['max_number'])){
			$max_number = (int)$tcsc['max_number'];
			$max_number = str_pad($max_number+1,4,'0',STR_PAD_LEFT);
			$max_seri = $tcsc['max_seri'];
			$max_seri = str_pad($max_seri+1,4,'0',STR_PAD_LEFT);
		}
	
		$searchModel = new TrainingClassStudentSearch();
		$queryParams['TrainingClassStudentSearch']=[				
			'training_class_id' =>$class_id,
			'training_class_student.status'=>1,
		];
		$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
		
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
			
			}
		}
		
        return $this->render('classStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
			'max_number' => $max_number,
			'max_seri' => $max_seri,
			
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
     * Creates a new TrainingClassStudentCertificate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetCertificateClass($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        

        if (Yii::$app->request->post()){
			$post = Yii::$app->request->post();
			$number = (int)$post['number'];
			$seri = (int)$post['seri'];
			$date = $post['date'];
			$date = date('Y-m-d',strtotime($date));
			$trainingClassStudents = TrainingClassStudent::find()
				->where([
					'training_id'=>$activity->id,
					'training_class_id'=>$class->id,
				])
				->all();
			foreach($trainingClassStudents as $trainingClassStudent){
				$trainingClassStudentCertificate = TrainingClassStudentCertificate::findOne($trainingClassStudent->id);
				if(null!=$trainingClassStudentCertificate){
					$trainingClassStudentCertificate->number = str_pad($number,4,'0',STR_PAD_LEFT);
					$trainingClassStudentCertificate->seri = str_pad($seri,4,'0',STR_PAD_LEFT);
					$trainingClassStudentCertificate->date = $date;
					$trainingClassStudentCertificate->status = 1;
				}
				else{
					$trainingClassStudentCertificate = new TrainingClassStudentCertificate([
							'training_class_student_id'=> $trainingClassStudent->id,
							'number'=>str_pad($number,4,'0',STR_PAD_LEFT),
							'seri'=>str_pad($seri,4,'0',STR_PAD_LEFT),
							'date'=>$date,
							'status'=>1,
						]);
				}
				
				$student = $trainingClassStudent->trainingStudent->student;
				$person = $student->person;
				$objectPerson = \backend\models\ObjectPerson::find()
					->where([
						'object'=>'person',
						'object_id'=>$person->id,
						'type'=>'graduate',
					])
					->one();
				if(null!=$objectPerson) $trainingClassStudentCertificate->graduate=$objectPerson->reference_id;
				$trainingClassStudentCertificate->graduate_desc=$person->graduate_desc;
				$trainingClassStudentCertificate->position=$person->position;
				$trainingClassStudentCertificate->position_desc=$person->position_desc;

				$trainingClassStudentCertificate->eselon3=$student->eselon3;
				$trainingClassStudentCertificate->eselon4=$student->eselon4;
				$trainingClassStudentCertificate->satker=$student->satker;
				
				$trainingClassStudentCertificate->save();
				$number++;
				$seri++;
			}
			
			Yii::$app->session->setFlash('success', 'Certificate created');           
        }
		
		return $this->redirect([
			'class-student',
			'id' => $id,
			'class_id'=>$class_id,
		]);
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
            $trainingClassStudent = TrainingClassStudent::findOne($training_class_student_id);
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
	
	
	public function actionPrintFrontendCertificate($id,$class_id,$filetype='docx'){
		$activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        try {
            $OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
            $path = '';
            if(isset(Yii::$app->params['uploadPath'])){
                $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
            }
            else{
                $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
            }
            $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
            $template = $template_path . 'skpp01.'.$filetype;
            $type_certificate = "SERTIFIKAT";
            $type_certificate_id = Yii::$app->request->post('type_certificate');
            if($type_certificate_id == 2){
                $template = $template_path . 'skpp01B.'.$filetype;
            }
            else if($type_certificate_id == 1){
                $type_certificate = "Surat Tanda Tamat Pendidikan dan Pelatihan";
            }
            $OpenTBS->LoadTemplate($template); 

            $data = [];
            $idx = 0;
            $number="";
            $trainingClassStudentCertificates = TrainingClassStudentCertificate::find()
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

                    $name_executor = $trainingClassStudentCertificate->trainingClassStudent->training->activity->satker->name;
                    $name_training = Yii::$app->request->post('name_training');
                    $location_training = Yii::$app->request->post('location_training');

                    $signer = (int)Yii::$app->request->post('signer');
					$city_signer = Yii::$app->request->post('city_signer');
                    $employee = \backend\models\Employee::findOne($signer);
                    if(!empty($employee)){
                        $name_signer = $employee->person->name;
                        $nip_signer = $employee->person->nip;
                        $position_signer = $employee->person->position_desc;                        
                    }
                    $idx++;
                }
				
				$trainingClassStudent = $trainingClassStudentCertificate->trainingClassStudent;
				$student = $trainingClassStudent->trainingStudent->student;
				$person = $student->person;
				$eselon = $student->satker;
				if(empty($eselon)) $eselon=1;
				$satker = [
					'1'=>$person->unit->reference->name.' ',
					'2'=>$student->eselon2.' ',
					'3'=>$student->eselon3.' ',
					'4'=>$student->eselon4.' ',
				];
				
				$instansi='-';
				if (strlen($satker[$eselon])>=3){
					$instansi = $satker[$eselon];
				}
				
				$object_file = ObjectFile::find()
					->where([
						'object'=>'person',
						'object_id'=>$person->id,
						'type'=>'photo',
					])
					->one();
				
				$photo='';
				if(!empty($object_file)){
					$photo = $path.'person'.DIRECTORY_SEPARATOR.$person->id.DIRECTORY_SEPARATOR.$object_file->file->file_name;
					if (!file_exists($photo) or strlen($object_file->file->file_name)<=3) $photo = '';
				}
                
				$rank_class = '';
				if(!empty($person->rankClass->reference)){
					$rank_class = $person->rankClass->reference->name;
				} 

                $data[] = [
                    // CERTIFICATE DATA
                    'type_certificate'=>$type_certificate,
                    'type_graduate'=>$type_graduate,
                    'seri'=>$trainingClassStudentCertificate->seri.$seri,
                    'number'=>$trainingClassStudentCertificate->number.$number,
                    'year_training'=>date('Y',strtotime($activity->start)),
                    'date_training'=>\hscstudio\heart\helpers\Heart::twodate(
                        date('Y-m-d',strtotime($activity->start)),
                        date('Y-m-d',strtotime($activity->end)),
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
                    'name_student'=>$person->name,
                    'nip_student'=>$person->nip,
                    'born_student'=>$person->born,
                    'birthDay_student'=>\hscstudio\heart\helpers\Heart::twodate($person->birthday),
                    'rankClass_student'=>$rank_class,
                    'position_student'=>$person->position_desc,
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
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_front_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
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

	public function actionPrintValueCertificate($id,$class_id,$filetype='docx'){
		$activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        try {
            // Initalize the TBS instance
            $OpenTBS = new \hscstudio\heart\extensions\OpenTBS;
            $path = '';
            if(isset(Yii::$app->params['uploadPath'])){
                $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
            }
            else{
                $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
            }
            $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
            $template = $template_path . 'skpp03.'.$filetype;
            $OpenTBS->LoadTemplate($template); 

            $idx = 0;
            $number="";
            $trainingClassStudentCertificates = TrainingClassStudentCertificate::find()
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
                }
                $idx++;
				
				$trainingClassStudent = $trainingClassStudentCertificate->trainingClassStudent;
				$student = $trainingClassStudent->trainingStudent->student;
				$person = $student->person;
				$eselon = $student->satker;
				if(empty($eselon)) $eselon=1;
				$satker = [
					'1'=>$person->unit->reference->name.' ',
					'2'=>$student->eselon2.' ',
					'3'=>$student->eselon3.' ',
					'4'=>$student->eselon4.' ',
				];
				
				$instansi='-';
				if (strlen($satker[$eselon])>=3){
					$instansi = $satker[$eselon];
				}
				
                $data1[] = [
                    'idx' => $idx,
                    'number'=>$trainingClassStudentCertificate->number.$number,
                    'name_student'=>$person->name,
                    'nip_student'=>$person->nip,
                    'satker_student'=>$instansi,
                ];
            }
            $OpenTBS->MergeBlock('data1', $data1);
            $programSubjectHistories = \backend\models\ProgramSubjectHistory::find()
                ->where([
                    'program_id' => $activity->training->program_id,
                    'program_revision' => $activity->training->program_revision,
                    'status'=>1,
                ])
				->orderBy('sort ASC')
                ->all();
            $idx1 = 1;
            $idx2 = 1;
            $idx3 = 1;
            $data2=[];
            $data3=[];
            $data4=[];
            foreach($programSubjectHistories as $programSubjectHistory){
                if(!in_array($programSubjectHistory->type,[109,110])){ /* CEK tabel reference MP & CERAMAH */
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
			$city_signer = Yii::$app->request->post('city_signer');
			$date_signer = Yii::$app->request->post('date_signer');
			$name_signer = '';
			$nip_signer = '';
			$position_signer = '';
			$employee = \backend\models\Employee::findOne($signer);
			if(!empty($employee)){
				$name_signer = $employee->person->name;
				$nip_signer = $employee->person->nip;
				$position_signer = $employee->person->position_desc;
			}
			
            $data[] = [
				'name_training'=>$activity->name,
				'year_training'=>substr($activity->start,0,4),
				'city_signer'=>$city_signer,
				'date_signer'=>\hscstudio\heart\helpers\Heart::twodate($date_signer),
				'position_signer'=>$position_signer,
				'name_signer'=>$name_signer,
				'nip_signer'=>$nip_signer,
			];
			$OpenTBS->MergeBlock('data', $data);

            // Output the result as a file on the server. You can change output file
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_value_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
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

	public function actionPrintBackendCertificate($id,$class_id,$filetype='docx'){
		$activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        try {
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
            $template = $template_path . 'skpp02.'.$filetype;
            $OpenTBS->LoadTemplate($template); 
            $data = [];
            $training = $activity->training;
            $programSubjectHistories = \backend\models\ProgramSubjectHistory::find()
                ->where([
                    'program_id' => $training->program_id,
                    'program_revision' => $training->program_revision,
                    'status'=>1,
                ])
				->orderBy('sort ASC')
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
			$city_signer = Yii::$app->request->post('city_signer');
			$date_signer = Yii::$app->request->post('date_signer');
			$name_signer = '';
			$nip_signer = '';
			$position_signer = '';
			$employee = \backend\models\Employee::findOne($signer);
			if(!empty($employee)){
				$name_signer = $employee->person->name;
				$nip_signer = $employee->person->nip;
				$position_signer = $employee->person->position_desc;
			}
			
			$data2[] = [
                'city_signer'=>$city_signer,
				'date_signer'=>\hscstudio\heart\helpers\Heart::twodate($date_signer),
				'position_signer'=>$position_signer,
				'name_signer'=>$name_signer,
				'nip_signer'=>$nip_signer,
			];
			$OpenTBS->MergeBlock('data2', $data2);
           
            // Output the result as a file on the server. You can change output file
            $OpenTBS->Show(OPENTBS_DOWNLOAD, 'certificate_back_'.date('YmdHis').'.'.$filetype); // Also merges all [onshow] automatic fields.
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

    public function actionPrintStudentChecklist($id,$class_id,$filetype='xlsx'){
		$activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        try {
            if(in_array($filetype,['xlsx','xls'])){
                $types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
                $objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
                $path = '';
                if(isset(Yii::$app->params['uploadPath'])){
                    $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                }
                else{
                    $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
                }
                $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
                $template = $template_path . 'student.checklist.'.$filetype;
                $objPHPExcel = $objReader->load($template);
                $objPHPExcel->setActiveSheetIndex(0);
                $activeSheet = $objPHPExcel->getActiveSheet();
                $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                $objPHPExcel->getProperties()->setTitle("Student Checklist");
                $training=$activity->training;

                $idx=1;
                $baseRow = 7; // start line 27

                $searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
				$dataProvider->setPagination(false);
				
                /* $trainingClassStudents=\backend\models\TrainingClassStudent::find()
                    ->where([
                        'training_id' => $activity->id,
                        'training_class_id' => $class->id,
                        'status'=>1,
                    ])
                    ->all(); */
				foreach($dataProvider->getModels() as $trainingClassStudent){	
					$row = ($idx+$baseRow)-1;
                    if($idx==1){
                        $activeSheet->setCellValue('A2', $activity->name)
                            ->setCellValue('A3', 'KELAS '. $trainingClassStudent->trainingClass->class)
                            ->setCellValue('A4', 'TAHUN ANGGARAN '. substr($activity->start,0,4));
                    }
					else{
						$activeSheet->insertNewRowBefore($row,1);
					}
                    $student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;
                    
                    $eselon = $student->satker;
					if(empty($eselon)) $eselon=1;
                    $satker = [
                        '1'=>$person->unit->reference->name.' ',
                        '2'=>$student->eselon2.' ',
                        '3'=>$student->eselon3.' ',
                        '4'=>$student->eselon4.' ',
                    ];
					
                    $studentSatker='-';
                    if (strlen($satker[$eselon])>=3){
                        $studentSatker = $satker[$eselon];
                    }
					
					$rank_class = '';
					if(!empty($person->rankClass->reference)){
						$rank_class = $person->rankClass->reference->name;
					} 
                    $activeSheet->setCellValue('A'.$row, $idx)
                        ->setCellValue('B'.$row, $person->name)
                        ->setCellValue('C'.$row, $person->nip.' ')
                        ->setCellValue('D'.$row, $person->born.', '.date('d-m-Y',strtotime($person->birthday)))
                        ->setCellValue('E'.$row, $rank_class)
                        ->setCellValue('F'.$row, $person->position_desc)
                        ->setCellValue('G'.$row, $studentSatker)
                        ->setCellValue('H'.$row, $person->email)
                        ->setCellValue('I'.$row, $person->phone)
                    ;
					
					if(($idx)%2==1){
						$activeSheet->setCellValue('J'.$row,'=A'.$row);
					}
					else{
						$activeSheet->mergeCells('J'.($row-1).':J'.($row))
													->mergeCells('K'.($row-1).':K'.($row))
													->setCellValue('K'.($row-1),'=A'.$row);
					}
                    $idx++;
                }

                // Redirect output to a client?s web browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="student.checklist.'.date('YmdHis').'.'.$filetype.'"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
                $objWriter->save('php://output');
                exit;
            }
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'class-student',
            'id'=>$id,
            'class_id'=>$class_id,
        ]);
    }
	
	public function actionPrintStudentChecklistPhoto($id,$class_id,$filetype='xlsx'){
		$activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
        try {
            if(in_array($filetype,['xlsx','xls'])){
                $types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
                $objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
                $path = '';
                if(isset(Yii::$app->params['uploadPath'])){
                    $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                }
                else{
                    $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
                }
                $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
                $template = $template_path . 'student.checklist.photo.'.$filetype;
                $objPHPExcel = $objReader->load($template);
                $objPHPExcel->setActiveSheetIndex(0);
                $activeSheet = $objPHPExcel->getActiveSheet();
                $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                $objPHPExcel->getProperties()->setTitle("Student Checklist");
                $training=$activity->training;

                $idx=1;
                $baseRow = 7; // start line 27
				
				$searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
				$dataProvider->setPagination(false);
				
                /* $trainingClassStudents=\backend\models\TrainingClassStudent::find()
                    ->where([
                        'training_id' => $activity->id,
                        'training_class_id' => $class->id,
                        'status'=>1,
                    ])
                    ->all(); */
				foreach($dataProvider->getModels() as $trainingClassStudent){	
					$row = ($idx+$baseRow)-1;
                    if($idx==1){
                        $activeSheet->setCellValue('A2', $activity->name)
                            ->setCellValue('A3', 'KELAS '. $trainingClassStudent->trainingClass->class)
                            ->setCellValue('A4', 'TAHUN ANGGARAN '. substr($activity->start,0,4));
                    }
					else{
						$activeSheet->insertNewRowBefore($row,1);
					}
                    $student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;
                    
                    $eselon = $student->satker;
					if(empty($eselon)) $eselon=1;
                    $satker = [
                        '1'=>$person->unit->reference->name.' ',
                        '2'=>$student->eselon2.' ',
                        '3'=>$student->eselon3.' ',
                        '4'=>$student->eselon4.' ',
                    ];
					
                    $studentSatker='-';
                    if (strlen($satker[$eselon])>=3){
                        $studentSatker = $satker[$eselon];
                    }
					
					$rank_class = '';
					if(!empty($person->rankClass->reference)){
						$rank_class = $person->rankClass->reference->name;
					} 
                    $activeSheet->setCellValue('A'.$row, $idx)
                        ->setCellValue('B'.$row, $person->name)
                        ->setCellValue('C'.$row, $person->nip.' ')
                        ->setCellValue('D'.$row, $person->born.', '.date('d-m-Y',strtotime($person->birthday)))
                        ->setCellValue('E'.$row, $rank_class)
                        ->setCellValue('F'.$row, $person->position_desc)
                        ->setCellValue('G'.$row, $studentSatker)
                        ->setCellValue('H'.$row, $person->email)
                        ->setCellValue('I'.$row, $person->phone)
                    ;
					
					$object_file = ObjectFile::find()
						->where([
							'object'=>'person',
							'object_id'=>$person->id,
							'type'=>'photo',
						])
						->one();
					
					$photo='';
					if(!empty($object_file)){
						$photo = $path.'person'.DIRECTORY_SEPARATOR.$person->id.DIRECTORY_SEPARATOR.$object_file->file->file_name;
						if (file_exists($photo) or strlen($object_file->file->file_name)>=3){
							$gdImage = imagecreatefromjpeg($photo);
							// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
							$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
							$objDrawing->setName('Sample image');
							$objDrawing->setDescription('Sample image');
							$objDrawing->setImageResource($gdImage);
							$objDrawing->setRenderingFunction(\PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
							$objDrawing->setMimeType(\PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
							$objDrawing->setHeight(120);
							if(($idx)%2==1){
								$objDrawing->setCoordinates('L'.$row);
							}
							else{
								$objDrawing->setCoordinates('M'.$row);
							}
							$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
						}
					}
					

					if(($idx)%2==1){
						$activeSheet->setCellValue('J'.$row,'=A'.$row);
					}
					else{
						
						$activeSheet->mergeCells('J'.($row-1).':J'.($row))
									->mergeCells('K'.($row-1).':K'.($row))
									->setCellValue('K'.($row-1),'=A'.$row)
									;
					}
                    $idx++;
                }

                // Redirect output to a client?s web browser
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="student.checklist.'.date('YmdHis').'.'.$filetype.'"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
                $objWriter->save('php://output');
                exit;
            }
        } catch (\yii\base\ErrorException $e) {
            Yii::$app->session->setFlash('error', 'Unable export there are some error');
        }

        return $this->redirect([
            'class-student',
            'id'=>$id,
            'class_id'=>$class_id,
        ]);
    }

	public function actionPrintCertificateReceipt($id,$class_id,$filetype='xlsx'){
        $activity = $this->findModel($id); // Activity
        $class = $this->findModelClass($class_id); // Class
		try {
            if(in_array($filetype,['xlsx','xls'])){
                $types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
                $objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
                $path = '';
                if(isset(Yii::$app->params['uploadPath'])){
                    $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                }
                else{
                    $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
                }
                $template_path = $path . 'template'.DIRECTORY_SEPARATOR.'pusdiklat'.DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR;
                $template = $template_path . 'certificate.receipt.'.$filetype;
                $objPHPExcel = $objReader->load($template);
                $objPHPExcel->setActiveSheetIndex(0);
                $activeSheet = $objPHPExcel->getActiveSheet();
                $activeSheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                $activeSheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                $objPHPExcel->getProperties()->setTitle("Tanda Terima Sertifikat");
                $training=$activity->training;

                $idx=1;
                $baseRow = 12; // start line 12
                $days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
                $months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
                $searchModel = new TrainingClassStudentSearch();
				$queryParams['TrainingClassStudentSearch']=[				
					'training_class_id' =>$class_id,
					'training_class_student.status'=>1,
				];
				$queryParams=\yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
				$dataProvider = $searchModel->search($queryParams); 
				/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
				$dataProvider->setPagination(false);
				
                /* $trainingClassStudents=\backend\models\TrainingClassStudent::find()
                    ->where([
                        'training_id' => $activity->id,
                        'training_class_id' => $class->id,
                        'status'=>1,
                    ])
                    ->all(); */
				foreach($dataProvider->getModels() as $trainingClassStudent){	
					$row = ($idx+$baseRow)-1;
                    if($idx==1){
						$month=$months[date("n")-1];
                        $day=$days[date("w")];
                        $activeSheet->setCellValue('C6', $activity->name.' T.A '.substr($activity->start,0,4).' KELAS '. 
							$trainingClassStudent->trainingClass->class)
                            ->setCellValue('C8',' '.$day.'. '.date('d').' '.$month.' '.date('Y'));
						$activeSheet->setCellValue('E18',Yii::$app->user->identity->employee->person->name)
									->setCellValue('E19','NIP '.Yii::$app->user->identity->employee->person->nip);
                    }
					else{
						$activeSheet->insertNewRowBefore($row,1);
					}
                    $student = $trainingClassStudent->trainingStudent->student;
					$person = $student->person;
                    $eselon = $student->satker;
					if(empty($eselon)) $eselon=1;
                    $satker = [
                        '1'=>$person->unit->reference->name.' ',
                        '2'=>$student->eselon2.' ',
                        '3'=>$student->eselon3.' ',
                        '4'=>$student->eselon4.' ',
                    ];
					
                    $studentSatker='-';
                    if (strlen($satker[$eselon])>=3){
                        $studentSatker = $satker[$eselon];
                    }
					
					$rank_class = '';
					if(!empty($person->rankClass->reference)){
						$rank_class = $person->rankClass->reference->name;
					} 
                    $activeSheet->setCellValue('A'.$row, $idx)
                        ->setCellValue('B'.$row, $person->name)
                        ->setCellValue('C'.$row, $person->nip.' ')
                        ->setCellValue('D'.$row, $studentSatker)
                    ;

					if(($idx)%2==1){
						$activeSheet->setCellValue('E'.$row,'=A'.$row);
					}
					else{
						$activeSheet->mergeCells('E'.($row-1).':E'.($row))
													->mergeCells('F'.($row-1).':F'.($row))
													->setCellValue('F'.($row-1),'=A'.$row);
					}
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
            'class-student',
            'id'=>$id,
            'class_id'=>$class_id,
        ]);
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
			DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR.'training.list.'.$filetype;
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






    public function actionNilaiAkhir($id) {
    	// Bikin data provider student dari class schedule
		$searchModel = new TrainingClassStudentPureSearch(); 

		$queryParams['TrainingClassStudentPureSearch'] = [
			'training_id' => $id
		];

		$queryParams = ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);

		$dataProvider = $searchModel->search($queryParams);
		// dah

		// Ngambil model training
		$modelTraining = Training::find()
			->where([
				'activity_id' => $id
			])
			->one();
		// dah

		return $this->render('finalscore', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'modelTraining' => $modelTraining
        ]);
    }





    public function actionEditableNilaiAkhir() {

		// Cuma ajax yg boleh manggil fungsi ni
		if (Yii::$app->request->isAjax == false) {
			Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Forbidden!');
			return $this->redirect(['activity/index']);
		}
		// dah

		$modelTrainingClassStudent = TrainingClassStudent::find()
			->where([
				'id' => Yii::$app->request->post('training_class_student_id'),
			])
			->one();

		if (!empty($modelTrainingClassStudent)) {

			$modelTrainingClassStudent->test = Yii::$app->request->post('test');

			if ($modelTrainingClassStudent->save()) {

				echo Json::encode(['test' => $modelTrainingClassStudent->test, 'error' => '']);

			}
			else {

				echo Json::encode(['test' => 'Tidak bisa menyimpan nilai!', 'error' => 'error']);

			}
			
		}
		else {
			
			echo Json::encode(['test' => 'Peserta diklat tidak ada!'.Yii::$app->request->post('training_class_student_id'), 'error' => 'error']);

		}

	}
}
