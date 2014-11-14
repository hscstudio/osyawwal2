<?php

namespace backend\modules\pusdiklat2\competency\controllers\planning;

use Yii;
use backend\models\Activity;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Program;
use backend\models\Reference;
use backend\models\ObjectPerson;
use backend\modules\pusdiklat2\competency\models\planning\TrainingActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
use backend\models\ProgramSubject;
use backend\models\ProgramSubjectHistory;
use backend\models\TrainingSubjectTrainerRecommendation;
use backend\modules\pusdiklat2\competency\models\planning\TrainingSubjectTrainerRecommendationSearch;
use yii\data\ActiveDataProvider;
use backend\models\Trainer;
use backend\modules\pusdiklat2\competency\models\planning\TrainerSearch;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class Activity3Controller extends Controller
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
			//1213020300 CEK KD_UNIT_ORG 1213020300 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
			'organisation_1213020300'=>'PIC TRAINING ACTIVITY [BIDANG TENAGA PENGAJAR]'
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
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubject($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		
		$query = ProgramSubjectHistory::find()
			->where([
				'program_id' => $model->training->program_id,
				'program_revision' => $model->training->program_revision,
				'status'=>1,
			])
			->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,]);			
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		//$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;
				
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('subject', $renders);
		else
			return $this->render('subject', $renders);
    } 
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubjectTrainer($id,$subject_id)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubjectHistory::find()
			->where([
				'id' => $subject_id,
				'program_id' =>  $model->training->program_id,
				'program_revision' => $model->training->program_revision,
			])
			->one();
		$renders = [];
		$renders['model'] = $model;		
		$renders['program_subject'] = $program_subject;	
		$searchModel = new TrainingSubjectTrainerRecommendationSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingSubjectTrainerRecommendationSearch']=[
			'training_id' => $model->id,
			'program_subject_id' => $subject_id,
		];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['status'=>SORT_DESC,'sort'=>SORT_ASC];
		$renders['searchModel']=$searchModel;
		$renders['dataProvider']=$dataProvider;
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('subjectTrainer', $renders);
		else
			return $this->render('subjectTrainer', $renders);				
		
    } 
	
	/**
     * Finds the TrainingSubjectTrainerRecommendation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingSubjectTrainerRecommendation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelSubjectTrainer($id)
    {
        if (($model = TrainingSubjectTrainerRecommendation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Displays a single TrainingSubjectTrainerRecommendation model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewSubjectTrainer($id)
    {
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('viewSubjectTrainer', [
				'model' => $this->findModelSubjectTrainer($id),
			]);
		else
			return $this->render('viewSubjectTrainer', [
				'model' => $this->findModelSubjectTrainer($id),
			]);

    }
	
	/**
     * Updates an existing TrainingSubjectTrainerRecommendation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateSubjectTrainer($id)
    {
        $model = $this->findModelSubjectTrainer($id);
		$program_subject = ProgramSubjectHistory::find()
			->where([
				'id' => $subject_id,
				'program_id' =>  $model->training->program_id,
				'program_revision' => $model->training->program_revision,
			])
			->one();
        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['subject-trainer', 'id' => $model->training_id, 'subject_id' => $model->program_subject_id]);
        } else {
            return $this->render('updateSubjectTrainer', [
                'model' => $model,
				'program_subject' => $program_subject,
            ]);
        }
    }
	
	 /**
     * Deletes an existing TrainingSubjectTrainerRecommendation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteSubjectTrainer($id)
    {
		$model = $this->findModelSubjectTrainer($id);
		$renders = [
			'subject-trainer', 'id' => $model->training_id, 'subject_id' => $model->program_subject_id
		];
		if($model->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect($renders);
    }
	
	/**
     * Lists all Trainer models.
     * @return mixed
     */
    public function actionChooseTrainer($id,$subject_id)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubjectHistory::find()
			->where([
				'id' => $subject_id,
				'program_id' =>  $model->training->program_id,
				'program_revision' => $model->training->program_revision,
			])
			->one();
		$renders = [];
		$renders['model'] = $model;		
		$renders['program_subject'] = $program_subject;	
        $searchModel = new TrainerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		$renders['searchModel']=$searchModel;
		$renders['dataProvider']=$dataProvider;
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('chooseTrainer', $renders);
		else
			return $this->render('chooseTrainer', $renders);
    }
	
	/**
     * Creates a new Trainer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSetTrainer($id,$subject_id,$trainer_id)
    {
        $model = $this->findModel($id);
		$program_subject = ProgramSubjectHistory::find()
			->where([
				'id' => $subject_id,
				'program_id' =>  $model->training->program_id,
				'program_revision' => $model->training->program_revision,
			])
			->one();
		$renders = [];
		$renders['model'] = $model;		
		$renders['program_subject'] = $program_subject;	
		$recommendation = new TrainingSubjectTrainerRecommendation([
			'training_id'=>$id,
			'program_subject_id'=>$subject_id,
			'trainer_id'=>$trainer_id
		]);
		$renders['recommendation'] = $recommendation;	
		
		$trainer = Trainer::findOne($trainer_id);
		$renders['trainer'] = $trainer;		

        if (Yii::$app->request->post()){ 
			$recommendation->load(Yii::$app->request->post());
			$recommendation->status = 1;
			if($recommendation->save()) {
				Yii::$app->getSession()->setFlash('success', 'Trainer have recommendate.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Trainer have not recommendate.');
			}
            return $this->redirect(['choose-trainer','id'=>$id,'subject_id'=>$subject_id]);
        } else {
			if (Yii::$app->request->isAjax){
				return $this->renderAjax('setTrainer', $renders);
			}
			else{
				return $this->render('setTrainer', $renders);
			}
        }
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
			DIRECTORY_SEPARATOR.'planning'.DIRECTORY_SEPARATOR.'training.list.'.$filetype;
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
