<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\Training;
use backend\models\Satker;
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
    public function actionIndex($year='',$status='nocancel',$satker_id=null)
    {
    	if ($satker_id == null) $satker_id = Yii::$app->user->identity->employee->satker_id;

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
		$dataProvider = $searchModel->search($queryParams,$satker_id);
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

		// Ngambil semua satker
		$satker = yii\helpers\ArrayHelper::map(
			Satker::find()
				->select('reference_id')
				->where([
					'eselon' => 3
				])
				->joinWith([
					'reference' => function ($query) {
						$query->select('id, name');
						$query->where([
							'type' => 'satker'
						]);
					}
				])
				->asArray()
				->all(),
			'reference_id', 'reference.name'
		);
		$satker[Yii::$app->user->identity->employee->satker_id] = Reference::findOne(Yii::$app->user->identity->employee->satker_id)->name;
		//dah
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
			'satker_id' => $satker_id,
			'satker' => $satker
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
		

        if (Yii::$app->request->isAjax) {
			return $this->renderAjax('view', [
	            'model' => $this->findModel($id),
				'dataProvider' => $dataProvider,
	        ]);
		}
		else {
			return $this->render('view', [
	            'model' => $this->findModel($id),
				'dataProvider' => $dataProvider,
	        ]);
		}
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
					if(!empty($model->location)) $model->location = implode('|',$model->location);
					$model->status =0;

					if($model->save()) {

						Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Diklat berhasil dibuat');

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

								Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Diklat berhasil dibuat');
								$transaction->commit();
								return $this->redirect(['index']);

							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Diklat tidak tersimpan');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Diklat gagal dibuat');
			}
        } 
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('create', $renders);
		else 
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
		
		if (Yii::$app->request->post()) { 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();	
			try{
				if($model->load(Yii::$app->request->post())){
					if (isset(Yii::$app->request->post()['create_revision'])){
						$model->create_revision = true;
					}
					$model->satker = 'current';
					
					$post = Yii::$app->request->post('Activity');
					if(!empty($post['location'])) {
						$model->location = implode('|',$model->location);
					}

                    if($model->save()) {
						Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Diklat berhasil diperbarui');
						if($training->load(Yii::$app->request->post())){							
							$training->activity_id= $model->id;
							if (isset(Yii::$app->request->post()['create_revision'])){
								$training->create_revision = true;
								
							}
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
								Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Diklat berhasil diperbarui');
								$transaction->commit();
								return $this->redirect(['index']);
							}
						}						
					}
					else{
						Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Diklat gagal diperbarui');
					}				
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Diklat gagal diperbarui');
			}
        }
		
		if (Yii::$app->request->isAjax) {
			return $this->renderAjax('update', $renders);
		}
		else {
			return $this->render('update', $renders);
		}
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
			Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Diklat berhasil dihapus');
		}
		else{
			Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Diklat gagal dihapus');
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
            throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
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
					Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Sebaran peserta berhasil disimpan');
					$training->student_count_plan = $studentCount;
					$training->save();
					$transaction->commit();
					return $this->redirect(['index-student-plan']);
				}				
				else{
					Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Sebaran peserta gagal disimpan');
				}
			}
			catch (Exception $e) {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Sebaran peserta gagal disimpan');
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
			'organisation_393'=>'PIC Diklat'
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
			Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> PIC berhasil diperbarui');
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['index']);
			}
			else{
				echo '<i class="fa fa-fw fa-check-circle"></i>Pic telah diperbarui';
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('pic', $renders);
            else
				return $this->render('pic', $renders);
        }
    }




	public function actionTogelApproveDiklat($training_id, $satker_id = null) {
		$modelTraining = Training::findOne($training_id);
		
		$modelTraining->approved_status = ($modelTraining->approved_status == 0) ? 1 : 0;
		$modelTraining->approved_date = date('Y-m-d H:i:s');
		$modelTraining->approved_by = Yii::$app->user->identity->employee->person_id;

		if ($modelTraining->save()) {
			if ($modelTraining->approved_status == 1) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Anda telah menyetujui '.$modelTraining->activity->name);
			} else {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Anda telah menarik perijinan '.$modelTraining->activity->name);
			}
		}
		else {
			if ($modelTraining->approved_status == 1) {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Gagal memberi persetujuan pada '.$modelTraining->activity->name);
			} else {
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Gagal menarik perijinan pada '.$modelTraining->activity->name);
			}
		}
		
		return $this->redirect(['index', 'satker_id' => $satker_id]);
	}
}