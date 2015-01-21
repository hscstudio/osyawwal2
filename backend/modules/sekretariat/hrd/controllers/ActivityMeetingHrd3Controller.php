<?php

namespace backend\modules\sekretariat\hrd\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\ActivityRoom;
use backend\models\Room;
use backend\models\Meeting;
use backend\models\Reference;
use backend\models\ObjectPerson;
use backend\modules\sekretariat\hrd\models\ActivityMeetingHrdSearch;
use backend\modules\sekretariat\hrd\models\RoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * ActivityMeetingHrdController implements the CRUD actions for Activity model.
 */
class ActivityMeetingHrd3Controller extends Controller
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
    public function actionIndex($year='',$status='all')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivityMeetingHrdSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$organisation_id = 8;
		if($status=='all'){
			if($year=='all'){
				$queryParams['ActivityMeetingHrdSearch']=[
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivityMeetingHrdSearch']=[
					'year' => $year,
					'organisation_id' => $organisation_id
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['ActivityMeetingHrdSearch']=[
					'status' => $status,
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivityMeetingHrdSearch']=[
					'year' => $year,
					'status' => $status,
					'organisation_id' => $organisation_id
				];
			}
		}
		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_meeting = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->asArray()
			->all(), 'year', 'year');
		$year_meeting['all']='All'	;
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_meeting' => $year_meeting,
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id,$organisation_id=NULL)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		
        $query = ActivityHistory::find()
			->joinWith('meeting')
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
		$meeting = new Meeting();
		$renders=[];
		$renders['model'] = $model;
		$renders['meeting'] = $meeting;
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
						if($meeting->load(Yii::$app->request->post())){							
							$meeting->activity_id= $model->id;	
							$meeting->organisation_id = 8;							
							if($meeting->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Meeting & activity data have saved.');
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
		$meeting = Meeting::findOne([
			'activity_id'=>$model->id,
			'organisation_id'=>8
		]);
		$renders=[];
		$renders['model'] = $model;
		$renders['meeting'] = $meeting;
		
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
						if($meeting->load(Yii::$app->request->post())){							
							$meeting->activity_id= $model->id;
							if (isset(Yii::$app->request->post()['create_revision'])){
								$meeting->create_revision = true;
								
							}
							/* $meeting->program_revision = (int)\backend\models\ProgramHistory::getRevision($meeting->program_id); */
							// GENERATE TRAINING NUMBER
							if(Yii::$app->request->post('generate_number')){
								$year = date('Y',strtotime($model->start));
								$program = Program::find()->where(['id'=>$meeting->program_id])->currentSatker()->active()->one();
								$program_owner = sprintf("%02s", $program->satker->sort);
								$activity_owner = sprintf("%02s", $model->satker->sort);							
								if($program_owner==$activity_owner) $activity_owner='00';
								$program_number = $program->number;
								$meeting_of_program_this_year = Activity::find()
									->where('start<=:start and YEAR(start)=:this_year',[':start'=>$model->start,':this_year'=>$year])			
									->currentSatker()
									->active()
									->count()+1;
								$meeting->number = $year.'-'.$program_owner.'-'.$activity_owner.'-'.$program_number.'.'.$meeting_of_program_this_year;
							}
							
							if($meeting->save()){								 
								Yii::$app->getSession()->setFlash('success', 'Meeting & activity data have saved.');
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
            throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
        }
    }
	
	public function actionRoom($activity_id, $satker_id=0)
    {
		$activity=$this->findModel($activity_id);
		
        $searchModel = new RoomSearch();
		if($satker_id===0) $satker_id = (int)$activity->location;
		if($satker_id<0) $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		if($satker_id=='all'){
			$queryParams['RoomSearch']=[
				'status'=>1,
			];
		}
		else{
			$queryParams['RoomSearch']=[
				'satker_id'=>$satker_id,
				'status'=>1,
			];
		}	
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

		// GET ALL TRAINING YEAR
		$satkers['all']='- All -';
		$satkers = yii\helpers\ArrayHelper::map(\backend\models\Reference::find()
			->where(['type'=>'satker'])
			//->orderBy(['eselon'=>'ASC',])
			//->active()
			->asArray()
			->all(), 'id', 'name');
		
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
		else{
			return $this->render('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
    }
	
	public function actionPic($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			// CEK ID 1213010300 IN TABLE ORGANISATION
			'organisation_1201010300'=>'PIC Meeting'
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
	
	public function actionSetRoom($activity_id,$room_id,$status)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		$activity=$this->findModel($activity_id);		
		$meeting = Meeting::findOne($activity->id);
		$room = Room::findOne($room_id);
		/* $status = 0;
		if($room->satker_id == $satker_id) $status = 1; */
		$model = new ActivityRoom([
			'activity_id'=>$meeting->activity_id,
			'room_id'=>$room->id,
			'start'=>$activity->start,
			'end'=>$activity->end,
			'status'=>$status,
		]);
		
        if($model->save()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have set');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
    }
	
	 public function actionUnsetRoom($activity_id,$room_id)
    {
        $model = ActivityRoom::find()->where(
			'activity_id=:activity_id AND room_id=:room_id',[':activity_id'=>$activity_id,':room_id'=>$room_id])->one();
		if($model->delete()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have unset');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
    }
	
	public function actionExportMeeting($year='',$status='all',$filetype='xlsx')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivityMeetingHrdSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$organisation_id = 8;
		if($status=='all'){
			if($year=='all'){
				$queryParams['ActivityMeetingHrdSearch']=[
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivityMeetingHrdSearch']=[
					'year' => $year,
					'organisation_id' => $organisation_id
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['ActivityMeetingHrdSearch']=[
					'status' => $status,
					'organisation_id' => $organisation_id
				];
			}
			else{
				$queryParams['ActivityMeetingHrdSearch']=[
					'year' => $year,
					'status' => $status,
					'organisation_id' => $organisation_id
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
			DIRECTORY_SEPARATOR.'general'.DIRECTORY_SEPARATOR.'meeting.list.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		$objPHPExcel->getProperties()->setTitle("Daftar Rapat");
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->setCellValue('A3', strtoupper(\Yii::$app->user->identity->employee->satker->name));
		$idx=7; // line 7
		$status_arr = ['0'=>'Draft','1'=>'Publish'];
		foreach($dataProvider->getModels() as $data){		
			if($idx==7){
				$activeSheet->setCellValue('A2', strtoupper($data->meeting->organisation->NM_UNIT_ORG));
				$activeSheet->setCellValue('A4', date('Y',strtotime($data->start)));
			}
			$activeSheet->insertNewRowBefore($idx+1,1);
			$locations = explode('|',$data->location);
			$location = '';
			if(Yii::$app->user->identity->employee->satker_id==$locations[0]){
				$location = $locations[1];
			}
			$activeSheet->setCellValue('A'.$idx, $idx-6)
					    ->setCellValue('B'.$idx, $data->name)
					    ->setCellValue('C'.$idx, date('d M y',strtotime($data->start)))
					    ->setCellValue('D'.$idx, date('d M y',strtotime($data->end)))
						->setCellValue('E'.$idx, $data->meeting->attendance_count_plan)
						->setCellValue('F'.$idx, ($data->hostel==1)?'Ya':'Tidak')
						->setCellValue('G'.$idx, $location)
					    ->setCellValue('H'.$idx, $status_arr[$data->status])
					    
						;
			$idx++;
		} 	
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="meeting.list.'.date('YmdHis').'.'.$filetype.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
		/* return $this->redirect(['student', 'id' => $id, 'status'=>$status]);	 */
    }
}
