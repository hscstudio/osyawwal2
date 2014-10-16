<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Program;
use backend\models\Reference;
use backend\models\ObjectPerson;
use backend\modules\pusdiklat\planning\models\TrainingActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
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
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		
        $query = ActivityHistory::find()
			->joinWith('training')
			->where([
				'satker_id' => $satker_id,
				'id' => $id,
			]);
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		$dataProvider->getSort()->defaultOrder = ['revision'=>SORT_DESC];
		
		return $this->render('view', [
            'model' => $this->findModel($id),
			'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();		
		$training = new Training();
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
					$model->status =0;									
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							$training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id);
							// GENERATE TRAINING NUMBER
							$year = date('Y',strtotime($model->start));
							$program = Program::find()->where(['id'=>$training->program_id])->currentSatker()->active()->one();
							$program_owner = sprintf("%02s", $program->satker->sort);
							$activity_owner = sprintf("%02s", $model->satker->sort);							
							if($program_owner==$activity_owner) $activity_owner='00';
							$program_number = $program->number;
							$training_of_program_this_year = Activity::find()
								->where('start<=:start and YEAR(start)=:this_year',[':start'=>$model->start,':this_year'=>$year])			
								->currentSatker()
								->active()
								->count()+1;
							$training->number = $year.'-'.$program_owner.'-'.$activity_owner.'-'.$program_number.'.'.$training_of_program_this_year;
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Training & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['view', 'id' => $model->id]);
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
		
		return $this->render('create', $renders);
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
					if (isset(Yii::$app->request->post()['create_revision'])){
						$model->create_revision = true;
					}
					$model->satker = 'current';
					$model->location = implode('|',$model->location);									
					if($model->save()) {
						Yii::$app->getSession()->setFlash('success', 'Activity data have saved.');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							if (isset(Yii::$app->request->post()['create_revision'])){
								$training->create_revision = true;
								
							}
							/* $training->program_revision = (int)\backend\models\ProgramHistory::getRevision($training->program_id); */
							// GENERATE TRAINING NUMBER
							if(Yii::$app->request->post('generate_number')){
								$year = date('Y',strtotime($model->start));
								$program = Program::find()->where(['id'=>$training->program_id])->currentSatker()->active()->one();
								$program_owner = sprintf("%02s", $program->satker->sort);
								$activity_owner = sprintf("%02s", $model->satker->sort);							
								if($program_owner==$activity_owner) $activity_owner='00';
								$program_number = $program->number;
								$training_of_program_this_year = Activity::find()
									->where('start<=:start and YEAR(start)=:this_year',[':start'=>$model->start,':this_year'=>$year])			
									->currentSatker()
									->active()
									->count()+1;
								$training->number = $year.'-'.$program_owner.'-'.$activity_owner.'-'.$program_number.'.'.$training_of_program_this_year;
							}
							
							if($training->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Training & activity data have saved.');
								$transaction->commit();
								return $this->redirect(['view', 'id' => $model->id]);
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
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if($this->findModel($id)->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
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
	
	 public function actionUpdateStudentPlan($id)
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
		if (Yii::$app->request->post()){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{	
				$trainingStudentPlan->load(Yii::$app->request->post());	
				$studentCount = array_sum($trainingStudentPlan->spread);
				$trainingStudentPlan->jsonSpread = $trainingStudentPlan->spread;
				$trainingStudentPlan->status = 1;					
				if($trainingStudentPlan->save()){
					Yii::$app->getSession()->setFlash('success', 'Student spread data have saved.');
					$training->student_count_plan = $studentCount;
					$training->save();
					$transaction->commit();
					return $this->redirect(['index-student-plan']);
				}				
				else{
					Yii::$app->getSession()->setFlash('error', 'Data is NOT saved.');
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', 'Roolback transaction. Data is not saved');
			}
        } 
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('updateStudentPlan', $renders);
		else
			return $this->render('updateStudentPlan', $renders);
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
			// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
			'organisation_393'=>'PIC Training'
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
}